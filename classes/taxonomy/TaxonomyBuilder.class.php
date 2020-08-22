<?php

namespace BIWS\EventManager\taxonomy;

defined('ABSPATH') or die('Nope!');

class TaxonomyBuilder
{
    private $slug;

    private $args;

    private $fields;

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

    public function addField($type, $id, $label, $required = false, $default = null, $placeholder = '')
    {
        switch ($type) {
            case fields\FieldType::NUMBER:
                $this->fields[] = new fields\NumberField(
                    $id,
                    $label,
                    $required,
                    $default
                );
                break;
            case fields\FieldType::TEXT:
                $this->fields[] = new fields\TextField(
                    $id,
                    $label,
                    $required,
                    $default,
                    $placeholder
                );
                break;
            case fields\FieldType::COLOR:
                $this->fields[] = new fields\ColorField(
                    $id,
                    $label,
                    $required,
                    $default
                );
                break;
            case fields\FieldType::IMAGE:
                $this->fields[] = new fields\ImageField(
                    $id,
                    $label,
                    $required,
                    $default
                );
                break;
        }
        return $this;
    }

    public function build()
    {
        return new Taxonomy($this->slug, $this->args, $this->fields);
    }
}
