<?php

namespace BIWS\EventManager\metabox;

use BIWS\EventManager\Templates;

defined('ABSPATH') or die('Nope!');

class MetaBox
{
    private $id;
    private $title;
    private $args;
    private $fields;

    public function __construct($id, $title, $args, $fields)
    {
        $this->id = $id;
        $this->title = $title;
        $this->args = $args;
        $this->fields = $fields;
    }

    public function getId()
    {
        return $this->id;
    }

    public function init($post_slug)
    {
        foreach ($this->fields as $field) {
            $field->init($post_slug);
        }
        add_filter("manage_{$post_slug}_posts_columns", array($this, 'addTableColumns'));
        add_filter("manage_{$post_slug}_posts_custom_column", array($this, 'addTableContents'), 10, 2);
    }

    public function renderForScript()
    {
        $string = ".addMetabox('{$this->id}', '{$this->title}')";
        foreach ($this->fields as $field) {
            $string .= $field->renderForScript();
        }
        return $string;
    }

    public function addTableColumns($columns)
    {
        foreach ($this->fields as $field) {
            $columns = $field->addTableColumn($columns);
        }
        return $columns;
    }

    public function addTableContents($column_name, $post_id)
    {
        foreach ($this->fields as $field) {
            $content = $field->addTableContent($column_name, $post_id);
        }
        return $content;
    }

    public function collectRestResponseData($post_id)
    {
        $meta_data = [];
        foreach ($this->fields as $field) {
            $value = $field->getValue($post_id);
            if ($value) {
                $meta_data[$field->getId()] = $value;
            }
        }

        return $meta_data;

        /*
        foreach ($terms as $term) {
            $term_data = array(
                'id' => $term->ID,
                'name' => $term->name,
            );

            $description = $term->description;
            if ($description) {
                $term_data['description'] = $description;

            }

            foreach ($this->fields as $field) {
                $value = $field->getValue($term->term_taxonomy_id);
                if ($value) {
                    $term_data[$field->getId()] = $value;
                }
            }

            $data[] = $term_data;
        }

        return $data;
        */
    }
}
