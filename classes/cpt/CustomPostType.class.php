<?php

namespace BIWS\EventManager\cpt;

defined('ABSPATH') or die('Nope!');

class CustomPostType
{
    private $slug;

    private $args;

    public function __construct($slug, $args)
    {
        $this->slug = $slug;
        $this->args = $args;
    }

    public function init()
    {
        \add_action('init', [$this, 'register']);
    }

    public function register()
    {
        \register_post_type($this->slug, $this->args);
    }
}
