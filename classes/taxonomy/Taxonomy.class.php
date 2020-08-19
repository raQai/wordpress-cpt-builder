<?php

namespace BIWS\EventManager\taxonomy;

defined('ABSPATH') or die('Nope!');

class Taxonomy
{
    private $slug;

    private $args;

    public function __construct($slug, $args)
    {
        $this->slug = $slug;
        $this->args = $args;
    }

    public function init($post_slug)
    {
        add_action('init', function () use ($post_slug) {
            $this->register($post_slug);
        });
    }

    public function register($post_slug)
    {
        register_taxonomy($this->slug, $post_slug, $this->args);
    }
}
