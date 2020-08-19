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

    public function addField($type, $id, $label, $required = false, $placeholder = '')
    {
        switch ($type) {
            case fields\FieldType::NUMBER:
                $this->fields[] = new fields\NumberField(
                    $id,
                    $label,
                    $required
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
