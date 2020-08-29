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

    function renderForScript();

    /**
     * Filters the columns displayed in the Posts list table for a specific post type.
     *
     * @param string[] $post_columns An associative array of column headings.
     * @see https://developer.wordpress.org/reference/hooks/manage_post_type_posts_columns/
     */
    public function addTableColumn($columns);

    /**
     * Fires for each custom column of a specific post type in the Posts list table.
     * 
     * @param string $column_name The name of the column to display.
     * @param int $post_id The current post ID.
     * @see https://developer.wordpress.org/reference/hooks/manage_post-post_type_posts_custom_column/
     */
    public function addTableContent($column_name, $post_id);
}
