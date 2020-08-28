<?php

namespace BIWS\EventManager\fields\metabox;

defined('ABSPATH') or die('Nope!');

class TextField extends AbstractMetaBoxField
{
    private $placeholder;

    public function __construct($id, $label, $placeholder)
    {
        parent::__construct('string', $id, $label);
        $this->placeholder = $placeholder;
    }

    public function renderForScript()
    {
        return ".addTextField('{$this->getId()}', '{$this->getLabel()}', '{$this->placeholder}')";
    }
}
