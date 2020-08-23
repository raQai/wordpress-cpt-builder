<?php

namespace BIWS\EventManager\taxonomy\fields;

use BIWS\EventManager\Templates;

defined('ABSPATH') or die('Nope!');

abstract class AbstractTaxonomyField implements TaxonomyFieldInterface
{
    private $id;

    private $label;

    private $required;

    private $default;

    function getId()
    {
        return $this->id;
    }

    function getLabel()
    {
        return $this->label;
    }

    function isRequired()
    {
        return $this->required;
    }

    function getDefault()
    {
        return $this->default;
    }

    public function __construct($id, $label, $required, $default)
    {
        $this->id = $id;
        $this->label = $label;
        $this->required = $required;
        $this->default = $default;
    }

    function sanitizePostData($post_data)
    {
        return $post_data;
    }

    function getLabelTemplatePath()
    {
        return Templates::FORM_FIELD_LABEL;
    }

    function getInputTemplatePath()
    {
        return Templates::FORM_FIELD_INPUT;
    }

    function buildRenderObject()
    {
        $render_object = (object)(array());

        $render_object->id = $this->id;
        $render_object->label = $this->label;
        $render_object->required = $this->required;

        $container_classes = array();
        $container_classes[] = "form-field";
        $container_classes[] = "term-{$this->id}-wrap";
        if ($this->required) {
            $container_classes[] = "form-required";
        }

        $render_object->container_attributes = array();
        $render_object->container_attributes["class"] = implode(" ", $container_classes);

        $render_object->label_attributes = array();
        $render_object->label_attributes["for"] = $this->id;

        $render_object->input_attributes = array();
        $render_object->input_attributes["name"] = $this->id;
        $render_object->input_attributes["id"] = $this->id;
        $render_object->input_attributes["aria-required"] = $this->required ? "true" : "false";
        if ($this->default !== null) {
            $render_object->input_attributes["value"] = $this->getValue();
        }

        $render_object->label_template = $this->getLabelTemplatePath();
        $render_object->input_template = $this->getInputTemplatePath();

        return $render_object;
    }

    function buildEditRenderObject($term, $slug) {
        $render_object = $this->buildRenderObject();
        $render_object->input_attributes["value"] = $this->getValue($term->term_id);

        return $render_object;
    }

    public function init($slug)
    {
    }

    public function getValue($term_id = null)
    {
        if ($term_id === null) {
            return $this->default;
        }

        return get_term_meta($term_id, $this->id, true);
    }

    public function saveValue($term_id, $tt_id)
    {
        if (isset($_POST[$this->id]) && '' !== $_POST[$this->id]) {
            add_term_meta(
                $term_id,
                $this->id,
                $this->sanitizePostData($_POST[$this->id])
            );
        } else if ($this->default !== null) {
            add_term_meta(
                $term_id,
                $this->id,
                $this->sanitizePostData($this->default)
            );
        }
    }

    public function updateValue($term_id, $tt_id)
    {
        if (isset($_POST[$this->id]) && '' !== $_POST[$this->id]) {
            update_term_meta(
                $term_id,
                $this->id,
                $this->sanitizePostData($_POST[$this->id])
            );
        } else if ($this->default !== null) {
            update_term_meta(
                $term_id,
                $this->id,
                $this->sanitizePostData($this->default)
            );
        } else {
            delete_term_meta($term_id, $this->id);
        }
    }

    public function renderField($slug)
    {
        $render_object = $this->buildRenderObject();

        ob_start();
        include Templates::TAXONOMY_FORM_FIELD;
        ob_end_flush();
    }

    public function renderEditField($term, $slug)
    {
        $render_object = $this->buildEditRenderObject($term, $slug);

        ob_start();
        include Templates::TAXONOMY_EDIT_FORM_FIELD;
        ob_end_flush();
    }

    public function addTableColumn($columns)
    {
        $columns[$this->id] = $this->label;
        return $columns;
    }

    public function addTableContent($content, $column_name, $term_id)
    {
        if ($column_name === $this->id) {
            return $content . $this->getValue($term_id);
        }
        return $content;
    }
}
