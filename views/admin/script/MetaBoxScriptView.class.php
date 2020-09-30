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

use BIWS\CPTBuilder\models\fields\IField;
use BIWS\CPTBuilder\models\MetaBox;
use BIWS\CPTBuilder\views\IView;
use BIWS\CPTBuilder\views\IViewController;

/**
 * Meta box script view implementation
 *
 * Adds the meta boxes to the gutenberg editor.
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder\views\admin
 * @subpackage script
 */
class MetaBoxScriptView implements IView
{
    /**
     * @since 1.0.0
     * @access private
     *
     * @var MetaBoxScriptViewController The controller for this view.
     */
    private MetaBoxScriptViewController $controller;

    /**
     * @since 1.0.0
     *
     * @param MetaBoxScriptViewController $controller The controller for this view.
     */
    public function __construct(MetaBoxScriptViewController $controller)
    {
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
     * @see MetaBoxScriptViewController::isValidScriptLocation()
     * @see MetaBoxScriptView::render()
     */
    public function renderScript(): void
    {
        if (!$this->controller->isValidScriptLocation()) {
            return;
        }
        $this->render();
    }

    /**
     * Calls the by the controller provided script wrapper object and calls the
     * renderer for each available meta box in the cpt provided to the
     * controller.
     * 
     * @since 1.0.0
     * 
     * @see MetaBoxScriptViewController::getMetaBoxScript()
     * @see MetaBoxScriptViewController::getMetaBoxes()
     * @see MetaBoxScriptView::renderMetaBox()
     */
    public function render(): void
    {
        ob_start();
        $render_object = $this->controller->getMetaBoxScript();
        $render_object->render(function () {
            foreach ($this->controller->getMetaBoxes() as $meta_box) {
                $this->renderMetaBox($meta_box);
            }
        });
        ob_end_flush();
    }

    /**
     * Renders the by the controller provided metabox script wrapper and calls
     * the renderer for each field of the meta box.
     *
     * @since 1.0.0
     * @access private
     * 
     * @see MetaBoxScriptViewController::getMetaBoxScriptPart()
     * @see MetaBoxScriptView::renderField()
     *
     * @param MetaBox $meta_box The meta box to be rendered.
     */
    private function renderMetaBox(MetaBox $meta_box): void
    {
        $render_object = $this->controller->getMetaBoxScriptPart($meta_box);
        $render_object->render(function () use ($meta_box) {
            foreach ($meta_box->getFields() as $field) {
                $this->renderField($field);
            }
        });
    }

    /**
     * Renders the by the controller provided field render object
     *
     * @since 1.0.0
     * @access private
     * 
     * @see MetaBoxScriptViewController::getFieldScriptPart()
     *
     * @param IField $field The field to be rendered.
     */
    private function renderField(IField $field): void
    {
        $render_object = $this->controller->getFieldScriptPart($field);
        $render_object->render();
    }
}
