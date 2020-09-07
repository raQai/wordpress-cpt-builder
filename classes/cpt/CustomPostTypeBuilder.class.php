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

    private $unset_culumns = [];

    private $rest_props;

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

    public function unsetColumns()
    {
        $this->unset_culumns = func_get_args();
        return $this;
    }

    public function setRestRoute(
        $namespace,
        $route,
        $params,
        $sort_callback = null,
        $permission_callback = '__return_true'
    ) {
        $this->rest_props['namespace'] = $namespace;
        $this->rest_props['route'] = $route;
        $this->rest_props['params'] = $params;
        $this->rest_props['sort_callback'] = $sort_callback;
        $this->rest_props['permission_callback'] = $permission_callback;
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
            $this->unset_culumns,
            $this->rest_props,
        );
        $cpt->init();
        return $cpt;
    }
}
