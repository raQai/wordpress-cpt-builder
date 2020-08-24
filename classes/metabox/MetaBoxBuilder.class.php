<?php

namespace BIWS\EventManager\metabox;

use BIWS\EventManager\fields\FieldType;
use BIWS\EventManager\fields\metabox\NumberField;
use BIWS\EventManager\fields\metabox\TextField;
use InvalidArgumentException;

defined('ABSPATH') or die('Nope!');

class MetaBoxBuilder
{
    private $id;
    private $title;
    private $context;
    private $priority;
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

    public function context($context)
    {
        $this->context = $context;
        return $this;
    }

    public function priority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    public function args($args)
    {
        $this->args = $args;
        return $this;
    }

    public function addField($type, $id, $label, $default = null, $placeholder = '')
    {
        switch ($type) {
            case FieldType::NUMBER:
                $this->fields[] = new NumberField($id, $label, $default);
                break;
            case FieldType::TEXT:
                $this->fields[] = new TextField($id, $label, $default, $placeholder);
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
            $this->context,
            $this->priority,
            $this->args,
            $this->fields
        );
    }
}
