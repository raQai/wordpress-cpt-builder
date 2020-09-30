<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\views\admin\cpt;

use BIWS\CPTBuilder\models\CustomPostType;
use BIWS\CPTBuilder\models\fields\IField;
use BIWS\CPTBuilder\views\admin\AbstractColumnViewController;

/**
 * Post column view controller implementation
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder\views\admin
 * @subpackage cpt
 */
class CPTColumnViewController extends AbstractColumnViewController
{
    /**
     * @since 1.0.0
     * @access private
     *
     * @var CustomPostType $cpt The custom post type used for this view
     */
    private CustomPostType $cpt;

    /**
     * @since 1.0.0
     * 
     * @param CustomPostType $cpt            The cpt this view is meant for.
     * @param bool           $add_all_fields Defines whether to add all the
     *                                       meta box fields to the columns.
     */
    public function __construct(
        CustomPostType $cpt,
        bool $add_all_fields = true
    ) {
        $initial_fields = array();
        if ($add_all_fields) {
            foreach ($cpt->getMetaBoxes() as $meta_box) {
                foreach ($meta_box->getFields() as $field) {
                    $initial_fields[] = $field;
                }
            }
        }
        parent::__construct($initial_fields);

        $this->cpt = $cpt;
    }

    /**
     * @since 1.0.0
     *
     * @return string The custom post types slug used for this view
     */
    public function getCPTSlug(): string
    {
        return $this->cpt->getSlug();
    }

    /**
     * @since 1.0.0
     * @access protected
     *
     * @see get_post_meta()
     *
     * @param IField $field   The meta box field to obtain the metadata for.
     * @param int    $post_id The post id to obtain the metadata for.
     *
     * @return mixed The post meta value for the provided $field and $post_id.
     * 
     * @link https://developer.wordpress.org/reference/functions/get_post_meta/
     */
    protected function getValue(IField $field, int $post_id)
    {
        return get_post_meta($post_id, $field->getId(), true);
    }
}
