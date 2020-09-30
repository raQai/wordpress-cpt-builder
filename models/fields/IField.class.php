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
 * IField interface
 *
 * Can be used to extend the core functionality.
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder\models
 * @subpackage fields
 */
interface IField
{
    /**
     * @since 1.0.0
     *
     * @see FieldType
     *
     * @return int The field type of this field as defined by FieldType.
     */
    public function getType(): int;

    /**
     * For Taxonomy fields the ID should be unique per taxonomy.
     * For MetaBox fields the ID should be unique per parent custom post type.
     *
     * @since 1.0.0
     *
     * @return string The unique id for this field.
     */
    public function getId(): string;

    /**
     * @since 1.0.0
     *
     * @return string The fields label to be displayed in the front end.
     */
    public function getLabel(): string;

    /**
     * @since 1.0.0
     *
     * @return bool Defines whether or not this field is required. Currently
     *              ignored by MetaBox and does not work for IMAGE or COLOR
     *              FieldTypes of taxonomies.
     */
    public function isRequired(): bool;
}
