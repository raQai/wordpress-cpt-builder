<?php

namespace BIWS\EventManager\fields\metabox;

use BIWS\EventManager\Templates;

defined('ABSPATH') or die('Nope!');

abstract class AbstractMetaBoxField implements MetaBoxFieldInterface
{
    private $id;

    private $label;

    private $default;

    function getId()
    {
        return $this->id;
    }

    function getLabel()
    {
        return $this->label;
    }

    function getDefault()
    {
        return $this->default;
    }

    public function __construct($id, $label, $default)
    {
        $this->id = $id;
        $this->label = $label;
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

    function buildRenderObject($post)
    {
        $render_object = (object)(array());

        $render_object->id = $this->id;
        $render_object->label = $this->label;

        $render_object->container_attributes = array();
        $render_object->container_attributes["class"] = "components-base-control__field";

        $render_object->label_attributes = array();
        $render_object->label_attributes["class"] = "components-base-control__label";
        $render_object->label_attributes["for"] = $this->id;

        $render_object->input_attributes = array();
        $render_object->input_attributes["class"] = "components-text-control__input";
        $render_object->input_attributes["name"] = $this->id;
        $render_object->input_attributes["id"] = $this->id;

        $value = $this->getValue($post->ID);
        $render_object->input_attributes["value"] = $value ? $value : $this->default;

        $render_object->label_template = $this->getLabelTemplatePath();
        $render_object->input_template = $this->getInputTemplatePath();

        return $render_object;
    }

    public function init()
    {
        // no-op
    }

    public function getValue($post_id)
    {
        if ($post_id === null) {
            return $this->default;
        }

        return get_post_meta($post_id, $this->id, true);
    }

    public function saveValue($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if ($parent_id = wp_is_post_revision($post_id)) {
            $post_id = $parent_id;
        }

        if (isset($_POST[$this->id]) && '' !== $_POST[$this->id]) {
            update_post_meta(
                $post_id,
                $this->id,
                $this->sanitizePostData($_POST[$this->id])
            );
        } else if ($this->default !== null) {
            update_post_meta(
                $post_id,
                $this->id,
                $this->sanitizePostData($this->default)
            );
        } else {
            delete_post_meta($post_id, $this->id);
        }
    }

    public function renderField($post)
    {
        $render_object = $this->buildRenderObject($post);

        include Templates::TAXONOMY_FORM_FIELD;
    }
}
