<?php

namespace BIWS\CPTBuilder\fields\metabox;

defined('ABSPATH') or die('Nope!');

class CheckBoxField extends AbstractMetaBoxField
{
    public function __construct($id, $label, $show_in_column)
    {
        parent::__construct('boolean', $id, $label, $show_in_column);
    }

    public function renderForScript()
    {
        return ".addCheckBoxField('{$this->getId()}', '{$this->getLabel()}')";
    }

    public function addTableContent($column_name, $post_id)
    {
        if ($this->isShowInColumn() && $column_name == $this->getId()) {
            echo $this->getValue($post_id) ? __('Yes') : __('No');
        }
    }
}
