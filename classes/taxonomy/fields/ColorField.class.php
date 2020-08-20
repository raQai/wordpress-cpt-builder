<?php

namespace BIWS\EventManager\taxonomy\fields;

defined('ABSPATH') or die('Nope!');

class ColorField implements TaxonomyFieldInterface
{
    private $id;

    private $label;

    private $required;

    public function __construct($id, $label, $required)
    {
        $this->id = $id;
        $this->label = $label;
        $this->required = $required;
    }

    public function getValue($term_id)
    {
        return get_term_meta($term_id, $this->id, true);
    }

    public function saveValue($term_id, $tt_id)
    {
        if (isset($_POST[$this->id]) && '' !== $_POST[$this->id]) {
            add_term_meta(
                $term_id,
                $this->id,
                sanitize_hex_color_no_hash($_POST[$this->id])
            );
        } else {
            add_term_meta($term_id, $this->id, 'a1a1b0');
        }
    }

    public function updateValue($term_id, $tt_id)
    {
        if (isset($_POST[$this->id]) && '' !== $_POST[$this->id]) {
            update_term_meta(
                $term_id,
                $this->id,
                sanitize_hex_color_no_hash($_POST[$this->id])
            );
        } else {
            delete_term_meta($term_id, $this->id);
        }
    }

    public function renderFormField($slug)
    {
        $html = '<div class="form-field term-%1$s-wrap %3$s">
                    <label for="%1$s">%2$s</label>
                    <input name="%1$s" id="%1$s" type="color" style="width:4rem;height:3rem" value="" %4$s>
                </div>';
        printf(
            $html,
            $this->id,
            $this->label,
            $this->required ? 'form-required' : '',
            $this->required ? 'aria-required="true"' : ''
        );
    }

    public function renderEditFormField($term, $slug)
    {
        $html = '<tr class="form-field term-%1$s-wrap %4$s">
                    <th scope="row">
                        <label for="%1$s">%2$s</label>
                    </th>
                    <td>
                        <input name="%1$s" id="%1$s" type="color" style="width:4rem;height:3rem" value="#%3$s" %5$s>
                    </td>
                </tr>';
        printf(
            $html,
            $this->id,
            $this->label,
            $this->getValue($term->term_id),
            $this->required ? 'form-required' : '',
            $this->required ? 'aria-required="true"' : ''
        );
    }

    public function addTableColumn($columns)
    {
        $columns[$this->id] = $this->label;
        return $columns;
    }

    public function addTableContent($content, $column_name, $term_id)
    {
        if ($column_name === $this->id) {
            $color = esc_attr($this->getValue($term_id));
            return $content . '<code style="background-color:#' . $color . ';color:#fff;padding:.3rem.8rem;display:inline-block;border-radius:6px;">#' . $color . '</code>';
        }
        return $content;
    }
}
