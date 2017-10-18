<?php

namespace espend\TwigInject\Injector;

interface InjectorInterface
{
    /**
     * Last item in node list
     */
    const POSITION_PREPEND = 'position.prepend';
    
    /**
     * In top of block node list
     */
    const POSITION_APPEND = 'position.append';

    /**
     * @return string
     */
    public function getBlock();

    /**
     * @return string
     */
    public function getTemplate();

    /**
     * @return string
     */
    public function getInclude();

    /**
     * @return string
     */
    public function getPosition();

    /**
     * @return int
     */
    public function getPriority();
}