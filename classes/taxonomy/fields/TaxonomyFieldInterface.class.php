<?php

namespace BIWS\EventManager\taxonomy\fields;

defined('ABSPATH') or die('Nope!');

interface TaxonomyFieldInterface
{
    /**
     * Initialize all necessary actions and filters for this field.
     *
     * @param string $slug The taxonomy slug
     */
    public function init($slug);

    /**
     * Simple getter for the term metadata.
     * May return a default value if $term_id is null or false if no value was set
     *
     * @param int $term_id Term ID.
     */
    public function getValue($term_id = null);

    /**
     * Fires after a new term in a specific taxonomy is created, and after the term cache has been cleaned.
     * 
     * @param int $term_id Term ID.
     * @param int $tt_id Term taxonomy ID.
     * @see https://developer.wordpress.org/reference/hooks/created_taxonomy/
     */
    public function saveValue($term_id, $tt_id);

    /**
     * Fires after a term for a specific taxonomy has been updated, and the term cache has been cleaned.
     *
     * @param int $term_id Term ID.
     * @param int $tt_id Term taxonomy ID.
     * @see https://developer.wordpress.org/reference/hooks/edited_taxonomy/
     */
    public function updateValue($term_id, $tt_id);

    /**
     * Fires after the Add Term form fields.
     *
     * @param string $slug The taxonomy slug.
     * @see https://developer.wordpress.org/reference/hooks/taxonomy_add_form_fields/
     */
    public function renderField($slug);

    /**
     * Fires after the Edit Term form fields are displayed.
     *
     * @param WP_Term $term Current taxonomy term object.
     * @param string $slug Current taxonomy slug.
     * @see https://developer.wordpress.org/reference/hooks/taxonomy_edit_form_fields/
     */
    public function renderEditField($term, $slug);

    /**
     * Filters the column headers for a list table on a specific screen.
     *
     * @param string[] $columns The column header labels keyed by column ID.
     * @see https://developer.wordpress.org/reference/hooks/manage_screen-id_columns/
     */
    public function addTableColumn($columns);

    /**
     * Filters the displayed columns in the terms list table.
     * 
     * @param string $content Blank string.
     * @param string $column_name Name of the column.
     * @param int $term_id Term ID.
     * @see https://developer.wordpress.org/reference/hooks/manage_this-screen-taxonomy_custom_column/
     */
    public function addTableContent($content, $column_name, $term_id);
}
