<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\models\fields;

/**
 * Implementation of a placeholder field
 *
 * Exnteds the SimpleField implementation by adding a placeholder.
 *
 * @since      1.0.0
 *
 * @see SimpleField
 *
 * @package    BIWS\CPTBuilder\models
 * @subpackage fields
 */
class PlaceholderField extends SimpleField
{
    /**
     * @since 1.0.0
     * @access private
     *
     * @var string $placeholder The placeholder e.g. to include it in an input field.
     */
    private string $placeholder;

    /**
     * @since 1.0.0
     *
     * @see FieldType
     *
     * @param int    $type        The field type of this field as defined by
     *                            FieldType.
     * @param string $id          The unique id for this field.
     * @param string $label       The fields label to be displayed in the front
     *                            end.
     * @param string $placeholder The placeholder for this field.
     * @param bool   $required    Defines whether or not this field is required.
     */
    public function __construct(
        int $type,
        string $id,
        string $label,
        string $placeholder,
        bool $required = false
    ) {
        parent::__construct($type, $id, $label, $required);
        $this->placeholder = $placeholder;
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
