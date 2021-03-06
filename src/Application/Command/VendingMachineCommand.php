<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Query\GetAvailableCoinsQuery;
use App\Application\Query\GetLastCoinsQuery;
use App\Application\Query\GetLastProductChangeQuery;
use App\Application\Query\GetProductStockQuery;
use App\Domain\Exception\InvalidActionException;
use App\Domain\Exception\InvalidArgumentsException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Messenger\MessageBusInterface;

class VendingMachineCommand extends Command
{
    private MessageBusInterface $bus;
    private GetLastProductChangeQuery $getLastProductChangeQuery;
    private GetLastCoinsQuery $getLastCoinsQuery;
    private GetProductStockQuery $getProductStockQuery;
    private GetAvailableCoinsQuery $getAvailableCoinsQuery;

    protected static $defaultName = 'app:vending-machine';

    public function __construct(
        MessageBusInterface $bus,
        GetLastProductChangeQuery $getLastProductChangeQuery,
        GetLastCoinsQuery $getLastCoinsQuery,
        GetProductStockQuery $getProductStockQuery,
        GetAvailableCoinsQuery $getAvailableCoinsQuery)
    {
        $this->bus                       = $bus;
        $this->getLastProductChangeQuery = $getLastProductChangeQuery;
        $this->getLastCoinsQuery         = $getLastCoinsQuery;
        $this->getProductStockQuery      = $getProductStockQuery;
        $this->getAvailableCoinsQuery    = $getAvailableCoinsQuery;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        while (true) {
            try {
                $helper     = $this->getHelper('question');
                $question   = new Question('');
                $userInput  = $helper->ask($input, $output, $question);
                $userAnswer = !empty($userInput) ? explode(',', $userInput) : [];

                if (count($userAnswer) <= 1) {
                    throw new InvalidArgumentsException($userInput);
                }

                $action = trim($userAnswer[count($userAnswer) - 1]);
                switch ($action) {
                    case 'GET-SODA':
                    case 'GET-WATER':
                    case 'GET-JUICE':
                        $product    = explode('-', $action)[1];
                        $entryCoins = array_slice($userAnswer, 0, -1);
                        $coins      = array_map(function ($coin) {
                            return round($coin, 2);
                        }, $entryCoins);
                        $this->bus->dispatch(new BuyProductCommand(
                            $product,
                            $coins
                        ));
                        $productChange = $this->getLastProductChangeQuery->getResult();
                        $response      = array_merge([$product], $productChange);
                        $output->writeln(implode(', ', $response));
                        break;
                    case 'RETURN-COIN':
                        $entryCoins = array_slice($userAnswer, 0, -1);
                        $coins      = array_map(function ($coin) {
                            return round($coin, 2);
                        }, $entryCoins);
                        $this->bus->dispatch(new ReturnCoinsCommand(
                            $coins
                        ));
                        $coins = $this->getLastCoinsQuery->getResult();
                        $output->writeln(implode(', ', $coins));
                        break;
                    case 'SERVICE':
                        $isProductStockService = !is_numeric($userAnswer[count($userAnswer) - 2]);
                        if ($isProductStockService) {
                            $product = trim($userAnswer[count($userAnswer) - 2]);
                            $stock   = intval($userAnswer[0]);
                            $this->bus->dispatch(new SetProductStockCommand(
                                $product,
                                $stock
                            ));
                            $productStock = $this->getProductStockQuery->getResult($product);
                            $output->writeln("$product, $productStock");
                        } else {
                            $entryCoins = array_slice($userAnswer, 0, -1);
                            $coins      = array_map(function ($coin) {
                                return round($coin, 2);
                            }, $entryCoins);
                            $this->bus->dispatch(new SetAvailableCoinsCommand($coins));
                            $availableCoins = $this->getAvailableCoinsQuery->getResult();
                            $response       = 'AVAILABLE-CHANGE, '.implode(', ', $availableCoins);
                            $output->writeln($response);
                        }
                        break;
                    default:
                        throw new InvalidActionException($action);
                        break;
                }
            } catch (\Exception $exception) {
                $output->writeln($exception->getMessage());
            }
        }
    }
}
