<?php

namespace FooBundle\Command;

use ChainCommandBundle\Command\AbstractChainCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FooCommand extends AbstractChainCommand
{
    protected function configure()
    {
        $this
            ->setName('foo:hello')
            ->setDescription('Testing command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        $output->writeln('Hello from Foo!');
    }
}
