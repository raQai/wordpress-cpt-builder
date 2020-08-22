<?php

namespace BIWS\EventManager\taxonomy\fields;

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
        return BIWS_EventManager__PLUGIN_DIR_PATH . 'includes/templates/fields/FormFieldLabel.inc.php';
    }

    function getInputTemplatePath()
    {
        return BIWS_EventManager__PLUGIN_DIR_PATH . 'includes/templates/fields/FormFieldInput.inc.php';
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
        add_action("{$slug}_add_form_fields", array($this, 'renderFormField'));
        add_action("created_{$slug}", array($this, 'saveValue'), 10, 2);
        add_action("{$slug}_edit_form_fields", array($this, 'renderEditFormField'), 10, 2);
        add_action("edited_{$slug}", array($this, 'updateValue'), 10, 2);
        add_filter("manage_edit-{$slug}_columns", array($this, 'addTableColumn'));
        add_filter("manage_{$slug}_custom_column", array($this, 'addTableContent'), 10, 3);
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

    public function renderFormField($slug)
    {
        $render_object = $this->buildRenderObject();

        ob_start();
        include BIWS_EventManager__PLUGIN_DIR_PATH . 'includes/templates/fields/taxonomy/TaxonomyFormField.inc.php';
        ob_end_flush();
    }

    public function renderEditFormField($term, $slug)
    {
        $render_object = $this->buildEditRenderObject($term, $slug);

        ob_start();
        include BIWS_EventManager__PLUGIN_DIR_PATH . 'includes/templates/fields/taxonomy/TaxonomyEditFormField.inc.php';
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
