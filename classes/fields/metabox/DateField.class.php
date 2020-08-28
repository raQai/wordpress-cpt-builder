<?php

namespace BIWS\EventManager\fields\metabox;

defined('ABSPATH') or die('Nope!');

class DateField extends AbstractMetaBoxField
{
    public function __construct($id, $label)
    {
        parent::__construct('string', $id, $label);
    }

    public function renderForScript()
    {
        return ".addDateField('{$this->getId()}', '{$this->getLabel()}')";
    }
}
