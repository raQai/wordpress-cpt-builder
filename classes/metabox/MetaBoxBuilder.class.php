<?php

namespace BIWS\EventManager\metabox;

use BIWS\EventManager\fields\FieldType;
use BIWS\EventManager\fields\metabox\CheckBoxField;
use BIWS\EventManager\fields\metabox\DateField;
use BIWS\EventManager\fields\metabox\NumberField;
use BIWS\EventManager\fields\metabox\TextField;
use BIWS\EventManager\fields\metabox\TimeField;
use InvalidArgumentException;

defined('ABSPATH') or die('Nope!');

class MetaBoxBuilder
{
    private $id;
    private $title;
    private $args = array();
    private $fields = array();

    private function __construct($id)
    {
        $this->id = $id;
    }

    public static function create($id)
    {
        return new MetaBoxBuilder($id);
    }

    public function title($title)
    {
        $this->title = $title;
        return $this;
    }

    public function args($args)
    {
        $this->args = $args;
        return $this;
    }

    public function addField($type, $id, $label, $show_in_column = false, $placeholder = '')
    {
        switch ($type) {
            case FieldType::NUMBER:
                $this->fields[] = new NumberField($id, $label, $show_in_column);
                break;
            case FieldType::TEXT:
                $this->fields[] = new TextField($id, $label, $show_in_column, $placeholder);
                break;
            case FieldType::CHECKBOX:
                $this->fields[] = new CheckBoxField($id, $label, $show_in_column);
                break;
            case FieldType::DATE:
                $this->fields[] = new DateField($id, $label, $show_in_column);
                break;
            case FieldType::TIME:
                $this->fields[] = new TimeField($id, $label, $show_in_column);
                break;
            default:
                throw new InvalidArgumentException('FieldType not supported for MetaBoxes. FieldType = ' . $type);
        }
        return $this;
    }

    public function build()
    {
        return new MetaBox(
            $this->id,
            $this->title,
            $this->args,
            $this->fields
        );
    }
}
