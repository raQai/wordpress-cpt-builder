<?php

namespace BIWS\EventManager\fields\metabox;

defined('ABSPATH') or die('Nope!');

abstract class AbstractMetaBoxField implements MetaBoxFieldInterface
{
    private $type;

    private $id;

    private $label;

    private $show_in_column;

    function getId()
    {
        return $this->id;
    }

    function getLabel()
    {
        return $this->label;
    }

    function isShowInColumn()
    {
        return $this->show_in_column;
    }

    function __construct($type, $id, $label, $show_in_column)
    {
        $this->type = $type;
        $this->id = $id;
        $this->label = $label;
        $this->show_in_column = $show_in_column;
    }

    function init($post_slug)
    {
        register_post_meta($post_slug, $this->id, array(
            'show_in_rest' => true,
            'single' => true,
            'type' => $this->type,
        ));
    }

    public function getValue($post_id)
    {
        return get_post_meta($post_id, $this->id, true);
    }

    public function addTableColumn($columns)
    {
        if ($this->show_in_column) {
            $columns[$this->id] = $this->label;
        }
        return $columns;
    }

    public function addTableContent($column_name, $post_id)
    {
        if ($this->show_in_column && $column_name == $this->id) {
            $val = $this->getValue($post_id);
            echo $val !== null && $val !== '' ? $val : '--';
        }
    }
}
