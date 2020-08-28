<?php

namespace BIWS\EventManager\fields\metabox;

defined('ABSPATH') or die('Nope!');

class TimeField extends AbstractMetaBoxField
{
    public function __construct($id, $label)
    {
        parent::__construct('string', $id, $label);
    }

    public function renderForScript()
    {
        return ".addTimeField('{$this->getId()}', '{$this->getLabel()}')";
    }
}
