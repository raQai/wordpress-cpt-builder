<?php

namespace BIWS\EventManager\fields\metabox;

defined('ABSPATH') or die('Nope!');

class DateField extends AbstractMetaBoxField
{
    function sanitizePostData($post_data) {
        return sanitize_text_field(parent::sanitizePostData($post_data));
    }

    function buildRenderObject($post) {
        $render_object = parent::buildRenderObject($post);
        $render_object->input_attributes["type"] = "date";
        if (!$this->getValue($post->ID)) {
            $render_object->input_attributes["value"] = date('Y-m-d', time());
        }
        $render_object->input_attributes["pattern"] = "\d{4}-\d{2}-\d{2}";
        $render_object->input_attributes["placeholder"] = "YYYY-mm-dd";
        return $render_object;
    }
}
