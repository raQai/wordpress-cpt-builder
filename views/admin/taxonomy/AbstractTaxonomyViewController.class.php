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

use BIWS\CPTBuilder\models\fields\FieldType;
use BIWS\CPTBuilder\models\fields\IField;
use BIWS\CPTBuilder\models\Taxonomy;
use BIWS\CPTBuilder\services\TemplateService;
use BIWS\CPTBuilder\views\admin\taxonomy\formfields\TaxonomyFormFieldContainer;
use BIWS\CPTBuilder\views\formfields\FormFieldInput;
use BIWS\CPTBuilder\views\formfields\FormFieldLabel;
use BIWS\CPTBuilder\views\IViewController;

/**
 * Abstract taxonomy view controller
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder\views\admin
 * @subpackage taxonomy
 *
 * @abstract
 */
abstract class AbstractTaxonomyViewController implements IViewController
{
    /**
     * @since 1.0.0
     * @access private
     *
     * @var Taxonomy $taxonomy The taxonomy used for this view
     */
    private Taxonomy $taxonomy;

    /**
     * @since 1.0.0
     * @access protected
     *
     * @see TemplateService
     *
     * @var TemplateService $template_service Used to obtain the templates.
     */
    protected TemplateService $template_service;

    /**
     * @since 1.0.0
     * 
     * @param Taxonomy $taxonomy The taxonomy this view is meant for.
     */
    public function __construct(Taxonomy $taxonomy)
    {
        $this->taxonomy = $taxonomy;
        $this->template_service = TemplateService::getInstance();
    }

    /**
     * @since 1.0.0
     *
     * @return string The taxonomies slug used for this view
     */
    public function getTaxonomySlug(): string
    {
        return $this->taxonomy->getId();
    }

    /**
     * @since 1.0.0
     *
     * @return IField[] The taxonomies fields used for this view
     */
    public function getFields(): array
    {
        return $this->taxonomy->getFields();
    }

    /**
     * Sanitizes the value associated with the given $field.
     *
     * @since 1.0.0
     * @access private
     *
     * @see filter_var()
     * @see sanitize_hex_color()
     * @see sanitize_email()
     * @see sanitize_text_field()
     *
     * @param IField $field the field this value is referenced to.
     * @param mixed  $value The value for this $field.
     *
     * @return mixed sanitized $value
     */
    private function sanitizeValue(IField $field, $value)
    {
        switch ($field->getType()) {
            case FieldType::COLOR:
                $value = sanitize_hex_color($value);
                break;
            case FieldType::CHECKBOX:
            case FieldType::NUMBER:
            case FieldType::IMAGE:
                $value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
                break;
            case FieldType::EMAIL:
                $value = sanitize_email($value);
                break;
            default:
                // fixme probably not working for check boxes
                $value = sanitize_text_field($value);
        }
        return $value;
    }

    /**
     * Saves the term metadata for the given $field or deletes it if it was not
     * set in $_POST
     *
     * @since 1.0.0
     * @access private
     *
     * @see update_term_meta()
     * @see delete_term_meta()
     * @global array $_POST
     *
     * @param IField $field   The field to save the value for.
     * @param int    $term_id The term id for which this metadata will be saved.
     */
    private function saveValue(IField $field, int $term_id): void
    {
        $field_id = $field->getId();
        if (array_key_exists($field_id, $_POST)) {
            $value = $_POST[$field_id];
            if (isset($value)) {
                update_term_meta(
                    $term_id,
                    $field_id,
                    $this->sanitizeValue($field, $value)
                );
                return;
            }
        }

        delete_term_meta($term_id, $field_id);
    }

    /**
     * Saves all term metadata for the given $term_id by using the stored
     * taxonomies' fields.
     *
     * @since 1.0.0
     *
     * @see AbstractTaxonomyViewController::saveValue()
     *
     * @param int $term_id The term id for which the metadata will be saved.
     */
    public function saveValues(int $term_id): void
    {
        foreach ($this->getFields() as $field) {
            $this->saveValue($field, $term_id);
        }
    }

    /**
     * Abstract function to obtain the render object wrapping around the forms
     * input and label elements.
     *
     * @since 1.0.0
     * @abstract
     * 
     * @param IField $field The field for which to obtain the render object.
     *
     * @return TaxonomyFormFieldContainer The render object to be returned.
     */
    public abstract function getContainerRenderObject(
        IField $field
    ): TaxonomyFormFieldContainer;

    /**
     * Abstract function to obtain the render object for forms label.
     *
     * @since 1.0.0
     * @abstract
     *
     * @param IField $field The field for which to obtain the render object.
     *
     * @return FormFieldLabel The render object to be returned.
     */
    public abstract function getLabelRenderObject(
        IField $field
    ): FormFieldLabel;

    /**
     * Abstract function to obtain the render object for forms input.
     *
     * @since 1.0.0
     * @abstract
     *
     * @param IField $field   The field for which to obtain the render object.
     * @param int    $term_id The term id for which to obtain the value for this
     *                        input.
     *
     * @return FormFieldInput The render object to be returned.
     */
    public abstract function getInputRenderObject(
        IField $field,
        int $term_id
    ): FormFieldInput;
}
