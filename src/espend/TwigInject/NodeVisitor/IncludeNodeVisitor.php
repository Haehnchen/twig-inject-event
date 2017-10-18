<?php

namespace espend\TwigInject\NodeVisitor;

use espend\TwigInject\Injector\InjectorInterface;
use Symfony\Bridge\Twig\NodeVisitor\Scope;
use Symfony\Component\Config\Definition\NodeInterface;
use Twig_Environment;
use Twig_Node;

class IncludeNodeVisitor extends \Twig_BaseNodeVisitor
{
    /**
     * @var Scope
     */
    private $scope;

    /**
     * @var \espend\TwigInject\Injector\InjectorInterface[]
     */
    private $injectors;

    public function __construct(array $injectors)
    {
        $this->scope = new Scope();
        $this->injectors = $injectors;
    }

    public function add(InjectorInterface $injector)
    {
        $this->injectors[] = $injector;
    }

    /**
     * {@inheritdoc}
     */
    protected function doEnterNode(Twig_Node $node, Twig_Environment $env)
    {
        if ($node instanceof \Twig_Node_Module && $node->hasAttribute('filename')) {
            $this->scope = $this->scope->enter();

            $filename = $node->getAttribute('filename');

            $injectors = array_filter($this->injectors, function(InjectorInterface $injector) use ($filename) {
                return $injector->getTemplate() === $filename;
            });


            if(count($injectors) > 0) {
                $this->scope->set('filename', $filename);
                $this->scope->set('injectors', array_values($injectors));
            }
        }

        if(!$this->scope->has('filename')) {
            return $node;
        }

        if(!$node->hasNode('body')) {
            return $node;
        }

        if(!($node instanceof \Twig_Node_Block) || !$node->hasAttribute('name') || !$node->hasNode('body')) {
            return $node;
        }


        $block = $node->getAttribute('name');
        $injectors = array_filter($this->scope->get('injectors', []), function(InjectorInterface $injector) use ($block) {
            return $injector->getBlock() === $block;
        });

        if(count($injectors) === 0) {
            return $node;
        }

        $body = $node->getNode('body');
        if($body instanceof \Twig_Node_Text) {
            list($prepends, $appends) = $this->extractBlockPositions($block);
            if(count($prepends) === 0 & count($appends) === 0) {
                return $node;
            }

            $wrapper = new \Twig_Node();

            foreach ($appends as $append) {
                $this->addIncludeNode($wrapper, $append, $body);
            }

            $wrapper->setNode(1, $body);

            foreach ($appends as $append) {
                $this->addIncludeNode($wrapper, $append, $body);
            }

            $node->setNode('body', $wrapper);
        } else {
            list($prepends, $appends) = $this->extractBlockPositions($block);
            if(count($prepends) === 0 & count($appends) === 0) {
                return $node;
            }

            $line = ($body->count() > 0) ? $body->getIterator()[$body->count() - 1]->getLine() : $body->getLine();

            /*
            foreach ($appends as $append) {
                $body->setNode($body->count() + 1, new \Twig_Node_Include(
                    new \Twig_Node_Expression_Constant($append->getTemplate(), $body->getLine()),
                    null,
                    false,
                    true,
                    $line
                ));
            }
            */
            //$this->addIncludeNode($wrapper, $append, $body);
        }
        
        return $node;
    }

    /**
     * {@inheritdoc}
     */
    protected function doLeaveNode(Twig_Node $node, Twig_Environment $env)
    {
        if ($node instanceof \Twig_Node_Module) {
            $this->scope = $this->scope->leave();
        }

        return $node;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 0;
    }

    /**
     * @param $wrapper
     * @param $append
     * @param $body
     */
    protected function addIncludeNode(Twig_Node $wrapper, InjectorInterface $append, Twig_Node $body)
    {
        $wrapper->setNode($wrapper->count(), new \Twig_Node_Include(
            new \Twig_Node_Expression_Constant($append->getTemplate(), $body->getLine()),
            null,
            false,
            true,
            $body->getLine()
        ));
    }

    /**
     * @param $block
     * @return InjectorInterface[]
     */
    private function extractBlockPositions($block)
    {
        $prepends = array_filter($this->scope->get('injectors', []), function (InjectorInterface $injector) use ($block) {
            return $injector->getPosition() === InjectorInterface::POSITION_PREPEND;
        });

        $appends = array_filter($this->scope->get('injectors', []), function (InjectorInterface $injector) use ($block) {
            return $injector->getPosition() === InjectorInterface::POSITION_APPEND;
        });

        return [$prepends, $appends];
    }
}