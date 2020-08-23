<?php

namespace BIWS\EventManager\fields\metabox;

use BIWS\EventManager\fields\FieldInterface;

defined('ABSPATH') or die('Nope!');

interface MetaBoxFieldInterface extends FieldInterface
{
    /**
     * Retrieves a post meta field for the given post ID.
     * 
     * @param int $post_id
     * @see https://developer.wordpress.org/reference/functions/get_post_meta/
     */
    public function getValue($post_id);

    /**
     * called by add_action 'save_post'
     * update_post_meta
     * $post_id, $field_id, $value
     */
    public function saveValue($post_id);

    /**
     * called by add_meta_box as display_callback
     * @param WP_Post $post current post object
     */
    public function renderField($post);
}
