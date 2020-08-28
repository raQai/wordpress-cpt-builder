<?php

namespace BIWS\EventManager\fields\metabox;

defined('ABSPATH') or die('Nope!');

class CheckBoxField extends AbstractMetaBoxField
{
    public function __construct($id, $label)
    {
        parent::__construct('boolean', $id, $label);
    }

    public function renderForScript()
    {
        return ".addCheckBoxField('{$this->getId()}', '{$this->getLabel()}')";
    }
}
