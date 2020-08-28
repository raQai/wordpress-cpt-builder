<?php

namespace BIWS\EventManager\fields\metabox;

defined('ABSPATH') or die('Nope!');

abstract class AbstractMetaBoxField implements MetaBoxFieldInterface
{
    private $type;

    private $id;

    private $label;

    function getId()
    {
        return $this->id;
    }

    function getLabel()
    {
        return $this->label;
    }

    function __construct($type, $id, $label)
    {
        $this->type = $type;
        $this->id = $id;
        $this->label = $label;
    }

    function init($post_slug)
    {
        register_post_meta($post_slug, $this->id, array(
            'show_in_rest' => true,
            'single' => true,
            'type' => $this->type,
        ));
    }
}
