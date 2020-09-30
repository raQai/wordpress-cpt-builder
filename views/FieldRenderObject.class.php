<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 * 
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\views;

use BIWS\CPTBuilder\models\fields\IField;
use BIWS\CPTBuilder\models\fields\PlaceholderField;

/**
 * Basic object to render field values
 *
 * Extends the RenderObject by adding the values of a given IField object.
 *
 * @since      1.0.0
 *
 * @see RenderObject
 *
 * @package    BIWS\CPTBuilder
 * @subpackage views
 */
class FieldRenderObject extends RenderObject
{
    /**
     * For Taxonomy fields the ID should be unique per taxonomy.
     * For MetaBox fields the ID should be unique per parent custom post type.
     *
     * @since 1.0.0
     * @access private
     *
     * @var string $id The unique id for this field.
     */
    private string $id;

    /**
     * @since 1.0.0
     * @access private
     *
     * @var string The fields label to be displayed in the front end.
     */
    private string $label;

    /**
     * @since 1.0.0
     * @access private
     *
     * @var bool $required Whether or not this field is required. Currently
     *                     ignored by MetaBox and does not work for IMAGE or
     *                     COLOR FieldTypes of taxonomies.
     */
    private bool $required;

    /**
     * @since 1.0.0
     * @access private
     *
     * @var string $placeholder The placeholder if available e.g. to include it
     *                          in an input field.
     */
    private string $placeholder = "";

    /**
     * @since 1.0.0
     *
     * @param IField $field    The field to be referenced.
     * @param string $template The path to the template to use.
     */
    public function __construct(IField $field, string $template)
    {
        parent::__construct($template);
        $this->id = $field->getId();
        $this->label = $field->getLabel();
        $this->required = $field->isRequired();
        if ($field instanceof PlaceholderField) {
            $this->placeholder = $field->getPlaceholder();
        }
    }

    /**
     * For Taxonomy fields the ID should be unique per taxonomy.
     * For MetaBox fields the ID should be unique per parent custom post type.
     *
     * @since 1.0.0
     *
     * @return string The unique id for this field.
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @since 1.0.0
     *
     * @return string The fields label to be displayed in the front end.
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @since 1.0.0
     *
     * @return bool Defines whether or not this field is required. Currently
     *              ignored by MetaBox and does not work for IMAGE or COLOR
     *              FieldTypes of taxonomies.
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @since 1.0.0
     *
     * @return string The placeholder e.g. to include it in an input field.
     */
    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }
}
