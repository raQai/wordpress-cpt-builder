<?php

namespace BIWS\CPTBuilder\fields\taxonomy;

defined('ABSPATH') or die('Nope!');

class ColorField extends AbstractTaxonomyField
{
    private $default;

    public function __construct($id, $label, $show_in_columns, $default)
    {
        parent::__construct($id, $label, false, $show_in_columns);
        $this->default = $default;
    }

    function sanitizePostData($post_data) {
        return sanitize_hex_color(parent::sanitizePostData($post_data));
    }

    function buildRenderObject() {
        $render_object = parent::buildRenderObject();
        $render_object->input_attributes["type"] = "color";
        $render_object->input_attributes["style"] = "width:4rem;height:3rem";
        if ($this->default !== null) {
            $render_object->input_attributes["value"] = $this->default;
        }
        return $render_object;
    }

    public function saveValue($term_id)
    {
        if (isset($_POST[$this->getId()]) && '' !== $_POST[$this->getId()]) {
            update_term_meta(
                $term_id,
                $this->getId(),
                $this->sanitizePostData($_POST[$this->getId()])
            );
        } else {
            delete_term_meta($term_id, $this->getId());
        }
    }

    public function addTableContent($content, $column_name, $term_id)
    {
        if ($this->isShwoInColumn() && $column_name == $this->getId()) {
            $color = $this->getValue($term_id);
            return $content . '<code style="background-color:' . $color . ';color:#fff;padding:.3rem.8rem;display:inline-block;border-radius:6px;">' . $color . '</code>';
        }
        return $content;
    }
}
