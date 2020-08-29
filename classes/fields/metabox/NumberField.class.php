<?php

namespace BIWS\EventManager\fields\metabox;

defined('ABSPATH') or die('Nope!');

class NumberField extends AbstractMetaBoxField
{
    public function __construct($id, $label, $show_in_column)
    {
        parent::__construct('integer', $id, $label, $show_in_column);
    }

    public function renderForScript()
    {
        return ".addNumberField('{$this->getId()}', '{$this->getLabel()}')";
    }
}
