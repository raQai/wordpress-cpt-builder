<?php

namespace BIWS\EventManager\fields;

defined('ABSPATH') or die('Nope!');

interface FieldInterface
{
    /**
     * Initialize additional actions and filters for this field
     */
    public function init();

    /**
     * Retrieves metadata for the given obj_id
     *
     * @param int $obj_id
     */
    public function getValue($obj_id);

    /**
     * Saves and updates metadata for the given obj_id
     *
     * @param int $obj_id
     */
    public function saveValue($obj_id);

    /**
     * Display callback
     * 
     * @param * $obj type depends on implementation.
     */
    public function renderField($obj);
}
