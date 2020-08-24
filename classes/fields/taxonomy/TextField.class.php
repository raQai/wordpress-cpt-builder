<?php

namespace BIWS\EventManager\fields\taxonomy;

defined('ABSPATH') or die('Nope!');

class TextField extends AbstractTaxonomyField
{
    private $placeholder;

    public function __construct($id, $label, $required, $placeholder)
    {
        parent::__construct($id, $label, $required);
        $this->placeholder = $placeholder;
    }

    function sanitizePostData($post_data) {
        return sanitize_text_field(parent::sanitizePostData($post_data));
    }

    function buildRenderObject() {
        $render_object = parent::buildRenderObject();
        $render_object->input_attributes["type"] = "text";
        $render_object->input_attributes["size"] = 40;
        $render_object->input_attributes["placeholder"] = $this->placeholder;
        return $render_object;
    }
}
