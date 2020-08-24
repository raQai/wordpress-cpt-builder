<?php

namespace BIWS\EventManager\fields\metabox;

defined('ABSPATH') or die('Nope!');

class TextField extends AbstractMetaBoxField
{
    private $placeholder;

    public function __construct($id, $label, $default, $placeholder)
    {
        parent::__construct($id, $label, $default);
        $this->placeholder = $placeholder;
    }

    function sanitizePostData($post_data) {
        return sanitize_text_field(parent::sanitizePostData($post_data));
    }

    function buildRenderObject($post) {
        $render_object = parent::buildRenderObject($post);
        $render_object->input_attributes["type"] = "text";
        $render_object->input_attributes["size"] = 40;
        $render_object->input_attributes["placeholder"] = $this->placeholder;
        return $render_object;
    }
}
