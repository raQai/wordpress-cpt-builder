<?php

namespace BIWS\EventManager\cpt;

defined('ABSPATH') or die('Nope!');

class CustomPostTypeBuilder
{
    private $slug;

    private $args;

    private $taxonomies = [];

    private $metaBoxes = [];

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
        $this->metaBoxes[] = $metaBox;
        return $this;
    }

    public function buildAndInit()
    {
        $cpt = new CustomPostType(
            $this->slug,
            $this->args,
            $this->taxonomies,
            $this->metaBoxes
        );
        $cpt->init();
        return $cpt;
    }
}
