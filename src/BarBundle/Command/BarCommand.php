<?php

namespace BarBundle\Command;

use ChainCommandBundle\Command\AbstractChainCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BarCommand extends AbstractChainCommand
{
    protected function configure()
    {
        $this
            ->setName('bar:hi')
            ->setDescription('Testing command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        $output->writeln('Hi from Bar!');
    }
}
