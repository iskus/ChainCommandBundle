<?php

namespace ChainCommandBundle\Manager;

use ChainCommandBundle\Command\AbstractChainCommand;
use ChainCommandBundle\DependencyInjection\Configuration;

class ChainCommandManager
{
    /** @var array $parentCommandName => [$chainCommandName, $chainCommandArgv] */
    private $config = [];

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param AbstractChainCommand $parentCommand
     * @return null|string
     */
    public function getChainCommandName(AbstractChainCommand $parentCommand)
    {
        $parentCommandName = $parentCommand->getName();
        if (isset($this->config[$parentCommandName])) {
            return $this->config[$parentCommandName][Configuration::COMMAND_NAME_KEY];
        }
        return null;
    }

    /**
     * @param AbstractChainCommand $chainCommand
     * @return null|string
     */
    public function getParentChainCommandName(AbstractChainCommand $chainCommand)
    {
        foreach ($this->config as $parentCommandName => $config) {
            if ($config[Configuration::COMMAND_NAME_KEY] === $chainCommand->getName()) {
                return $parentCommandName;
            }
        }
        return null;
    }
}
