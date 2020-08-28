<?php

namespace BIWS\EventManager\fields\metabox;

defined('ABSPATH') or die('Nope!');

class NumberField extends AbstractMetaBoxField
{
    public function __construct($id, $label)
    {
        parent::__construct('integer', $id, $label);
    }

    public function renderForScript()
    {
        return ".addNumberField('{$this->getId()}', '{$this->getLabel()}')";
    }
}
