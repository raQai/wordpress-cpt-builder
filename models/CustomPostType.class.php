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

/**
 * Custom Post Type Model
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder
 * @subpackage models
 */
class CustomPostType
{
    /**
     * Slug used for the custom post type.
     * 
     * @since 1.0.0
     * @access private
     *
     * @var string The post type slug.
     */
    private string $slug;

    /**
     * Array of arguments for registering a post type. See WordPress
     * documentation for further information on the args parameter.
     * 
     * @since 1.0.0
     * @access private
     *
     * @var array This posts arguments used to register it.
     *
     * @link https://developer.wordpress.org/reference/functions/register_post_type/
     */
    private array $args;

    /**
     * Taxonmies for this custom post type.
     * 
     * @since 1.0.0
     * @access private
     *
     * @var Taxonomy[] The taxonomies to register.
     */
    private array $taxonomies;

    /**
     * Meta boxes for this custom post type.
     * 
     * @since 1.0.0
     * @access private
     *
     * @var MetaBox[] The meta boxes to register.
     */
    private array $meta_boxes;

    /**
     * Related child custom post typees for this custom post type.
     * 
     * @since 1.0.0
     * @access private
     *
     * @var CustomPostType[] Related child custom post types.
     */
    private array $child_cpts;

    /**
     * Constructs a custom post type object for the given parameters.
     * This does not register or display it by any means. It is just a
     * representation.
     * 
     * @since 1.0.0
     *
     * @param string           $slug       The post type slug.
     * @param array            $args       The post type arguments.
     * @param Taxonomy[]       $taxonomies The taxonomies to register.
     * @param MetaBox[]        $meta_boxes The meta boxes to register.
     * @param CustomPostType[] $child_cpts Related child custom post types.
     */
    public function __construct(
        string $slug,
        array $args,
        array $taxonomies = array(),
        array $meta_boxes = array(),
        array $child_cpts = array()
    ) {
        $this->slug = $slug;
        $this->args = $args;

        // store taxonomies as dictionary (id => Taxonomy)
        $this->taxonomies = array();
        foreach ($taxonomies as $taxonomy) {
            $this->taxonomies[$taxonomy->getId()] = $taxonomy;
        }

        // store meta boxes as dictionary (id => MetaBox)
        $this->meta_boxes = array();
        foreach ($meta_boxes as $meta_box) {
            $this->meta_boxes[$meta_box->getId()] = $meta_box;
        }

        $this->child_cpts = $child_cpts;
    }

    /**
     * @since 1.0.0
     *
     * @return string This posts slug/post_type.
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @since 1.0.0
     *
     * @return array This posts registration arguments.
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @since 1.0.0
     *
     * @return Taxonomy[] This posts taxonomies.
     */
    public function getTaxonomies(): array
    {
        return $this->taxonomies;
    }

    /**
     * @since 1.0.0
     *
     * @return MetaBox[] This posts meta boxes.
     */
    public function getMetaBoxes(): array
    {
        return $this->meta_boxes;
    }

    /**
     * @since 1.0.0
     *
     * @return CustomPostType[] This posts related child custom post types.
     */
    public function getChildCPTs(): array
    {
        return $this->child_cpts;
    }
}
