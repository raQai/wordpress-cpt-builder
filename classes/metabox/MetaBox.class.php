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

    public function init($post_slug)
    {
        foreach ($this->fields as $field) {
            $field->init($post_slug);
        }
    }

    public function renderForScript() {
        $string = ".addMetabox('{$this->id}', '{$this->title}')";
        foreach ($this->fields as $field) {
            $string .= $field->renderForScript();
        }
        return $string;
    }
}
