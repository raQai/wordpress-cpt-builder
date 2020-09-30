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
 * Implementation of a simple field
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder\models
 * @subpackage fields
 */
class SimpleField implements IField
{
    /**
     * @since 1.0.0
     * @access private
     *
     * @var int $type The field type of this field as defined by FieldType.
     */
    private int $type;

    /**
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
     * @var string $label The fields label to be displayed in the front end.
     */
    private string $label;

    /**
     * @since 1.0.0
     * @access private
     *
     * @var bool $required Indicates whether or not this field is required.
     */
    private bool $required;

    /**
     * @since 1.0.0
     * 
     * @see FieldType
     *
     * @param int    $type     The field type of this field as defined by
     *                         FieldType.
     * @param string $id       The unique id for this field.
     * @param string $label    The fields label to be displayed in the front end.
     * @param bool   $required Defines whether or not this field is required.
     */
    public function __construct(
        int $type,
        string $id,
        string $label,
        bool $required = false
    ) {
        $this->type = $type;
        $this->id = $id;
        $this->label = $label;
        $this->required = $required;
    }

    /**
     * @since 1.0.0
     *
     * @see FieldTyp
     *
     * @return int $type The field type of this field as defined by FieldType
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * For Taxonomy fields the ID should be unique per taxonomy.
     * For MetaBox fields the ID should be unique per parent custom post type
     *
     * @since 1.0.0
     *
     * @return string The unique id for this field
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @since 1.0.0
     *
     * @return string The fields label to be displayed in the front end
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @since 1.0.0
     *
     * @return bool Whether or not this field is required. Currently ignored
     *              by MetaBox and does not work for IMAGE or COLOR FieldTypes
     */
    public function isRequired(): bool
    {
        return $this->required;
    }
}
