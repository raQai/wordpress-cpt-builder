<?php

namespace BIWS\EventManager\metabox;

use BIWS\EventManager\Templates;

defined('ABSPATH') or die('Nope!');

class MetaBox
{
    private $id;
    private $title;
    private $context;
    private $priority;
    private $args;
    private $fields;

    public function __construct($id, $title, $context, $priority, $args, $fields)
    {
        $this->id = $id;
        $this->title = $title;
        $this->context = $context;
        $this->priority = $priority;
        $this->args = $args;
        $this->fields = $fields;
    }

    public function init($post_slug)
    {
        add_action('add_meta_boxes', function () use ($post_slug) {
            $this->register($post_slug);
        });
        add_action('save_post', array($this, 'saveValues'));
    }

    public function register($post_slug)
    {
        add_meta_box(
            $this->id,
            $this->title,
            array($this, 'renderFields'),
            $post_slug,
            $this->context,
            $this->priority,
            $this->args
        );
    }

    public function saveValues($post_id)
    {
        foreach ($this->fields as $field) {
            $field->saveValue($post_id);
        }
    }

    public function renderFields($post)
    {
        ob_start();
        include Templates::META_BOX_FIELD_WRAPPER_START;
        foreach ($this->fields as $field) {
            $field->renderField($post);
        }
        include Templates::META_BOX_FIELD_WRAPPER_END;
        ob_end_flush();
    }
}
