<?php

namespace BIWS\EventManager\cpt;

defined('ABSPATH') or die('Nope!');

class CustomPostTypeBuilder
{
    private $slug;

    private $args;

    private $taxonomies = [];

    private $meta_boxes = [];

    private $child_cpts = [];

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

    public function addMetaBox($meta_box)
    {
        $this->meta_boxes[] = $meta_box;
        return $this;
    }

    public function addCPT($cpt)
    {
        $this->child_cpts[] = $cpt;
        return $this;
    }

    public function buildAndInit()
    {
        $cpt = new CustomPostType(
            $this->slug,
            $this->args,
            $this->taxonomies,
            $this->meta_boxes,
            $this->child_cpts,
        );
        $cpt->init();
        return $cpt;
    }
}
