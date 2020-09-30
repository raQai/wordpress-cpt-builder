<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\views\admin\taxonomy;

use BIWS\CPTBuilder\views\admin\AbstractColumnView;

/**
 * Taxonomy column view implementation
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder\views\admin
 * @subpackage taxonomy
 */
class TaxonomyColumnView extends AbstractColumnView
{
    /**
     * The controller managing this view. 
     *
     * @since 1.0.0
     * @access private
     *
     * @var TaxonomyColumnViewController $controller The controller for this view.
     */
    private TaxonomyColumnViewController $controller;

    /**
     * @since 1.0.0
     *
     * @param TaxonomyColumnViewController $controller The controller managing this view.
     */
    public function __construct(TaxonomyColumnViewController $controller)
    {
        parent::__construct($controller);
        $this->controller = $controller;
    }

    /**
     * Adds UI/renderiong related actions and filters for the view.
     * Actions/filters may rely on logic provided by the controller.
     *
     * @since 1.0.0
     */
    public function init(): void
    {
        add_filter(
            "manage_edit-{$this->controller->getTaxonomySlug()}_columns",
            array($this, 'setTableColumns')
        );
        add_filter(
            "manage_{$this->controller->getTaxonomySlug()}_custom_column",
            array($this, 'addTaxonomyTableContent'),
            10,
            3
        );
    }

    /**
     * Removes previously registered actions and filters for the
     * view to make sure it can be overwritten by the RenderService.
     *
     * @since 1.0.0
     * 
     * @see RenderService
     */
    public function remove(): void
    {
        remove_filter(
            "manage_edit-{$this->controller->getTaxonomySlug()}_columns",
            array($this, 'setTableColumns')
        );
        remove_filter(
            "manage_{$this->controller->getTaxonomySlug()}_custom_column",
            array($this, 'addTaxonomyTableContent'),
            10
        );
    }

    /**
     * Adds the metadata content to the column
     * 
     * @since 1.0.0
     *
     * @see  AbstractColumnView::addTableContent()
     *
     * @param string $string      Blank string to be used if empty.
     * @param string $column_name The name of the column to display
     * @param int    $term_id     Post id to query the metadata for.
     *
     * @link https://developer.wordpress.org/reference/hooks/manage_this-screen-taxonomy_custom_column/
     */
    public function addTaxonomyTableContent(
        string $string,
        string $column_name,
        int $term_id
    ): string {
        return $this->getTableContent('—', $column_name, $term_id);
    }
}
