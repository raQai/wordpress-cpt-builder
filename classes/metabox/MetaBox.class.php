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

    public function initForPostType($postSlug)
    {
        add_action('add_meta_boxes', function () use ($postSlug) {
            $this->register($postSlug);
        });
    }

    public function register($postSlug)
    {
        add_meta_box(
            $this->id,
            $this->title,
            array($this, 'render'),
            $postSlug,
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
