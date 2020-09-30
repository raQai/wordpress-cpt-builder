<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\views;

use BIWS\CPTBuilder\models\CustomPostType;
use BIWS\CPTBuilder\models\fields\FieldType;
use BIWS\CPTBuilder\services\Service;
use BIWS\CPTBuilder\views\admin\cpt\CPTColumnView;
use BIWS\CPTBuilder\views\admin\cpt\CPTColumnViewController;
use BIWS\CPTBuilder\views\admin\script\MetaBoxScriptView;
use BIWS\CPTBuilder\views\admin\script\MetaBoxScriptViewController;
use BIWS\CPTBuilder\views\admin\script\TaxonomyMediaUploaderScriptView;
use BIWS\CPTBuilder\views\admin\script\TaxonomyMediaUploaderScriptViewController;
use BIWS\CPTBuilder\views\admin\taxonomy\EditTaxonomyView;
use BIWS\CPTBuilder\views\admin\taxonomy\EditTaxonomyViewController;
use BIWS\CPTBuilder\views\admin\taxonomy\NewTaxonomyView;
use BIWS\CPTBuilder\views\admin\taxonomy\NewTaxonomyViewController;
use BIWS\CPTBuilder\views\admin\taxonomy\TaxonomyColumnView;
use BIWS\CPTBuilder\views\admin\taxonomy\TaxonomyColumnViewController;
use BIWS\CPTBuilder\views\IView;
use BIWS\CPTBuilder\views\IViewController;

/**
 * Render service implementation
 *
 * Allows registering new views for the front and backend by storing them in an
 * array and ensuring a view will be deregistered before overwriting it.
 *
 * @since      1.0.0
 *
 * @see Service
 *
 * @package    BIWS\CPTBuilder
 * @subpackage views
 */
class RenderService extends Service
{
    /**
     * @since 1.0.0
     * @access private
     *
     * @var array $post_views {
     *     @type string $key   May be a key defined by RenderType if a view will
     *                         be registered for the post or a posts slug to
     *                         further specify dedicated taxonomie and meta box
     *                         views.
     *     @type array  $value {
     *         @type string      $key   May be a posts slug if registering a view for
     *                                  a post type. 'taxonomy' or 'meta_box' if
     *                                  registering a view for a taxonomy or meta box
     *                                  respectively.
     *         @type IView|array $value May be an instance of a registered view for
     *                                  a post or and array following the following
     *                                  pattern: RenderType =>
     *                                               Taxonomy/MetaBox id =>
     *                                                   IView instance
     *     }
     * }
     */
    private array $post_views = array();

    /**
     * Registers a view for a post
     *
     * @since 1.0.0
     *
     * @see RenderService::_register()
     *
     * @param string $post_slug   The post for which the view should be applied
     *                            to.
     * @param string $render_type Defined by RenderType or a custom string.
     * @param IView  $view        A created IView instance to be registered.
     */
    public function registerPost(
        string $post_slug,
        string $render_type,
        IView $view
    ) {
        if (!isset($this->post_views[$render_type])) {
            $this->post_views[$render_type] = array();
        }
        $this->_register(
            $this->post_views[$render_type],
            $post_slug,
            $view,
            true
        );
    }

    /**
     * Registers a view for a taxonomy
     *
     * @since 1.0.0
     *
     * @see RenderService::_register()
     *
     * @param string $post_slug   The post for which the taxonomy view should be
     *                            applied to.
     * @param string $taxonomy    The unique taxonomy name/slug/id.
     * @param string $render_type Defined by RenderType or a custom string.
     * @param IView  $view        A created IView instance to be registered.
     */
    public function registerTaxonomy(
        string $post_slug,
        string $taxonomy,
        string $render_type,
        IView $view
    ): void {
        if (!isset($this->post_views[$post_slug]["taxonomy"][$render_type])) {
            $this->post_views[$post_slug]["taxonomy"][$render_type] = array();
        }
        $this->_register(
            $this->post_views[$post_slug]["taxonomy"][$render_type],
            $taxonomy,
            $view,
            true
        );
    }

