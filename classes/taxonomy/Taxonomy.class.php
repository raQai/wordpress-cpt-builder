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

        add_action("created_{$this->slug}", array($this, 'saveValues'), 10, 2);
        add_action("edited_{$this->slug}", array($this, 'updateValues'), 10, 2);
        add_action("{$this->slug}_add_form_fields", array($this, 'renderFields'));
        add_action("{$this->slug}_edit_form_fields", array($this, 'renderEditFields'), 10, 2);
        add_filter("manage_edit-{$this->slug}_columns", array($this, 'addTableColumns'));
        add_filter("manage_{$this->slug}_custom_column", array($this, 'addTableContents'), 10, 3);

        foreach ($this->fields as $field) {
            $field->init($this->slug);
        }
    }

    public function register($post_slug)
    {
        register_taxonomy($this->slug, $post_slug, $this->args);
    }

    public function saveValues($term_id, $tt_id)
    {
        foreach ($this->fields as $field) {
            $field->saveValue($term_id, $tt_id);
        }
    }

    public function updateValues($term_id, $tt_id)
    {
        foreach ($this->fields as $field) {
            $field->updateValues($term_id, $tt_id);
        }
    }

    public function renderFields($slug)
    {
        foreach ($this->fields as $field) {
            $field->renderField($slug);
        }
    }

    public function renderEditFields($term, $slug)
    {
        foreach ($this->fields as $field) {
            $field->renderEditField($term, $slug);
        }
    }

    public function addTableColumns($columns)
    {
        foreach ($this->fields as $field) {
            $columns = $field->addTableColumn($columns);
        }
        return $columns;
    }

    public function addTableContents($content, $column_name, $term_id)
    {
        foreach ($this->fields as $field) {
            $content = $field->addTableContent($content, $column_name, $term_id);
        }
        return $content;
    }
}
