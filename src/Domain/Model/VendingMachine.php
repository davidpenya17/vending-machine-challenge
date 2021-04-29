<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Exception\InsufficientAvailableChangeException;
use App\Domain\Exception\InvalidCoinsException;
use App\Domain\Exception\InvalidProductNameException;

class VendingMachine
{
    private array $availableCoins;
    private array $availableChange;
    private array $lastProductChange;
    private array $products;

    public function __construct()
    {
        $this->availableCoins    = [1, 0.25, 0.10, 0.05];
        $this->availableChange   = [0.05, 0.05, 0.10, 0.10, 0.25, 0.25, 1, 1];
        $this->lastProductChange = [];
        $this->products          = [
            new Water(10),
            new Juice(10),
            new Soda(10),
        ];
    }

    /**
     * @return array
     */
    public function getAvailableCoins()
    {
        return $this->availableCoins;
    }

    /**
     * @return array
     */
    public function getAvailableChange()
    {
        return $this->availableChange;
    }

    /**
     * @return array
     */
    public function getLastProductChange()
    {
        return $this->lastProductChange;
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

    public function validateCoins(array $coins): void
    {
        foreach ($coins as $coin) {
            if (!in_array($coin, $this->getAvailableCoins())) {
                throw new InvalidCoinsException($coins);
            }
        }
    }

    public function buyProduct(Product $product, array $coins): void
    {
        if ($product->isAvailableToBuy($coins)) {
            // add coins
            $this->addCoins($coins);

            // calculate product change
            $productChange = $this->calculateProductChange($product->getPrice(), $coins);
            if (!empty($productChange)) {
                $this->removeCoins($productChange);
            }

            // set product change
            $this->setLastProductChange($productChange);

            // remove product
            $product->removeProductFromStock();
        }
    }

    public function calculateProductChange(float $price, array $coins): array
    {
        $sumCoins       = array_sum($coins);
        $productChange  = $sumCoins - $price;
        $changeCoins    = [];
        $availableCoins = $this->getAvailableChange();
        usort($availableCoins, function ($a, $b) {
            return $a < $b;
        });
        foreach ($availableCoins as $index => $availableCoin) {
            if ($availableCoin <= $productChange) {
                $changeCoins[] = round($availableCoin, 2);
                $productChange = round($productChange - $availableCoin, 2);
            }
        }
        if ($productChange > 0) {
            throw new InsufficientAvailableChangeException($productChange, $availableCoins);
        }

        return $changeCoins;
    }

    public function removeCoins(array $coins): void
    {
        $availableCoins = $this->getAvailableChange();
        foreach ($coins as $coin) {
            $availableCoinIndex = array_search($coin, $availableCoins);
            if ($availableCoinIndex) {
                unset($availableCoins[$availableCoinIndex]);
            }
        }
        $this->setAvailableChange(array_values($availableCoins));
    }

    public function addCoins(array $coins): void
    {
        $this->setAvailableChange(array_merge($this->getAvailableChange(), $coins));
    }

    public function setAvailableChange(array $coins): void
    {
        $this->availableChange = $coins;
    }

    public function setLastProductChange(array $coins): void
    {
        $this->lastProductChange = $coins;
    }
}
