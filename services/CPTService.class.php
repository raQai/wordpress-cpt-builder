<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\services;

use BIWS\CPTBuilder\models\CustomPostType;
use BIWS\CPTBuilder\models\fields\FieldType;
use BIWS\CPTBuilder\models\MetaBox;
use BIWS\CPTBuilder\models\Taxonomy;
use InvalidArgumentException;

/**
 * Service implementation to register custom post types
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder
 * @subpackage services
 */
class CPTService extends Service
{
    /**
     * @since 1.0.0
     * @access private
     *
     * @var CustomPostType[] $registered_cpts {
     *     @type string         $key   The post types slug.
     *     @type CustomPostType $value The CustomPostType instance
     * } Holder for the registered custom post types.
     */
    private array $registered_cpts = array();

    /**
     * Registers given Custom Post Type
     *
     * Registers a custom post type and its child custom post types with this
     * service and initializes them recursively.
     *
     * @since 1.0.0
     *
     * @see CPTService::_init()
     *
     * @param CustomPostType $cpt The custom post type to register.
     */
    public function registerAndInit(CustomPostType $cpt): void
    {
        $this->registered_cpts[$cpt->getSlug()] = $cpt;

        $this->_init($cpt);

        foreach ($cpt->getChildCPTs() as $child_cpt) {
            $this->registerAndInit($child_cpt);
        }
    }

    /**
     * Initializes a given Custom Post Type
     *
     * Adds all nevessary actions and filtersto initialize the given custom post
     * type and all related taxonomies and meta boxes.
     * Also adds the child custom post types taxonomy menu entries as submenu
     * entries within the parents custom post types' menu entry.
     *
     * @since 1.0.0
     * @access private
     *
     * @see register_post_type()
     * @see CPTService::_initTaxonomy()
     * @see CPTService::_initMetaBox()
     *
     * @param CustomPostType $cpt The custom post type to initialize.
     *
     * @link https://developer.wordpress.org/reference/functions/register_post_type/
     */
    private function _init(CustomPostType $cpt): void
    {
        foreach ($this->registered_cpts as $cpt) {
            // basic initialisation
            add_action('init', function () use ($cpt) {
                register_post_type($cpt->getSlug(), $cpt->getArgs());
            });

            foreach ($cpt->getTaxonomies() as $taxonomy) {
                $this->_initTaxonomy($cpt, $taxonomy);
            }

            // register metabox fields
            foreach ($cpt->getMetaBoxes() as $meta_box) {
                $this->_initMetaBox($cpt, $meta_box);
            }

            foreach ($cpt->getChildCPTs() as $child_cpt) {

                // only working for 1 level now. Children of children wont display properly
                foreach ($child_cpt->getTaxonomies() as $taxonomy) {
                    // add child taxonomies to parent navigation
                    add_action('admin_menu', function () use ($cpt, $child_cpt, $taxonomy) {
                        add_submenu_page(
                            "edit.php?post_type={$cpt->getSlug()}",
                            $taxonomy->getLabel(),
                            "- {$taxonomy->getLabel()}",
                            'manage_options',
                            "edit-tags.php?taxonomy={$taxonomy->getId()}&post_type={$child_cpt->getSlug()}"
                        );
                    });

                    // fix parent file of taxonomies to allow proper navigation handling
                    add_filter('parent_file', function ($parent_file) use ($cpt, $child_cpt, $taxonomy) {
                        global $submenu_file, $current_screen, $pagenow;
                        if (
                            $current_screen->post_type == $child_cpt->getSlug() &&
                            $pagenow == 'edit-tags.php' &&
                            stripos($submenu_file, $taxonomy->getId()) !== false
                        ) {
                            $submenu_file = "edit-tags.php?taxonomy={$taxonomy->getId()}&post_type={$current_screen->post_type}";
                            $parent_file = "edit.php?post_type={$cpt->getSlug()}";
                        }
                        return $parent_file;
                    });
                }
            }
        }
    }

    /**
     * Initializes a given Taxonomy
     *
     * Adds all nevessary actions to register and initialize the given taxonomy.
     *
     * @since 1.0.0
     * @access private
     *
     * @see register_taxonomy()
     *
     * @param CustomPostType $cpt      The parent custom post type for the given
     *                                 $taxonomy.
     * @param Taxonomy       $taxonomy The taxonomy to register and initialize.
     *
     * @link https://developer.wordpress.org/reference/functions/register_taxonomy/
     */
    private function _initTaxonomy(
        CustomPostType $cpt,
        Taxonomy $taxonomy
    ): void {
        $post_slug = $cpt->getSlug();

        add_action('init', function () use ($post_slug, $taxonomy) {
            register_taxonomy(
                $taxonomy->getId(),
                $post_slug,
                $taxonomy->getArgs()
            );
        });
    }

    /**
     * Initializes a given MetaBox
     *
     * Adds all nevessary actions to register and initialize the given meta box.
     *
     * @since 1.0.0
     * @access private
     *
     * @see register_post_meta()
     *
     * @param CustomPostType $cpt      The parent custom post type for the given
     *                                 $meta_box.
     * @param MetaBox        $meta_box The meta box to register and initialize.
     *
     * @link https://developer.wordpress.org/reference/functions/register_post_meta/
     */
    private function _initMetaBox(
        CustomPostType $cpt,
        MetaBox $meta_box
    ): void {
        $post_slug = $cpt->getSlug();

        add_action('init', function () use ($post_slug, $meta_box) {
            foreach ($meta_box->getFields() as $field) {
                register_post_meta($post_slug, $field->getId(), array(
                    // TODO maybe make args configurable in model but for
                    // now this is fine.
                    "type" => $this->toMetaKeyTypeString($field->getType()),
                    "single" => true,
                    "show_in_rest" => true
                ));
            }
        });
    }

    /**
     * Converts a FieldType to a valid meta argument type.
     * See wordpress documentation for more details
     * 
     * @since 1.0.0
     * @access private
     *
     * @link https://developer.wordpress.org/reference/functions/register_meta/
     *
     * @throws InvalidArgumentException if the field type is not supported.
     */
    private function toMetaKeyTypeString(int $fieldType): string
    {
        switch ($fieldType) {
            case FieldType::NUMBER:
                return 'integer';
            case FieldType::CHECKBOX:
                return 'boolean';
            case FieldType::TEXT:
            case FieldType::DATE:
            case FieldType::TIME:
            case FieldType::EMAIL:
                return 'string';
            default:
                throw new InvalidArgumentException(
                    "Field type {$fieldType} not supported."
                );
        }
    }
}
