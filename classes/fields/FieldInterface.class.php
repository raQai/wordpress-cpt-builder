<?php

namespace BIWS\EventManager\fields;

defined('ABSPATH') or die('Nope!');

interface FieldInterface
{
    /**
     * Initialize additional actions and filters for this field
     */
    public function init($post_slug);
}
