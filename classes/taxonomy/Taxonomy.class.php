<?php

namespace BIWS\EventManager\taxonomy;

defined('ABSPATH') or die('Nope!');

class Taxonomy
{
    private $slug;

    private $args;

    private $fields;

    public function __construct($slug, $args, $fields)
    {
        $this->slug = $slug;
        $this->args = $args;
        $this->fields = $fields;
    }

    public function init($post_slug)
    {
        add_action('init', function () use ($post_slug) {
            $this->register($post_slug);
        });

        foreach ($this->fields as $field) {
            $field->init($this->slug);
        }
    }

    public function register($post_slug)
    {
        register_taxonomy($this->slug, $post_slug, $this->args);
    }
}
