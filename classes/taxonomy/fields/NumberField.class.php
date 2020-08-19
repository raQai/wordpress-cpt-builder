<?php

namespace BIWS\EventManager\taxonomy\fields;

defined('ABSPATH') or die('Nope!');

class NumberField implements TaxonomyFieldInterface
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
                filter_var($_POST[$this->id], FILTER_SANITIZE_NUMBER_INT)
            );
        } else {
            add_term_meta($term_id, $this->id, 0);
        }
    }

    public function updateValue($term_id, $tt_id)
    {
        if (isset($_POST[$this->id]) && '' !== $_POST[$this->id]) {
            update_term_meta(
                $term_id,
                $this->id,
                filter_var($_POST[$this->id], FILTER_SANITIZE_NUMBER_INT)
            );
        } else {
            delete_term_meta($term_id, $this->id);
        }
    }

    public function renderFormField($slug)
    {
        $html = '<div class="form-field term-%1$s-wrap %3$s">
                    <label for="%1$s">%2$s</label>
                    <input name="%1$s" id="%1$s" type="number" value="0" %4$s>
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
                        <input name="%1$s" id="%1$s" type="number" value="%3$d" %5$s>
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
            return $content . $this->getValue($term_id);
        }
        return $content;
    }
}
