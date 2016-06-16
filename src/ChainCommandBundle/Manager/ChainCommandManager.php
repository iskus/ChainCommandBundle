<?php

namespace ChainCommandBundle\Manager;

use ChainCommandBundle\Command\AbstractChainCommand;

class ChainCommandManager
{
    /** @var array $parentCommandName => [$chainCommand, $chainCommand2 ...] */
    private $chains = [];

    /**
     * @param array $chains
     */
    public function __construct(array $chains)
    {
        $this->chains = $chains;
    }

    /**
     * @param AbstractChainCommand $parentCommand
     * @return string[]
     */
    public function getChainCommandNames(AbstractChainCommand $parentCommand)
    {
        $parentCommandName = $parentCommand->getName();
        if (isset($this->chains[$parentCommandName])) {
            return $this->chains[$parentCommandName];
        }
        return [];
    }

    /**
     * @param AbstractChainCommand $chainCommand
     * @return null|string
     */
    public function getParentChainCommandName(AbstractChainCommand $chainCommand)
    {
        foreach ($this->chains as $parentCommandName => $chainCommands) {
            if (in_array($chainCommand->getName(), $chainCommands)) {
                return $parentCommandName;
            }
        }
        return null;
    }
}
