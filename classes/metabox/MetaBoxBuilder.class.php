<?php

namespace BIWS\EventManager\metabox;

defined('ABSPATH') or die('Nope!');

class MetaBoxBuilder
{
    private $id;
    private $title;
    private $context;
    private $priority;
    private $args;

    private function __construct($id)
    {
        $this->id = $id;
    }

    public static function create($id)
    {
        return new MetaBoxBuilder($id);
    }

    public function title($title)
    {
        $this->title = $title;
        return $this;
    }

    public function context($context)
    {
        $this->context = $context;
        return $this;
    }

    public function priority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    public function args($args)
    {
        $this->args = $args;
        return $this;
    }

    public function build()
    {
        return new MetaBox(
            $this->id,
            $this->title,
            $this->context,
            $this->priority,
            $this->args
        );
    }
}
