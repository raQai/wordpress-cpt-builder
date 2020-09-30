<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\views\admin\script;

use BIWS\CPTBuilder\views\IView;
use BIWS\CPTBuilder\views\IViewController;

/**
 * Taxonomy media uploader script view implementation
 *
 * Adds the media uploader script if necessary.
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder\views\admin
 * @subpackage script
 */
class TaxonomyMediaUploaderScriptView implements IView
{
    /**
     * @since 1.0.0
     * @access private
     *
     * @var TaxonomyMediaUploaderScriptViewController The controller for this view.
     */
    private TaxonomyMediaUploaderScriptViewController $controller;

    /**
     * @since 1.0.0
     *
     * @param TaxonomyMediaUploaderScriptViewController $controller The controller for this view.
     */
    public function __construct(
        TaxonomyMediaUploaderScriptViewController $controller
    ) {
        $this->controller = $controller;
    }

    /**
     * @since 1.0.0
     * 
     * @see IView::getController()
     */
    public function getController(): IViewController
    {
        return $this->controller;
    }

    /**
     * @since 1.0.0
     * 
     * @see IView::init()
     * @see add_action()
     * 
     * @link https://developer.wordpress.org/reference/hooks/admin_footer/
     */
    public function init(): void
    {
        add_action('admin_footer', array($this, 'renderScript'));
    }

    /**
     * @since 1.0.0
     * 
     * @see IView::remove()
     * @see remove_action()
     */
    public function remove(): void
    {
        remove_action('admin_footer', array($this, 'renderScript'));
    }

    /**
     * Calls the render function if this is called in a valid script location.
     *
     * @since 1.0.0
     *
     * @see TaxonomyMediaUploaderScriptViewController::isValidScriptLocation()
     * @see TaxonomyMediaUploaderScriptView::render()
     */
    public function renderScript(): void
    {
        if (!$this->controller->isValidScriptLocation()) {
            return;
        }

        $this->render();
    }

    /**
     * Renders the by the controller provided script object for every image
     * field of the taxonomy.
     * 
     * @since 1.0.0
     * 
     * @see TaxonomyMediaUploaderScriptViewController::getImageFields()
     * @see TaxonomyMediaUploaderScriptViewController::getMediaUploadScript()
     */
    public function render(): void
    {
        ob_start();
        foreach ($this->controller->getImageFields() as $field) {
            $render_object = $this->controller->getMediaUploadScript($field);
            $render_object->render();
        }
        ob_end_flush();
    }
}
