<?php

namespace espend\TwigInject\Injector;

abstract class InjectorAbstract implements InjectorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 0;
    }
}