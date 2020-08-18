<?php

namespace BIWS\EventManager\cpt;

defined('ABSPATH') or die('Nope!');

class CustomPostType
{
    private $slug;

    private $args;

    private $taxonomies;

    public function __construct($slug, $args, $taxonomies)
    {
        $this->slug = $slug;
        $this->args = $args;
        $this->taxonomies = $taxonomies;
    }

    public function init()
    {
        add_action('init', [$this, 'register']);
        foreach ($this->taxonomies as $taxonomy) {
            $taxonomy->initForPostType($this->slug);
        }
    }

    public function register()
    {
        register_post_type($this->slug, $this->args);
    }
}
