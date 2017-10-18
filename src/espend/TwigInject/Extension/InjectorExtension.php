<?php

namespace espend\TwigInject\Extension;



use espend\TwigInject\Injector\IncludeInjector;
use espend\TwigInject\NodeVisitor\IncludeNodeVisitor;

class InjectorExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getNodeVisitors()
    {
        return [
            new IncludeNodeVisitor([
                new IncludeInjector('default/index.html.twig', 'foo2', 'before.html.twig', IncludeInjector::POSITION_PREPEND),
                new IncludeInjector('default/index.html.twig', 'foo2', 'after.html.twig', IncludeInjector::POSITION_APPEND),
                new IncludeInjector('default/index.html.twig', 'foo', 'after.html.twig', IncludeInjector::POSITION_APPEND),
            ]),
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'twig_injector';
    }
}