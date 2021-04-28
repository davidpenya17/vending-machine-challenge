<?php
namespace App\Application\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Messenger\MessageBusInterface;

class VendingMachineCommand extends Command
{
    private MessageBusInterface $bus;

    protected static $defaultName = 'app:vending-machine';

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        while (true) {
            try {
                $helper = $this->getHelper('question');
                $question = new Question('');
                $userAnswer = $helper->ask($input, $output, $question);

                if (empty($userAnswer)) {
                    throw new \RuntimeException(
                        'No arguments passed'
                    );
                }

                $userAnswer = explode(',', $userAnswer);
                $action = trim($userAnswer[count($userAnswer) - 1]);
                $coins = array_slice($userAnswer, 0, -1);

                switch ($action) {
                    case 'GET-SODA':
                    case 'GET-WATER':
                    case 'GET-JUICE':
                        $product = explode('-', $action)[1];
                        $output->writeln($product);
                        break;
                    case 'RETURN-COIN':
                        $coins = array_slice($userAnswer, 0, -1);
                        $output->writeln(implode(",", $coins));
                        break;
                    case 'SERVICE':
                        $output->writeln('service');
                        break;
                    default:
                        throw new \RuntimeException(
                            'Error!!'
                        );
                        break;
                }
            } catch (\Exception $exception) {
                $output->writeln($exception->getMessage());
            }
        }
    }
}
