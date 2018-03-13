<?php

namespace Src;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class sayHelloCommand extends Command
{
    public function configure()
    {
        $this->setName('sayHelloTo')
             ->setDescription('Offer a greeting to given person')
             ->addArgument('name', InputArgument::REQUIRED, 'Your Name')
             ->addOption('greeting',null,InputOption::VALUE_OPTIONAL,'Override the default greeting','Hello');
    }

    public function execute(InputInterface $input , OutputInterface $output)
    {
        $message = sprintf('%s %s',$input->getOption('greeting'),$input->getArgument('name'));
        $output->writeln("<info>{$message}</info>");
    }
}
