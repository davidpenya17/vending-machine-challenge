<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Query\GetLastProductChangeQuery;
use App\Domain\Exception\InvalidActionException;
use App\Domain\Exception\InvalidArgumentsException;
use App\Domain\Service\VendingMachineRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Messenger\MessageBusInterface;

class VendingMachineCommand extends Command
{
    private MessageBusInterface $bus;
    private VendingMachineRepository $vendingMachineRepository;

    protected static $defaultName = 'app:vending-machine';

    public function __construct(
        MessageBusInterface $bus,
        VendingMachineRepository $vendingMachineRepository)
    {
        $this->bus                      = $bus;
        $this->vendingMachineRepository = $vendingMachineRepository;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        while (true) {
            try {
                $helper     = $this->getHelper('question');
                $question   = new Question('');
                $userInput  = $helper->ask($input, $output, $question);
                $userAnswer = explode(',', $userInput);

                if (count($userAnswer) <= 1) {
                    throw new InvalidArgumentsException($userInput);
                }

                $action                = trim($userAnswer[count($userAnswer) - 1]);
                $isProductStockService = !is_numeric($userAnswer[count($userAnswer) - 2]);
                if ('SERVICE' !== $action || !$isProductStockService) {
                    $entryCoins = array_slice($userAnswer, 0, -1);
                    $coins      = array_map(function ($coin) {
                        return round($coin, 2);
                    }, $entryCoins);

                    $this->bus->dispatch(new ValidateCoinsCommand($coins));
                }

                switch ($action) {
                    case 'GET-SODA':
                    case 'GET-WATER':
                    case 'GET-JUICE':
                        $product = explode('-', $action)[1];
                        $this->bus->dispatch(new BuyProductCommand(
                            $product,
                            $coins
                        ));
                        $query         = new GetLastProductChangeQuery($this->vendingMachineRepository);
                        $productChange = $query->getResult();
                        $response      = array_merge([$product], $productChange);
                        $output->writeln(implode(', ', $response));
                        break;
                    case 'RETURN-COIN':
                        $output->writeln(implode(', ', $coins));
                        break;
                    case 'SERVICE':
                        if ($isProductStockService) {
                            // dispatch set product service
                        } else {
                            // dispatch set available change
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
