<?php

namespace BIWS\EventManager\fields\metabox;

defined('ABSPATH') or die('Nope!');

class EmailField extends AbstractMetaBoxField
{
    private $placeholder;

    public function __construct($id, $label, $show_in_column, $placeholder)
    {
        parent::__construct('string', $id, $label, $show_in_column);
        $this->placeholder = $placeholder;
    }

    public function renderForScript()
    {
        return ".addEmailField('{$this->getId()}', '{$this->getLabel()}', '{$this->placeholder}')";
    }
}
