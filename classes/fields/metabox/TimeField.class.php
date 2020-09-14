<?php

namespace BIWS\CPTBuilder\fields\metabox;

defined('ABSPATH') or die('Nope!');

class TimeField extends AbstractMetaBoxField
{
    public function __construct($id, $label, $show_in_column)
    {
        parent::__construct('string', $id, $label, $show_in_column);
    }

    public function renderForScript()
    {
        return ".addTimeField('{$this->getId()}', '{$this->getLabel()}')";
    }
}
