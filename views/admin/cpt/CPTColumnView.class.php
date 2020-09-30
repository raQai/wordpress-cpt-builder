<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\views\admin\cpt;

use BIWS\CPTBuilder\views\admin\AbstractColumnView;

/**
 * Post column view implementation
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder\views\admin
 * @subpackage cpt
 */
class CPTColumnView extends AbstractColumnView
{
    /**
     * The controller managing this view. 
     *
     * @since 1.0.0
     * @access private
     *
     * @var CPTColumnViewController $controller The controller for this view.
     */
    private CPTColumnViewController $controller;

    /**
     * @since 1.0.0
     *
     * @param CPTColumnViewController $controller The controller managing this view.
     */
    public function __construct(CPTColumnViewController $controller)
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
            "manage_{$this->controller->getCPTSlug()}_posts_columns",
            array($this, 'setTableColumns')
        );
        add_filter(
            "manage_{$this->controller->getCPTSlug()}_posts_custom_column",
            array($this, 'addPostTableContent'),
            10,
            2
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
            "manage_{$this->controller->getCPTSlug()}_post_columns",
            array($this, 'setTableColumns')
        );
        remove_filter(
            "manage_{$this->controller->getCPTSlug()}_posts_custom_column",
            array($this, 'addPostTableContent'),
            10
        );
    }

    /**
     * Overwrites this method because column rendering of taxonomies and posts
     * differs and needs to be directly echoed instead of being returned.
     *
     * @since 1.0.0
     *
     * @see AbstractColumnView::addTableContent()
     *
     * @param string $blank_string Blank string to be used if empty.
     * @param string $column_name  The column name to add content to.
     * @param int    $post_id      Post id to query the metadata for.
     */
    public function getTableContent(
        string $blank_string,
        string $column_name,
        int $post_id
    ): string {
        echo parent::getTableContent($blank_string, $column_name, $post_id);
        return "";
    }

    /**
     * Adds the metadata content to the column
     * 
     * @since 1.0.0
     *
     * @see  AbstractColumnView::addTableContent()
     *
     * @param string $column_name  The name of the column to display
     * @param int    $post_id      Post id to query the metadata for.
     *
     * @link https://developer.wordpress.org/reference/hooks/manage_post-post_type_posts_custom_column/
     */
    public function addPostTableContent(
        string $column_name,
        int $post_id
    ): string {
        return $this->getTableContent('—', $column_name, $post_id);
    }
}