    /**
     * Registers a view for a meta box
     *
     * @since 1.0.0
     *
     * @see RenderService::_register()
     *
     * @param string $post_slug   The post for which the meta box view should be
     *                            applied to.
     * @param string $meta_box    The unique meta box name/slug/id.
     * @param string $render_type Defined by RenderType or a custom string.
     * @param IView  $view        A created IView instance to be registered.
     */
    public function registerMetaBox(
        string $post_slug,
        string $meta_box,
        string $render_type,
        IView $view
    ): void {
        if (!isset($this->post_views[$post_slug]["meta_box"][$render_type])) {
            $this->post_views[$post_slug]["meta_box"][$render_type] = array();
        }
        $this->_register(
            $this->post_views[$post_slug]["meta_box"][$render_type],
            $meta_box,
            $view,
            true
        );
    }

    /**
     * Registers a view
     *
     * @since 1.0.0
     * @access private
     *
     * @param array  $target             Reference to the target location within
     *                                   the $post_views array.
     * @param string $key                The unique post slug, taxonomy or meta
     *                                   box name/slug/id.
     * @param IView  $view               A created IView instance to be
     *                                   registered.
     * @param bool   $overwrite_existing Defines whether to overwrite existings
     *                                   views or not. This will lead to a no-op
     *                                   if there is already a registered view
     *                                   for the provided $key in the $target
     *                                   location.
     */
    private function _register(
        array &$target,
        string $key,
        IView $view,
        bool $overwrite_existing = false
    ): void {
        if (!is_array($target)) {
            $target = array();
        }

        if ($overwrite_existing) {
            $this->_removeExistingViews($target, $key);
        }

        if (!array_key_exists($key, $target)) {
            $target[$key] = $view;
            $view->init();
            $view->getController()->init();
        }
    }

    /**
     * Removes and deregisters a view calling the corresponding methods
     *
     * @since 1.0.0
     * @access private
     * 
     * @see IView::remove()
     * @see IViewController::remove()
     *
     * @param array  $target Reference to the target location within the
     *                       $post_views array.
     * @param string $key    The unique post slug, taxonomy or meta box
     *                       name/slug/id.
     */
    private function _removeExistingViews(
        array &$target,
        string $key
    ): void {
        if (array_key_exists($key, $target)) {
            $view = $target[$key];
            if ($view instanceof IView) {
                $controller = $view->getController();
                if ($controller instanceof IViewController) {
                    $controller->remove();
                }
                $view->remove();
            }
            unset($target[$key]);
        }
    }

    /**
     * Registers a default view for a post
     * 
     * Does not overwrite existing views.
     *
     * @since 1.0.0
     * @access private
     *
     * @see RenderService::_register()
     *
     * @param string $post_slug   The post for which the view should be applied
     *                            to.
     * @param string $render_type Defined by RenderType or a custom string.
     * @param IView  $view        A created IView instance to be registered.
     */
    private function _registerPostDefault(
        string $post_slug,
        string $render_type,
        IView $view
    ) {
        if (!isset($this->post_views[$render_type])) {
            $this->post_views[$render_type] = array();
        }
        $this->_register(
            $this->post_views[$render_type],
            $post_slug,
            $view,
            false
        );
    }

    /**
     * Registers a default view for a taxonomy
     * 
     * Does not overwrite existing views.
     *
     * @since 1.0.0
     * @access private
     *
     * @see RenderService::_register()
     *
     * @param string $post_slug   The post for which the taxonomy view should be
     *                            applied to.
     * @param string $taxonomy    The unique taxonomy name/slug/id.
     * @param string $render_type Defined by RenderType or a custom string.
     * @param IView  $view        A created IView instance to be registered.
     */
    private function _registerTaxnomyDefault(
        string $post_slug,
        string $taxonomy,
        string $render_type,
        IView $view
    ): void {
        if (!isset($this->post_views[$post_slug]["taxonomy"][$render_type])) {
            $this->post_views[$post_slug]["taxonomy"][$render_type] = array();
        }
        $this->_register(
            $this->post_views[$post_slug]["taxonomy"][$render_type],
            $taxonomy,
            $view,
            false
        );
    }

