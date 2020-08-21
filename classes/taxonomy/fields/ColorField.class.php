<?php

namespace BIWS\EventManager\taxonomy\fields;

defined('ABSPATH') or die('Nope!');

class ColorField extends AbstractTaxonomyField
{
    function sanitizePostData($post_data) {
        return sanitize_hex_color(parent::sanitizePostData($post_data));
    }

    function buildRenderObject() {
        $render_object = parent::buildRenderObject();
        $render_object->input_attributes["type"] = "color";
        $render_object->input_attributes["style"] = "width:4rem;height:3rem";
        return $render_object;
    }

    public function addTableContent($content, $column_name, $term_id)
    {
        if ($column_name === $this->getId()) {
            $color = parent::getValue($term_id);
            return $content . '<code style="background-color:' . $color . ';color:#fff;padding:.3rem.8rem;display:inline-block;border-radius:6px;">' . $color . '</code>';
        }
        return $content;
    }
}
