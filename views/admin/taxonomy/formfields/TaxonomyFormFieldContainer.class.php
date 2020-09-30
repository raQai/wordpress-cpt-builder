<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 * 
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\views\admin\taxonomy\formfields;

use BIWS\CPTBuilder\models\fields\IField;
use BIWS\CPTBuilder\views\RenderObjectWrapper;

/**
 * Form field wrapper render object implementation
 *
 * Extends the RenderObjectWrapper by adding attributes relevant to an html
 * container (e.g. div) element.
 *
 * @since      1.0.0
 *
 * @see RenderObjectWrapper
 *
 * @package    BIWS\CPTBuilder\views\admin\taxonomy
 * @subpackage formfields
 */
class TaxonomyFormFieldContainer extends RenderObjectWrapper
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
     * @param IField $field          The field to be referenced.
     * @param string $start_template The template to be rendered/required at
     *                               the start of the wrapper.
     * @param string $end_template   The template to be rendered/required at
     *                               the end of the wrapper.
     */
    public function __construct(
        IField $field,
        string $template_start,
        string $template_end
    ) {
        parent::__construct($template_start, $template_end);
        $classes = ['form-field', "term-{$field->getId()}-wrap"];
        if ($field->isRequired()) {
            $classes[] = 'form-required';
        }
        $this->attributes['class'] = implode(" ", $classes);

        $this->attributes["id"] = "{$field->getId()}";
    }
}
