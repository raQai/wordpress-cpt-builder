<?php

namespace BIWS\EventManager\taxonomy;

use BIWS\EventManager\fields\FieldType;
use BIWS\EventManager\fields\taxonomy\ColorField;
use BIWS\EventManager\fields\taxonomy\ImageField;
use BIWS\EventManager\fields\taxonomy\NumberField;
use BIWS\EventManager\fields\taxonomy\TextField;
use InvalidArgumentException;

defined('ABSPATH') or die('Nope!');

class TaxonomyBuilder
{
    private $slug;

    private $args = array();

    private $fields = array();

    private function __construct($slug)
    {
        $this->slug = $slug;
    }

    public static function create($slug)
    {
        return new TaxonomyBuilder($slug);
    }

    public function args($args)
    {
        $this->args = $args;
        return $this;
    }

    public function addField($type, $id, $label, $required = false, $show_in_column = false, $obj = null)
    {
        switch ($type) {
            case FieldType::NUMBER:
                $this->fields[] = new NumberField(
                    $id,
                    $label,
                    $required,
                    $show_in_column,
                );
                break;
            case FieldType::TEXT:
                $this->fields[] = new TextField(
                    $id,
                    $label,
                    $required,
                    $show_in_column,
                    $obj
                );
                break;
            case FieldType::COLOR:
                $this->fields[] = new ColorField(
                    $id,
                    $label,
                    $show_in_column,
                    $obj
                );
                break;
            case FieldType::IMAGE:
                $this->fields[] = new ImageField($id, $label);
                break;
            default:
                throw new InvalidArgumentException('FieldType not supported for taxonomies. FieldType = ' . $type);
        }
        return $this;
    }

    public function build()
    {
        return new Taxonomy($this->slug, $this->args, $this->fields);
    }
}
