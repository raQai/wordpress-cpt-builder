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

use BIWS\CPTBuilder\models\fields\FieldType;
use BIWS\CPTBuilder\models\fields\IField;
use BIWS\CPTBuilder\views\formfields\FormFieldInput;
use InvalidArgumentException;

/**
 * Form field image render object implementation
 *
 * Extends FormFieldInput by extending the attributes relevant to an image
 * related input render object.
 * Also adds the image html as a queryable string.
 *
 * @since      1.0.0
 *
 * @see FieldRenderObject
 *
 * @package    BIWS\CPTBuilder\views\admin\taxonomy
 * @subpackage formfields
 */
class TaxonomyImageFormFieldInput extends FormFieldInput
{
    /**
     * @since 1.0.0
     *
     * @var string $image_html The images html to be used.
     */
    public string $image_html = "";

    /**
     * @since 1.0.0
     *
     * @see  wp_get_attachment_image()
     *
     * @param IField   $field    The field to be referenced.
     * @param string   $template The path to the template to use.
     * @param int|null $image_id The image id to get the attachment for.
     * 
     * @throws InvalidArgumentException If $field is not FieldType::IMAGE
     *
     * @link https://developer.wordpress.org/reference/functions/wp_get_attachment_image/
     */
    public function __construct(
        IField $field,
        string $template,
        ?int $image_id = null
    ) {
        parent::__construct($field, $template, $image_id);

        if ($field->getType() !== FieldType::IMAGE) {
            throw new InvalidArgumentException(
                "Cannot use image input render object for non images."
            );
        }

        $this->attributes["id"] = "{$field->getId()}_media-selector";
        $this->attributes["class"] = "{$field->getId()}_media-url";

        if ($image_id !== null && $image_id !== false) {
            $this->attributes["value"] = $image_id;
            $this->image_html = wp_get_attachment_image($image_id, 'thumbnail');
        }
    }
}
