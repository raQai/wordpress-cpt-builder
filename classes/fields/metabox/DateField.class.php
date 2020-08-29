<?php

namespace BIWS\EventManager\fields\metabox;

defined('ABSPATH') or die('Nope!');

class DateField extends AbstractMetaBoxField
{
    public function __construct($id, $label, $show_in_column)
    {
        parent::__construct('string', $id, $label, $show_in_column);
    }

    public function renderForScript()
    {
        return ".addDateField('{$this->getId()}', '{$this->getLabel()}')";
    }

    public function addTableContent($column_name, $post_id)
    {
        if ($this->isShowInColumn() && $column_name == $this->getId()) {
            $date = $this->getValue($post_id);
            if ($date) {
                echo date_i18n('d. F y', strtotime($this->getValue($post_id)));
            } else {
                echo '--';
            }
        }
    }
}
