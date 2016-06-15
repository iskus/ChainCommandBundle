<?php

namespace ChainCommandBundle;

use ChainCommandBundle\DependencyInjection\ChainCommandsExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ChainCommandBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new ChainCommandsExtension();
    }
}
