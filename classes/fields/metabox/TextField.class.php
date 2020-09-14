<?php

namespace BIWS\CPTBuilder\fields\metabox;

defined('ABSPATH') or die('Nope!');

class TextField extends AbstractMetaBoxField
{
    private $placeholder;

    public function __construct($id, $label, $show_in_column, $placeholder)
    {
        parent::__construct('string', $id, $label, $show_in_column);
        $this->placeholder = $placeholder;
    }

    public function renderForScript()
    {
        return ".addTextField('{$this->getId()}', '{$this->getLabel()}', '{$this->placeholder}')";
    }
}
