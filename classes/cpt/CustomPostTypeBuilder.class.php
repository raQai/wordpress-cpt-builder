<?php

namespace BIWS\EventManager\cpt;

defined('ABSPATH') or die('Nope!');

class CustomPostTypeBuilder
{
    private $slug;

    private $args;

    private $taxonomies = [];

    private $meta_boxes = [];

    private function __construct($slug)
    {
        $this->slug = $slug;
    }

    public static function create($slug)
    {
        return new CustomPostTypeBuilder($slug);
    }

    public function args($args)
    {
        $this->args = $args;
        return $this;
    }

    public function addTaxonomy($taxonomy)
    {
        $this->taxonomies[] = $taxonomy;
        return $this;
    }

    public function addMetaBox($metaBox)
    {
        $this->meta_boxes[] = $metaBox;
        return $this;
    }

    public function buildAndInit()
    {
        $cpt = new CustomPostType(
            $this->slug,
            $this->args,
            $this->taxonomies,
            $this->meta_boxes
        );
        $cpt->init();
        return $cpt;
    }
}
