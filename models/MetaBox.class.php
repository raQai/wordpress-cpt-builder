<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\models;

use BIWS\CPTBuilder\models\fields\IField;

/**
 * MetaBox Model
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder
 * @subpackage models
 */
class MetaBox
{
    /**
     * @since 1.0.0
     * @access private
     *
     * @var string The unique meta box ID used to register and query metadata
     *             for a given post.
     */
    private string $id;

    /**
     * @since 1.0.0
     * @access private
     *
     * @var string The meta box label to be displayed in the front end.
     */
    private string $label;

    /**
     * @since 1.0.0
     * @access private
     *
     * @var IField[] The fields for this meta box.
     */
    private array $fields;

    /**
     * @since 1.0.0
     *
     * @param string   $id     The unique meta box ID used to register and query
     *                         metadata for a given post.
     * @param string   $label  The meta box label to be displayed in the front
     *                         end.
     * @param IField[] $fields The fields for this meta box.
     */
    public function __construct(
        string $id,
        string $label,
        array $fields
    ) {
        $this->id = $id;
        $this->label = $label;
        $this->fields = $fields;
    }

    /**
     * @since 1.0.0
     *
     * @return string The unique meta box id.
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @since 1.0.0
     *
     * @return string The meta box label to be displayed in the front end.
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @since 1.0.0
     *
     * @return IField[] The fields for this meta box.
     */
    public function getFields(): array
    {
        return $this->fields;
    }
}
