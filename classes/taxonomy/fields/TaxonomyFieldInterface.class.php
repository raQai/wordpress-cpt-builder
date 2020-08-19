<?php

namespace BIWS\EventManager\taxonomy\fields;

defined('ABSPATH') or die('Nope!');

interface TaxonomyFieldInterface
{
    /**
     * Simple getter for the term metadata
     *
     * @param term_id (int) Term ID.
     */
    public function getValue($term_id);

    /**
     * Fires after a new term in a specific taxonomy is created, and after the term cache has been cleaned.
     * 
     * @param term_id (int) Term ID.
     * @param tt_id (int) Term taxonomy ID.
     * @see https://developer.wordpress.org/reference/hooks/created_taxonomy/
     */
    public function saveValue($term_id, $tt_id);

    /**
     * Fires after a term for a specific taxonomy has been updated, and the term cache has been cleaned.
     *
     * @param term_id (int) Term ID.
     * @param tt_id (int) Term taxonomy ID.
     * @see https://developer.wordpress.org/reference/hooks/edited_taxonomy/
     */
    public function updateValue($term_id, $tt_id);

    /**
     * Fires after the Add Term form fields.
     *
     * @param slug (string) The taxonomy slug.
     * @see https://developer.wordpress.org/reference/hooks/taxonomy_add_form_fields/
     */
    public function renderFormField($slug);

    /**
     * Fires after the Edit Term form fields are displayed.
     *
     * @param term (WP_Term) Current taxonomy term object.
     * @param slug (string) Current taxonomy slug.
     * @see https://developer.wordpress.org/reference/hooks/taxonomy_edit_form_fields/
     */
    public function renderEditFormField($term, $slug);

    /**
     * Filters the column headers for a list table on a specific screen.
     *
     * @param columns (string[]) The column header labels keyed by column ID.
     * @see https://developer.wordpress.org/reference/hooks/manage_screen-id_columns/
     */
    public function addTableColumn($columns);

    /**
     * Filters the displayed columns in the terms list table.
     * 
     * @param content (string) Blank string.
     * @param column_name (string) Name of the column.
     * @param term_id (int) Term ID. 
     * @see https://developer.wordpress.org/reference/hooks/manage_this-screen-taxonomy_custom_column/
     */
    public function addTableContent($content, $column_name, $term_id);
}
