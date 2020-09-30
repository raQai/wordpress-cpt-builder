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
use WP_Term;

/**
 * Edit Taxonomy Form view implementation
 * 
 * Extends the abstract taxonomy view controller for the edit taxonomy form.
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder\views\admin
 * @subpackage taxonomy
 */
class EditTaxonomyView implements IView
{
    /**
     * The controller managing this view. 
     *
     * @since 1.0.0
     * @access private
     *
     * @var EditTaxonomyViewController $controller The controller for this view.
     */
    private EditTaxonomyViewController $controller;

    /**
     * @since 1.0.0
     *
     * @param EditTaxonomyViewController $controller The controller managing
     *                                               this view.
     */
    public function __construct(EditTaxonomyViewController $controller)
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
     * @link https://developer.wordpress.org/reference/hooks/taxonomy_edit_form_fields/
     */
    public function init(): void
    {
        add_action(
            "{$this->controller->getTaxonomySlug()}_edit_form_fields",
            array($this, 'renderFormFields'),
            10,
            2
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
            "{$this->controller->getTaxonomySlug()}_edit_form_fields",
            array($this, 'renderFormFields'),
            10
        );
    }

    /**
     * Renders all form fields for the given WP_Term object
     *
     * @since 1.0.0
     *
     * @param WP_Term $tag The by the wordpress hook obtained WP_Term object.
     * @param $taxonomy_slug By the wordpress hook obtained taxonomy slug.
     */
    public function renderFormFields(WP_Term $tag, string $taxonomy_slug): void
    {
        ob_start();
        foreach ($this->controller->getFields() as $field) {
            $this->renderFormField($field, $tag);
        }
        ob_end_flush();
    }

    /**
     * Renders the form field for the given WP_Term object and $field.
     *
     * @since 1.0.0
     *
     * @param IField  $field The field to be rendered.
     * @param WP_Term $tag   The from the wordpress hook obtained WP_Term object.
     */
    public function renderFormField(IField $field, WP_Term $tag): void
    {
        $render_object = $this->controller->getContainerRenderObject($field);
        $render_object->render(function () use ($field, $tag) {
            $this->renderLabel($field);
            $this->renderInput($field, $tag);
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
        echo '<th scope="row">';
        $this->controller->getLabelRenderObject($field)->render();
        echo '</th>';
    }

    /**
     * Renders the input element for the given $field.
     *
     * @since 1.0.0
     *
     * @param IField  $field The field to be rendered.
     * @param WP_Term $tag   The term object to obtain the metadata value for.
     */
    public function renderInput(IField $field, WP_Term $tag)
    {
        echo '<td>';
        $this->controller
            ->getInputRenderObject($field, $tag->term_id)
            ->render();
        echo '</td>';
    }
}
