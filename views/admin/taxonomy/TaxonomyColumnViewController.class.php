<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\views\admin\taxonomy;

use BIWS\CPTBuilder\models\fields\IField;
use BIWS\CPTBuilder\models\Taxonomy;
use BIWS\CPTBuilder\views\admin\AbstractColumnViewController;

/**
 * Post column view controller implementation
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder\views\admin
 * @subpackage taxonomy
 */
class TaxonomyColumnViewController extends AbstractColumnViewController
{
    /**
     * @since 1.0.0
     * @access private
     *
     * @var Taxonomy $taxonomy The taxonomy used for this view
     */
    private Taxonomy $taxonomy;

    /**
     * @since 1.0.0
     * 
     * @param Taxonomy $taxonomy       The taxonomy this view is meant for.
     * @param bool     $add_all_fields Defines whether to add all the 
     *                                 fields of this taxonomy to the columns.
     */
    public function __construct(Taxonomy $taxonomy, $add_all_fields = true)
    {
        $initial_fields = $add_all_fields ? $taxonomy->getFields() : array();
        parent::__construct($initial_fields);
        $this->taxonomy = $taxonomy;
    }

    /**
     * @since 1.0.0
     *
     * @return string The taxonomies slug used for this view
     */
    public function getTaxonomySlug(): string
    {
        return $this->taxonomy->getId();
    }

    /**
     * @since 1.0.0
     * @access protected
     *
     * @see get_term_meta()
     *
     * @param IField $field   The taxonomy field to obtain the metadata for.
     * @param int    $term_id The term id to obtain the metadata for.
     *
     * @return mixed The taxonomy meta value for the provided $field and $term_id.
     * 
     * @link https://developer.wordpress.org/reference/functions/get_term_meta/
     */
    protected function getValue(IField $field, int $term_id)
    {
        return get_term_meta($term_id, $field->getId(), true);
    }
}
