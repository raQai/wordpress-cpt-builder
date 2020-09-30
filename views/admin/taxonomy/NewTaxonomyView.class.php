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

use BIWS\CPTBuilder\models\fields\IField;
use BIWS\CPTBuilder\views\IView;
use BIWS\CPTBuilder\views\IViewController;

/**
 * New Taxonomy Form view implementation
 * 
 * Extends the abstract taxonomy view controller for the new taxonomy form.
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder\views\admin
 * @subpackage taxonomy
 */
class NewTaxonomyView implements IView
{
    /**
     * The controller managing this view. 
     *
     * @since 1.0.0
     * @access private
     *
     * @var NewTaxonomyViewController $controller The controller for this view.
     */
    private NewTaxonomyViewController $controller;

    /**
     * @since 1.0.0
     *
     * @param NewTaxonomyViewController $controller The controller managing
     *                                              this view.
     */
    public function __construct(NewTaxonomyViewController $controller)
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
     * @link https://developer.wordpress.org/reference/hooks/taxonomy_add_form_fields/
     */
    public function init(): void
    {
        add_action(
            "{$this->controller->getTaxonomySlug()}_add_form_fields",
            array($this, 'renderFormFields')
        );
    }

    /**
     * @since 1.0.0
     *
     * @see IView::remove()
     * @see remove_action()
     */
    public function remove(): void
    {
        remove_action(
            "{$this->controller->getTaxonomySlug()}_add_form_fields",
            array($this, 'renderFormFields')
        );
    }

    /**
     * Renders all form fields for the taxonomy
     *
     * @since 1.0.0
     *
     * @param $taxonomy_slug By the wordpress hook obtained taxonomy slug.
     *                       Ignored because we can use the Taxonomy provided
     *                       by the controller.
     */
    public function renderFormFields(string $taxonomy_slug): void
    {
        ob_start();
        foreach ($this->controller->getFields() as $field) {
            $this->renderFormField($field);
        }
        ob_end_flush();
    }

    /**
     * Renders the form field for the given $field.
     *
     * @since 1.0.0
     *
     * @param IField $field The field to be rendered.
     */
    public function renderFormField(IField $field): void
    {
        $render_object = $this->controller->getContainerRenderObject($field);
        $render_object->render(function () use ($field) {
            $this->renderLabel($field);
            $this->renderInput($field);
        });
    }

    /**
     * Renders the label element for the given $field.
     *
     * @since 1.0.0
     *
     * @param IField $field The field to be rendered.
     */
    public function renderLabel(IField $field)
    {
        $this->controller->getLabelRenderObject($field)->render();
    }

    /**
     * Renders the input element for the given $field.
     *
     * @since 1.0.0
     *
     * @param IField $field The field to be rendered.
     */
    public function renderInput(IField $field)
    {
        // passing -1 as term_id because it is omitted anyways
        $this->controller->getInputRenderObject($field, -1)->render();
    }
}
