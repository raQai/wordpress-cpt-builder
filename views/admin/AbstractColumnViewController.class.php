<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\views\admin;

use BIWS\CPTBuilder\models\fields\FieldType;
use BIWS\CPTBuilder\models\fields\IField;
use BIWS\CPTBuilder\views\IViewController;
use InvalidArgumentException;

/**
 * Abstract column view controller implementation
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder\views
 * @subpackage admin
 * 
 * @abstract
 */
abstract class AbstractColumnViewController implements IViewController
{
    /**
     * @since 1.0.0
     * @access private
     * 
     * @see AbstractColumnViewController::addColumn()
     *
     * @var array $added_columns {
     *     @type string $key   The referenced field ID.
     *     @type IField $value The field to be registered for the view.
     * } The columns to be added to the view.
     */
    private array $added_columns = array();

    /**
     * Initial fields added on construction
     *
     * @since 1.0.0
     * @access private
     *
     * @var array $initial_fields {
     *     @type string $key   The referenced field ID.
     *     @type IField $value The field to be registered for the view.
     * } The initial columns to be added to the view.
     */
    private array $initial_fields = array();

    /**
     * @since 1.0.0
     * @access private
     * 
     * @see AbstractColumnViewController::removeColumn()
     *
     * @var string[] $removed_columns The column names to be removed from the view.
     */
    private array $removed_columns = array();

    /**
     * @param IField[] $initial_fields The initial fields to be added to the view.
     */
    public function __construct(array $initial_fields)
    {
        foreach ($initial_fields as $field) {
            $this->initial_fields[$field->getId()] = $field;
        }
    }

    /**
     * no-op
     *
     * @since 1.0.0
     */
    public function init(): void
    {
    }

    /**
     * no-op
     *
     * @since 1.0.0
     */
    public function remove(): void
    {
    }

    /**
     * Obtains added columns for this column view
     *
     * Merges the initial fields which have not been removed together
     * with the manually added columns and returns the result.
     *
     * @since 1.0.0
     *
     * @see AbstractColumnViewController::$added_columns
     * @see AbstractColumnViewController::$initial_fields
     *
     * @return array {
     *     @type string $key   The referenced field ID.
     *     @type IField $value The field to be registered for the view.
     * }
     */
    public function getAddedColumns(): array
    {
        return array_merge($this->added_columns, $this->initial_fields);
    }

    /**
     * Obtains removed column names for this column view
     *
     * @since 1.0.0
     * 
     * @see AbstractColumnViewController::removeColumn()
     *
     * @return string[] The column names registered for removal.
     */
    public function getRemovedColumns(): array
    {
        return $this->removed_columns;
    }

    /**
     * Adds a field to the column view
     *
     * @since 1.0.0
     *
     * @param IField $field The field to be added to the columns.
     *
     * @return $this This instance for chained calls.
     */
    public function addColumn(IField $field): self
    {
        $this->added_columns[$field->getId()] = $field;
        return $this;
    }

    /**
     * Marks a column for removal
     *
     * If the column was registered with the initial fields, it will be removed
     * from those fields. Otherwise it will be additionally added to the removed
     * columns array.
     *
     * @since 1.0.0
     *
     * @param string $column_name The column name to be marked for removal.
     *
     * @return $this This instance for chained calls.
     */
    public function removeColumn(string $column_name): self
    {
        if (array_key_exists($column_name, $this->initial_fields)) {
            unset($this->initial_fields[$column_name]);
        } else {
            $this->removed_columns[] = $column_name;
        }
        return $this;
    }

    /**
     * @since 1.0.0
     * @access protected
     * @abstract
     *
     * @param IField $field The field for which to obtain the metadata for
     * @param int    $id    May be $post_id or $term_id
     * 
     * @return mixed The post meta value for the provided $field and $post_id.
     */
    protected abstract function getValue(IField $field, int $id);

    /**
     * Helps to get the field value for the front end
     *
     * @since 1.0.0
     * 
     * @see AbstractColumnViewController::getValue()
     *
     * @param IField $field The field for which to obtain the metadata for
     * @param int    $id    May be $post_id or $term_id
     * 
     * @return mixed The metadata value for the provided $field and $id.
     */
    public function getFieldValue(IField $field, int $id)
    {
        $fieldType = $field->getType();
        $value = $this->getValue($field, $id);
        switch ($fieldType) {
            case FieldType::NUMBER:
            case FieldType::TEXT:
            case FieldType::DATE:
            case FieldType::TIME:
            case FieldType::EMAIL:
                return htmlspecialchars($value);
            case FieldType::COLOR:
                return '<code style="background-color:'
                    . $value
                    . ';color:#fff;padding:.3rem.8rem;display:inline-block;border-radius:6px;">'
                    . $value
                    . '</code>';
            case FieldType::IMAGE:
                return '<img style="max-width:100%;max-height:6rem" src="'
                    . wp_get_attachment_thumb_url($value)
                    . '" loading="lazy">';
            case FieldType::CHECKBOX:
                return $value ? __("Yes") : __("No");
            default:
                throw new InvalidArgumentException(
                    "Field type {$fieldType} not supported."
                );
        }
    }
}
