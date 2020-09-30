<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 * 
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\views\formfields;

use BIWS\CPTBuilder\models\fields\FieldType;
use BIWS\CPTBuilder\models\fields\IField;
use BIWS\CPTBuilder\views\FieldRenderObject;
use InvalidArgumentException;

/**
 * Form field input render object implementation
 *
 * Extends the FieldRenderObject by adding attributes relevant to an html input
 * element.
 *
 * @since      1.0.0
 *
 * @see        FieldRenderObject
 *
 * @package    BIWS\CPTBuilder\views
 * @subpackage formfields
 */
class FormFieldInput extends FieldRenderObject
{
    /**
     * @since 1.0.0
     *
     * @var string[] $attributes {
     *     @type string $key   The html element property.
     *     @type string $value The value of the given proprety $key.
     * } The attributes relevant to render form labels.
     */
    public array $attributes;

    /**
     * @since 1.0.0
     *
     * @param IField $field    The field to be referenced.
     * @param string $template The path to the template to use.
     * @param string $value    The value to be displayed for the input.
     */
    public function __construct(
        IField $field,
        string $template,
        string $value = null
    ) {
        parent::__construct($field, $template);
        $this->attributes["name"] = $field->getId();
        $this->attributes["id"] = $field->getId();

        if ($value !== null) {
            $this->attributes["value"] = $value;
        }

        if ($field->isRequired()) {
            $this->attributes["aria-required"] = "true";
        }

        if (!empty($this->getPlaceholder())) {
            $this->attributes["placeholder"] = $this->getPlaceholder();
        }

        $fieldType = $field->getType();
        switch ($fieldType) {
            case FieldType::NUMBER:
                $this->attributes["type"] = "number";
                break;
            case FieldType::TEXT:
                $this->attributes["type"] = "text";
                break;
            case FieldType::DATE:
                $this->attributes["type"] = "date";
                break;
            case FieldType::TIME:
                $this->attributes["type"] = "time";
                break;
            case FieldType::EMAIL:
                $this->attributes["type"] = "email";
                break;
            case FieldType::COLOR:
                $this->attributes["type"] = "color";
                $this->attributes["style"] = "width:4rem;height:3rem";
                break;
            case FieldType::IMAGE:
                $this->attributes["type"] = "hidden";
                break;
            case FieldType::CHECKBOX:
                $this->attributes["type"] = "checkbox";
                $this->attributes["value"] = "1";
                if ($value) {
                    $this->attributes["checked"] = "";
                }
                break;
            default:
                throw new InvalidArgumentException(
                    "Field type {$fieldType} not supported."
                );
        }
    }
}
