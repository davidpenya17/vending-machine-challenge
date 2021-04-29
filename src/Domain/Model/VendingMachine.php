<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Exception\InsufficientAvailableChangeException;
use App\Domain\Exception\InvalidProductNameException;

class VendingMachine
{
    private array $products;

    private array $availableCoins;

    private array $lastProductChange;

    private array $lastCoins;

    public function __construct()
    {
        $this->products = [
            new Water(10),
            new Juice(10),
            new Soda(10),
        ];
        $this->availableCoins = [
            new Coin(0.05),
            new Coin(0.05),
            new Coin(0.10),
            new Coin(0.10),
            new Coin(0.25),
            new Coin(0.25),
            new Coin(1),
            new Coin(1),
        ];
        $this->lastProductChange = [];
        $this->lastCoins         = [];
    }

    public function getAvailableCoins(): array
    {
        return $this->availableCoins;
    }

    public function getLastProductChange(): array
    {
        return $this->lastProductChange;
    }

    public function getLastCoins(): array
    {
        return $this->lastCoins;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function getProductByName(string $productName): Product
    {
        foreach ($this->getProducts() as $product) {
            if ($product->getName() === $productName) {
                return $product;
            }
        }

        throw new InvalidProductNameException($productName);
    }

    public function setAvailableCoins(array $coins): void
    {
        $this->availableCoins = $coins;
    }

    public function setLastProductChange(array $coins): void
    {
        $this->lastProductChange = $coins;
    }

    public function setLastCoins(array $coins): void
    {
        $this->lastCoins = $coins;
    }

    public function removeCoins(array $coins): void
    {
        foreach ($coins as $coin) {
            $this->removeCoin($coin);
        }
    }

    public function removeCoin(Coin $coin): void
    {
        $availableCoins     = $this->getAvailableCoins();
        $availableCoinIndex = array_search($coin, $availableCoins);
        if ($availableCoinIndex) {
            unset($availableCoins[$availableCoinIndex]);
        }

        $this->availableCoins = array_values($availableCoins);
    }

    public function addCoin(Coin $coin): void
    {
        $this->availableCoins[] = $coin;
    }

    public function addCoins(array $coins): void
    {
        foreach ($coins as $coin) {
            $this->addCoin($coin);
        }
    }

    public function createCoins(array $coins): array
    {
        $newCoins = [];
        foreach ($coins as $coinValue) {
            $coin       = new Coin($coinValue);
            $newCoins[] = $coin;
        }

        return $newCoins;
    }

    public function buyProduct(Product $product, array $coins): void
    {
        // create coins
        $newCoins = $this->createCoins($coins);

        // add coins
        $this->addCoins($newCoins);

        if ($product->isAvailableToBuy($newCoins)) {
            // calculate product change coins
            $productChangeCoins = $this->calculateProductChange($product->getPrice(), $newCoins);
            if (!empty($productChangeCoins)) {
                $this->removeCoins($productChangeCoins);
            }

            // set last coins
            $this->setLastCoins($newCoins);

            // remove product
            $product->removeProductFromStock();
        }
    }

    public function calculateProductChange(float $price, array $coins): array
    {
        $totalAmountCoins = 0;
        foreach ($coins as $coin) {
            $totalAmountCoins += $coin->getValue();
        }

        $productChange  = $totalAmountCoins - $price;
        $changeCoins    = [];
        $availableCoins = $this->getAvailableCoins();
        usort($availableCoins, function (Coin $coin1, Coin $coin2
        ) {
            return $coin1->getValue() < $coin2->getValue();
        });
        foreach ($availableCoins as $availableCoin) {
            if ($availableCoin->getValue() <= $productChange) {
                $changeCoins[] = $availableCoin;
                $productChange = round($productChange - $availableCoin->getValue(), 2);
            }
        }

        if ($productChange > 0) {
            throw new InsufficientAvailableChangeException($productChange);
        }

        $this->setLastProductChange($changeCoins);

        return $changeCoins;
    }
}
