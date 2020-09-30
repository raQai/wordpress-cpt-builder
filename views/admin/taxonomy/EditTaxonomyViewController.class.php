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
use BIWS\CPTBuilder\services\TemplateType;
use BIWS\CPTBuilder\views\admin\taxonomy\formfields\TaxonomyFormFieldContainer;
use BIWS\CPTBuilder\views\admin\taxonomy\formfields\TaxonomyImageFormFieldInput;
use BIWS\CPTBuilder\views\formfields\FormFieldInput;
use BIWS\CPTBuilder\views\formfields\FormFieldLabel;

/**
 * Edit Taxonomy Form view controller implementation
 * 
 * Extends the abstract taxonomy view controller for the edit taxonomy form.
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder\views\admin
 * @subpackage taxonomy
 */
class EditTaxonomyViewController extends AbstractTaxonomyViewController
{
    /**
     * @since 1.0.0
     *
     * @see IViewController::init()
     * @see add_action()
     *
     * @link https://developer.wordpress.org/reference/hooks/edited_taxonomy/
     */
    public function init(): void
    {
        add_action(
            "edited_{$this->getTaxonomySlug()}",
            array($this, 'saveValues')
        );
    }

    /**
     * @since 1.0.0
     *
     * @see IViewController::remov()
     * @see remove_action()
     */
    public function remove(): void
    {
        remove_action(
            "edited_{$this->getTaxonomySlug()}",
            array($this, 'saveValues')
        );
    }

    /**
     * Gets the value for a given $field and $term_id
     *
     * @since 1.0.0
     * @access private
     *
     * @see get_term_meta()
     * 
     * @param IField $field   The field to return the value for.
     * @param int    $term_id The term id for which this metadata will returned.
     *
     * @return mixed The term metadata value for the given $field and $term_id
     *
     * @link https://developer.wordpress.org/reference/functions/get_term_meta/
     */
    private function getValue(IField $field, int $term_id)
    {
        return get_term_meta($term_id, $field->getId(), true);
    }

    /**
     * @since 1.0.0
     *
     * @see AbstractTaxonomyViewController::getContainerRenderObject()
     */
    public function getContainerRenderObject(
        IField $field
    ): TaxonomyFormFieldContainer {
        return new TaxonomyFormFieldContainer(
            $field,
            $this->template_service->getTemplatePath(
                TemplateType::TAXONOMY,
                "start_edit_form_field",
                $this->getTaxonomySlug()
            ),
            $this->template_service->getTemplatePath(
                TemplateType::TAXONOMY,
                "end_edit_form_field",
                $this->getTaxonomySlug()
            )
        );
    }

    /**
     * @since 1.0.0
     *
     * @see AbstractTaxonomyViewController::getLabelRenderObject()
     */
    public function getLabelRenderObject(IField $field): FormFieldLabel
    {
        return new FormFieldLabel(
            $field,
            $this->template_service->getTemplatePath(
                TemplateType::TAXONOMY,
                "form_field_label",
                $this->getTaxonomySlug()
            )
        );
    }

    /**
     * @since 1.0.0
     *
     * @see AbstractTaxonomyViewController::getInputRenderObject()
     *
     * @return FormFieldInput|TaxonomyImageFormFieldInput Input render object
     *                                                    based on the field type.
     */
    public function getInputRenderObject(
        IField $field,
        int $term_id
    ): FormFieldInput {
        $value = $this->getValue($field, $term_id);
        if ($field->getType() === FieldType::IMAGE) {
            return new TaxonomyImageFormFieldInput(
                $field,
                $this->template_service->getTemplatePath(
                    TemplateType::TAXONOMY,
                    "form_field_input_image",
                    $this->getTaxonomySlug(),
                ),
                $value ? $value : null
            );
        }

        return new FormFieldInput(
            $field,
            $this->template_service->getTemplatePath(
                TemplateType::TAXONOMY,
                "form_field_input",
                $this->getTaxonomySlug()
            ),
            $value
        );
    }
}
