<?php

namespace BIWS\EventManager\fields\metabox;

defined('ABSPATH') or die('Nope!');

class TimeField extends AbstractMetaBoxField
{
    function sanitizePostData($post_data) {
        return sanitize_text_field(parent::sanitizePostData($post_data));
    }

    function buildRenderObject($post) {
        $render_object = parent::buildRenderObject($post);
        $render_object->input_attributes["type"] = "time";
        $render_object->input_attributes["pattern"] = "[0-9]{2}:[0-9]{2}";
        $render_object->input_attributes["placeholder"] = "HH:MM";
        return $render_object;
    }
}
