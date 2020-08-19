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
            add_action("{$this->slug}_add_form_fields", array($field, 'renderFormField'));
            add_action("created_{$this->slug}", array($field, 'saveValue'), 10, 2);
            add_action("{$this->slug}_edit_form_fields", array($field, 'renderEditFormField'), 10, 2);
            add_action("edited_{$this->slug}", array($field, 'updateValue'), 10, 2);
            add_filter("manage_edit-{$this->slug}_columns", array($field, 'addTableColumn'));
            add_filter("manage_{$this->slug}_custom_column", array($field, 'addTableContent'), 10, 3);
        }
    }

    public function register($post_slug)
    {
        register_taxonomy($this->slug, $post_slug, $this->args);
    }
}
