<?php
namespace App\Application\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class VendingMachineCommand extends Command
{
    protected static $defaultName = 'app:vending-machine';

    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        while (true) {
            $helper = $this->getHelper('question');
            $question = new Question('' );
            $userAnswer = $helper->ask($input, $output, $question);
        }
    }
}