<?php

namespace BIWS\EventManager\metabox;

defined('ABSPATH') or die('Nope!');

class MetaBox
{
    private $id;
    private $title;
    private $context;
    private $priority;
    private $args;

    public function __construct($id, $title, $context, $priority, $args)
    {
        $this->id = $id;
        $this->title = $title;
        $this->context = $context;
        $this->priority = $priority;
        $this->args = $args;
    }

    public function init($post_slug)
    {
        add_action('add_meta_boxes', function () use ($post_slug) {
            $this->register($post_slug);
        });
    }

    public function register($post_slug)
    {
        add_meta_box(
            $this->id,
            $this->title,
            array($this, 'render'),
            $post_slug,
            $this->context,
            $this->priority,
            $this->args
        );
    }

    public function render()
    {
        // TODO implement with defined fields
    }
}
