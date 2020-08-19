<?php

namespace BIWS\EventManager\cpt;

defined('ABSPATH') or die('Nope!');

class CustomPostType
{
    private $slug;

    private $args;

    private $taxonomies;

    private $metaBoxes;

    public function __construct($slug, $args, $taxonomies, $metaBoxes)
    {
        $this->slug = $slug;
        $this->args = $args;
        $this->taxonomies = $taxonomies;
        $this->metaBoxes = $metaBoxes;
    }

    public function init()
    {
        add_action('init', [$this, 'register']);
        foreach ($this->taxonomies as $taxonomy) {
            $taxonomy->initForPostType($this->slug);
        }
        foreach ($this->metaBoxes as $metaBox) {
            $metaBox->initForPostType($this->slug);
        }
    }

    public function register()
    {
        register_post_type($this->slug, $this->args);
    }
}
