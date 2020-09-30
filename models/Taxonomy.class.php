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
use Exception;

/**
 * Taxonomy Model
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder
 * @subpackage models
 */
class Taxonomy
{
    /**
     * @since 1.0.0
     * @access private
     *
     * @var string The unique taxonomy ID used to register and query metadata
     *             for a given term.
     */
    private string $id;

    /**
     * @since 1.0.0
     * @access private
     *
     * @var string The taxonomy label to be displayed in the front end.
     */
    private string $label;

    /**
     * Array of arguments for registering a taxonomy. See WordPress
     * documentation for further information on the args parameter.
     *
     * @since 1.0.0
     * @access private
     *
     * @var array The taxonomy arguments.
     *
     * @link https://developer.wordpress.org/reference/functions/register_taxonomy/
     */
    private array $args;

    /**
     * @since 1.0.0
     * @access private
     *
     * @var IField[] The fields for this taxonomy.
     */
    private array $fields;

    /**
     * @since 1.0.0
     *
     * @param string   $id     The unique taxonomy ID used to register and query
     *                         metadata for a given term.
     * @param string   $label  The taxonomy label to be displayed in the front
     *                         end.
     * @param IField[] $fields The fields for this taxonomy.
     */
    public function __construct(
        string $id,
        array $args,
        array $fields = array()
    ) {
        if (
            !array_key_exists('labels', $args) ||
            !array_key_exists('name', $args['labels'])
        ) {
            throw new Exception("Taxonomy {$id} is missing args['labels']['name'].");
        }
        $this->id = $id;
        $this->args = $args;
        $this->label = $args['labels']['name'];
        $this->fields = $fields;
    }

    /**
     * @since 1.0.0
     *
     * @return string The unique taxonomy id.
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @since 1.0.0
     *
     * @return string The taxonomy label to be displayed in the front end.
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @since 1.0.0
     *
     * @return array The taxonomy arguments used to register it.
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @since 1.0.0
     *
     * @return IField[] The fields for this taxonomy.
     */
    public function getFields(): array
    {
        return $this->fields;
    }
}
