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

use BIWS\CPTBuilder\models\fields\IField;
use BIWS\CPTBuilder\views\FieldRenderObject;

/**
 * Form field label render object implementation
 *
 * Extends the FieldRenderObject by adding attributes relevant to an html lable
 * element.
 *
 * @since      1.0.0
 *
 * @see        FieldRenderObject
 *
 * @package    BIWS\CPTBuilder\views
 * @subpackage formfields
 */
class FormFieldLabel extends FieldRenderObject
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
     */
    public function __construct(IField $field, string $template)
    {
        parent::__construct($field, $template);
        $this->attributes['for'] = $field->getId();
    }
}
