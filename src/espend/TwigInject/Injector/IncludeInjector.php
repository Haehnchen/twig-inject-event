<?php

namespace espend\TwigInject\Injector;

class IncludeInjector extends InjectorAbstract
{
    /**
     * @var string
     */
    private $template;

    /**
     * @var string
     */
    private $block;

    /**
     * @var string
     */
    private $position;
    
    /**
     * @var string
     */
    private $include;

    public function __construct($template, $block, $include, $position)
    {
        $this->template = $template;
        $this->block = $block;
        $this->position = $position;
        $this->include = $include;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getInclude()
    {
        return $this->include;
    }
}