    /**
     * Registers a default view for a meta box
     * 
     * Does not overwrite existing views.
     *
     * @since 1.0.0
     * @access private
     *
     * @see RenderService::_register()
     *
     * @param string $post_slug   The post for which the meta box view should be
     *                            applied to.
     * @param string $meta_box    The unique meta box name/slug/id.
     * @param string $render_type Defined by RenderType or a custom string.
     * @param IView  $view        A created IView instance to be registered.
     */
    private function _registerMetaBoxDefault(
        string $post_slug,
        string $meta_box,
        string $render_type,
        IView $view
    ): void {
        if (!isset($this->post_views[$post_slug]["meta_box"][$render_type])) {
            $this->post_views[$post_slug]["meta_box"][$render_type] = array();
        }
        $this->_register(
            $this->post_views[$post_slug]["meta_box"][$render_type],
            $meta_box,
            $view,
            false
        );
    }

    /**
     * Registers default views
     * 
     * Registeres provided default views to the given custom post type and
     * all corresponding taxonomies, meta boxes and child custom post types.
     *
     * @since 1.0.0
     *
     * @param CustomPostType $cpt A created custom post type to supply with
     *                            default views.
     * 
     * @todo should actually move out to allow moving the render service to
     *       the intended subpackage 'services'.
     */
    public function registerDefaults(CustomPostType $cpt)
    {
        $post_type = $cpt->getSlug();

        // Column view to display meta box values in admin column view.
        // Generally adds all fields of the meta boxes.
        $this->_registerPostDefault(
            $post_type,
            RenderType::CPT_COLUMN,
            new CPTColumnView(new CPTColumnViewController($cpt))
        );

        // Meta box view to display meta boxes in gutenberg editor.
        // Requires show_in_rest for the provided meta moxes.
        $this->_registerPostDefault(
            $post_type,
            RenderType::CPT_META_BOX_SCRIPT,
            new MetaBoxScriptView(new MetaBoxScriptViewController($cpt))
        );

        $contains_image_fields = false;
        foreach ($cpt->getTaxonomies() as $taxonomy) {
            $taxonomy_id = $taxonomy->getId();

            // column view to display taxonomy fields in admin column view.
            // Generally adds all fields of the taxonomy
            $this->_registerTaxnomyDefault(
                $post_type,
                $taxonomy_id,
                RenderType::TAXONOMY_COLUMN,
                new TaxonomyColumnView(
                    new TaxonomyColumnViewController($taxonomy)
                )
            );

            // View to add all fields to the new taxonomy form.
            // Generally adds all fields of the taxonomy.
            $this->_registerTaxnomyDefault(
                $post_type,
                $taxonomy_id,
                RenderType::TAXONOMY_NEW,
                new NewTaxonomyView(new NewTaxonomyViewController($taxonomy))
            );

            // View to add all fields to the edit taxonomy form.
            // Generally adds all fields of the taxonomy.
            $this->_registerTaxnomyDefault(
                $post_type,
                $taxonomy_id,
                RenderType::TAXONOMY_EDIT,
                new EditTaxonomyView(new EditTaxonomyViewController($taxonomy))
            );

            // check if image fields are present
            if (!$contains_image_fields) {
                foreach ($taxonomy->getFields() as $field) {
                    if ($field->getType() === FieldType::IMAGE) {
                        $contains_image_fields = true;
                        break;
                    }
                }
            }
        }

        // Add media uploader script to the cpt.
        // Will only be added if an ImageField is present although
        // the default view only enqueues the scripts if needed.
        if ($contains_image_fields) {
            $this->_registerPostDefault(
                $post_type,
                RenderType::CPT_MEDIA_UPLOADER_SCRIPT,
                new TaxonomyMediaUploaderScriptView(
                    new TaxonomyMediaUploaderScriptViewController($cpt)
                )
            );
        }
        foreach ($cpt->getMetaBoxes() as $meta_box) {
            // currently no default views
            // TODO support cpts without gutenberg support.
            //      views will be added here.
        }

        // Register default views for child custom post types
        foreach ($cpt->getChildCPTs() as $child) {
            $this->registerDefaults($child);
        }
    }
}
