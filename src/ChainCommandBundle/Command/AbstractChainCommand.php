<?php

namespace ChainCommandBundle\Command;

use ChainCommandBundle\Exception\UnchainedCommandRan;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractChainCommand extends ContainerAwareCommand
{
    const CHAINED_ARGUMENT = 'chained';

    final public function run(InputInterface $input, OutputInterface $output)
    {
        try {
            $returnCode = parent::run($input, $output);
            $chainCommand = $this->getNextChainCommand();
            if ($chainCommand) {
                $chainCommand->addArgument(self::CHAINED_ARGUMENT, InputArgument::REQUIRED);

                return $this->runChainCommand($chainCommand);
            }
            return $returnCode;
        } catch (UnchainedCommandRan $ex) {
            return -1;
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws UnchainedCommandRan When command is registered as chain, but ran separately
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $parent = $this->getParent();
        if ($parent && !$input->hasArgument(self::CHAINED_ARGUMENT)) {
            $output->writeln($this->prepareParentChainErrorMessage($parent));
            throw new UnchainedCommandRan();
        }
        return 0;
    }

    /**
     * @return AbstractChainCommand|null
     */
    private function getParent()
    {
        if ($parentChainCommandName = $this->getParentChainCommandName()) {
            return $this->findChainCommandByName($parentChainCommandName);
        }
        return null;
    }

    /**
     * @return string|null
     */
    private function getParentChainCommandName()
    {
        return $this->getContainer()->get('chain_command.manager')->getParentChainCommandName($this);
    }

    /**
     * @param AbstractChainCommand $parentChainCommand
     * @return string
     */
    private function prepareParentChainErrorMessage(AbstractChainCommand $parentChainCommand)
    {
        return sprintf(
            '<error>Error: "%s" command is a member of "%s" command chain and cannot be executed on its own.</error>',
            $this->getName(),
            $parentChainCommand->getName()
        );
    }

    /**
     * @return AbstractChainCommand|null
     */
    public function getNextChainCommand()
    {
        if ($chainCommandName = $this->getNextChainCommandName()) {
            return $this->findChainCommandByName($chainCommandName);
        }
        return null;
    }

    /**
     * @return string|null
     */
    private function getNextChainCommandName()
    {
        return $this->getContainer()->get('chain_command.manager')->getChainCommandName($this);
    }

    /**
     * @param $name
     * @return AbstractChainCommand|null
     */
    private function findChainCommandByName($name)
    {
        foreach ($this->getApplication()->all() as $command) {
            if ($command->getName() === $name && $command instanceof AbstractChainCommand) {
                return $command;
            }
        }
        return null;
    }

    /**
     * @param AbstractChainCommand $command
     * @throws \Exception
     */
    private function runChainCommand(AbstractChainCommand $command)
    {
        $this->getApplication()->run(new ArrayInput([
            'command' => $command->getName(),
            self::CHAINED_ARGUMENT => true,
        ]));
    }
}
