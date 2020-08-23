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

        add_action("created_{$this->slug}", array($this, 'saveValues'));
        add_action("edited_{$this->slug}", array($this, 'saveValues'));
        add_action("{$this->slug}_add_form_fields", array($this, 'renderFields'));
        add_action("{$this->slug}_edit_form_fields", array($this, 'renderEditFields'), 10, 2);
        add_filter("manage_edit-{$this->slug}_columns", array($this, 'addTableColumns'));
        add_filter("manage_{$this->slug}_custom_column", array($this, 'addTableContents'), 10, 3);

        foreach ($this->fields as $field) {
            $field->init();
        }
    }

    public function register($post_slug)
    {
        register_taxonomy($this->slug, $post_slug, $this->args);
    }

    /**
     * Fires after a new term in a specific taxonomy is created, and after the term cache has been cleaned.
     * 
     * @param int $term_id Term ID.
     * @param int $tt_id Term taxonomy ID.
     * @see https://developer.wordpress.org/reference/hooks/created_taxonomy/
     */
    public function saveValues($term_id)
    {
        foreach ($this->fields as $field) {
            $field->saveValue($term_id);
        }
    }

    /**
     * Fires after the Add Term form fields.
     *
     * @param string $slug The taxonomy slug.
     * @see https://developer.wordpress.org/reference/hooks/taxonomy_add_form_fields/
     */
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
