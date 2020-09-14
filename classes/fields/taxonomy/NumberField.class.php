<?php

namespace BIWS\CPTBuilder\fields\taxonomy;

defined('ABSPATH') or die('Nope!');

class NumberField extends AbstractTaxonomyField
{
    function sanitizePostData($post_data) {
        return filter_var(
            parent::sanitizePostData($post_data),
            FILTER_SANITIZE_NUMBER_INT
        );
    }

    function buildRenderObject() {
        $render_object = parent::buildRenderObject();
        $render_object->input_attributes["type"] = "number";
        $render_object->input_attributes["value"] = 0;
        return $render_object;
    }
}
