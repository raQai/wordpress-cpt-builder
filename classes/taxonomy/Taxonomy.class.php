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

    public function initForPostType($postSlug)
    {
        add_action('init', function () use ($postSlug) {
            $this->register($postSlug);
        });
    }

    public function register($postSlug)
    {
        register_taxonomy($this->slug, $postSlug, $this->args);
    }
}
