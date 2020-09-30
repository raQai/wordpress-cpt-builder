<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\views\admin;

use BIWS\CPTBuilder\views\IView;
use BIWS\CPTBuilder\views\IViewController;

/**
 * Abstract column view implementation
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder\views
 * @subpackage admin
 * 
 * @abstract
 */
abstract class AbstractColumnView implements IView
{
    /**
     * The controller managing this view. 
     *
     * @since 1.0.0
     * @access private
     *
     * @var AbstractColumnViewController $controller The controller for this view.
     */
    private AbstractColumnViewController $controller;

    /**
     * @since 1.0.0
     *
     * @param AbstractColumnViewController $controller The controller managing
     *                                                 this view.
     */
    public function __construct(AbstractColumnViewController $controller)
    {
        $this->controller = $controller;
    }

    /**
     * Gets the controller managing this view. 
     *
     * @since 1.0.0
     *
     * @return IViewController The controller for this view.
     */
    public function getController(): IViewController
    {
        return $this->controller;
    }

    /**
     * Filters the columns displayed in the Posts list table for thein the
     * controller registered columns to be added and to be removed.
     *
     * @since 1.0.0
     *
     * @param string[] $columns An associative array of column headings
     *
     * @link https://developer.wordpress.org/reference/hooks/manage_post_type_posts_columns/
     * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_edit-post_type_columns
     */
    public function setTableColumns(array $columns): array
    {
        // unset removed columns
        foreach ($this->controller->getRemovedColumns() as $column) {
            unset($columns[$column]);
        }

        // add new columns
        foreach ($this->controller->getAddedColumns() as $field) {
            $columns[$field->getId()] = $field->getLabel();
        }

        return $columns;
    }

    /**
     * Gets the metadata value for the column to display
     * 
     * Checks the fields of the added collumns to query the metadata.
     * 
     * @since 1.0.0
     *
     * @param string $blank_string Blank string to be used if empty.
     * @param string $column_name  The column name to add content to.
     * @param int    $id           May be $post_id or $term_id
     */
    public function getTableContent(
        string $blank_string,
        string $column_name,
        int $id
    ): string {
        $added_columns = $this->controller->getAddedColumns();
        if (!array_key_exists($column_name, $added_columns)) {
            return $blank_string;
        }

        $field = $added_columns[$column_name];
        $val = $this->controller->getFieldValue($field, $id);
        return $val !== null && $val !== '' ? $val : $blank_string;
    }
}
