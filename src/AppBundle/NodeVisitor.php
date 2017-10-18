<?php

namespace AppBundle;

use Twig_Environment;
use Twig_Node;

class NodeVisitor extends \Twig_BaseNodeVisitor
{
    private $filename;

    private $blocks = [];

    /**
     * Called before child nodes are visited.
     *
     * @param Twig_Node $node The node to visit
     * @param Twig_Environment $env The Twig environment instance
     *
     * @return Twig_Node The modified node
     */
    protected function doEnterNode(Twig_Node $node, Twig_Environment $env)
    {
     //   dump($node);

        if ($node instanceof \Twig_Node_Module) {
            dump($node);
            $this->filename = $node->getAttribute('filename');
        }

        //echo __FUNCTION__ . '.' . get_class($node) . "\r\n";


        if ($node instanceof \Twig_Node_Block && $node->getAttribute('name') === 'foo') {

            $this->blocks[] = $node->getAttribute('name');


           // dump($node->getAttribute('name'));

            $body = $node->getNode('body');

            if($body instanceof \Twig_Node_Text) {

                
                $node1 = new \Twig_Node();

                $node1->setNode(0, new \Twig_Node_Include(
                    new \Twig_Node_Expression_Constant('before.html.twig', $body->getLine()),
                    null,
                    false,
                    true,
                    $body->getLine(
                    )));

                $node1->setNode(1, $body);

                $node1->setNode(2, new \Twig_Node_Include(
                    new \Twig_Node_Expression_Constant('after.html.twig', $body->getLine()),
                    null,
                    false,
                    true,
                    $body->getLine(
                )));

                $block = new \Twig_Node_Block('my_fooaaaaaa', new \Twig_Node_Text('apple', $body->getLine()), $body->getLine());

                //$node1->setNode(3, new \Twig_Node_Text('foo', $body->getLine()));
                //$node1->setNode(3, $block);

                $node->setNode('body', $node1);
            } else {

                $line = ($body->count() > 0) ? $body->getIterator()[$body->count() - 1]->getLine() : $body->getLine();

                $body->setNode($body->count() + 1, new \Twig_Node_Include(
                    new \Twig_Node_Expression_Constant('after.html.twig', $line),
                    null,
                    false,
                    true,
                    $line
                ));
            }


            //dump($body);
           // dump($node);

            //$node->setNode('foo', new \Twig_Node_Expression_Function());
            //echo $this->filename . '-' . $node->getAttribute('name') . "\r\n";
            //$this->scope = $this->scope->enter();
        }
        
        
        return $node;
    }

    /**
     * Called after child nodes are visited.
     *
     * @param Twig_Node $node The node to visit
     * @param Twig_Environment $env The Twig environment instance
     *
     * @return Twig_Node|false The modified node or false if the node must be removed
     */
    protected function doLeaveNode(Twig_Node $node, Twig_Environment $env)
    {
        if ($node instanceof \Twig_Node_Module) {
            $this->filename = null;
        }

        if ($node instanceof \Twig_Node_Block) {
            dump($node->getAttribute('name'));
          //  dump($node);
        }

        //echo __FUNCTION__ . '.' . get_class($node) . "\r\n";

        return $node;
    }

    /**
     * Returns the priority for this visitor.
     *
     * Priority should be between -10 and 10 (0 is the default).
     *
     * @return int The priority level
     */
    public function getPriority()
    {
        return 0;
    }
}