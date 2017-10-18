<?php

namespace AppBundle;

class NodeExtVisitor extends \Twig_Extension
{
    public function getNodeVisitors()
    {
        return [
            //new NodeVisitor(),
        ];
    }
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'foobar';
    }
}