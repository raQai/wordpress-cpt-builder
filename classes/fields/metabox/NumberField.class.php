<?php

namespace BIWS\EventManager\fields\metabox;

defined('ABSPATH') or die('Nope!');

class NumberField extends AbstractMetaBoxField
{
    function sanitizePostData($post_data) {
        return filter_var(
            parent::sanitizePostData($post_data),
            FILTER_SANITIZE_NUMBER_INT
        );
    }

    function buildRenderObject($post) {
        $render_object = parent::buildRenderObject($post);
        $render_object->input_attributes["type"] = "number";
        return $render_object;
    }
}
