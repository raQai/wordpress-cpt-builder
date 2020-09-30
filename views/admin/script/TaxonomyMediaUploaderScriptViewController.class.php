<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\views\admin\script;

use BadFunctionCallException;
use BIWS\CPTBuilder\models\CustomPostType;
use BIWS\CPTBuilder\models\fields\FieldType;
use BIWS\CPTBuilder\models\fields\IField;
use BIWS\CPTBuilder\services\TemplateType;
use BIWS\CPTBuilder\services\TemplateService;
use BIWS\CPTBuilder\views\FieldRenderObject;
use BIWS\CPTBuilder\views\IViewController;

/**
 * Taxonomy media upload script view controller implementation
 * 
 * Provides the necessarry functionality to render the media upload scripts
 * for all supported fields of a given custom post types' taxonomies.
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder\views\admin
 * @subpackage script
 */
class TaxonomyMediaUploaderScriptViewController implements IViewController
{
    /**
     * The current media upload script version to use.
     * 
     * @since 1.0.0
     * @access private
     *
     * @var string JS_MEDIA_UPLOAD_VERSION The script version.
     */
    private const JS_MEDIA_UPLOAD_VERSION = '0.2.0';

    /**
     * The media upload script url to register and enqueue.
     *
     * @since 1.0.0
     * @access private
     * 
     * @global string BIWS_CPT_BUILDER__PLUGIN_DIR_URL
     *
     * @var string JS_MEDIA_UPLOAD The media upload script url.
     */
    private const JS_MEDIA_UPLOAD = BIWS_CPT_BUILDER__PLUGIN_DIR_URL
        . 'public/js/biws.mediauploader-'
        . self::JS_MEDIA_UPLOAD_VERSION
        . '.js';

    /**
     * @since 1.0.0
     * @access private
     * 
     * @var CustomPostType $cpt The custom post type to render the script for.
     */
    private CustomPostType $cpt;

    /**
     * @since 1.0.0
     * @access private
     * 
     * @var TemplateService $template_service The tempalte service.
     */
    private TemplateService $template_service;

    /**
     * @since 1.0.0
     * 
     * @param CustomPostType $cpt The custom post type to render the script for.
     */
    public function __construct(CustomPostType $cpt)
    {
        $this->cpt = $cpt;
        $this->template_service = TemplateService::getInstance();
    }

    /**
     * @since 1.0.0
     *
     * @see IViewController::init()
     * @see add_action()
     *
     * @link https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
     */
    public function init(): void
    {
        add_action('init', array($this, 'registerScript'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueScript'));
    }

    /**
     * @since 1.0.0
     *
     * @see IViewController::remove()
     * @see remove_action()
     */
    public function remove(): void
    {
        remove_action('init', array($this, 'registerScript'));
        remove_action(
            'admin_enqueue_scripts',
            array($this, 'enqueueScript')
        );
    }

    /**
     * Registers the media upload script if it os not already registered.
     * 
     * @since 1.0.0
     * 
     * @see wp_script_is()
     * @see wp_register_script()
     * 
     * @link https://developer.wordpress.org/reference/functions/wp_register_script/
     */
    public function registerScript()
    {
        if (wp_script_is('biws-media-uploader', 'registered')) {
            return;
        }
        wp_register_script(
            'biws-media-uploader',
            self::JS_MEDIA_UPLOAD,
            array('jquery'),
            self::JS_MEDIA_UPLOAD_VERSION,
            true
        );
    }

    /**
     * Enqueues all scripts, styles, settings and templates necessary to use all
     * WordPress media JS APIs and the script to actually use it on image fields
     * if the location is valid.
     * 
     * @since 1.0.0
     * 
     * @see TaxonomyMediaUploaderScriptViewController::isValidScriptLocation()
     * @see wp_enqueue_media()
     * @see wp_enqueue_script()
     * 
     * @link https://developer.wordpress.org/reference/functions/wp_enqueue_script/
     */
    public function enqueueScript()
    {
        if (!$this->isValidScriptLocation()) {
            return;
        }
        if (!did_action('wp_enqueue_media')) {
            wp_enqueue_media();
        }
        if (!wp_script_is('biws-media-uploader', 'enqueued')) {
            wp_enqueue_script('biws-media-uploader');
        }
    }

    /**
     * Determines whether the media upload script is called on a valid location
     * to avoid including the script unnecessarily.
     * 
     * @since 1.0.0
     * 
     * @global string $pagenow
     * @global string $typenow
     * @global string $taxnow
     *
     * @return bool true if $pagenow is a edit-tags/term page,
     *              if the $cpt slug equals $typenow,
     *              if the $cpt contains the $taxnow,
     *              and if $taxnow contains image fields,
     *              false otherwise.
     */
    public function isValidScriptLocation(): bool
    {
        global $pagenow, $typenow, $taxnow;

        // not valid page
        if (!in_array($pagenow, array('edit-tags.php', 'term.php'))) {
            return false;
        }

        // not valid post type
        if ($this->cpt->getSlug() !== $typenow) {
            return false;
        }

        foreach ($this->cpt->getTaxonomies() as $taxonomy) {
            // not current taxonomy
            if ($taxonomy->getId() !== $taxnow) {
                continue;
            }
            // check all fields of the taxonmy
            foreach ($taxonomy->getFields() as $field) {
                // taxonomy contains image, valid script location
                if ($field->getType() === FieldType::IMAGE) {
                    return true;
                }
            }
        }

        // No valid script location
        return false;
    }

    /**
     * Returns all image fields for the current taxonomy.
     * 
     * @since 1.0.0
     * 
     * @see TaxonomyMediaUploaderScriptViewController::isValidScriptLocation()
     * 
     * @global string $taxnow
     * 
     * @return IField[] The image fields of the current taxonomy, empty array
     *                  if there are none which should never be the case due
     *                  to the validity check.
     * 
     * @throws BadFunctionCallException if this function was called on an invalid location.
     */
    public function getImageFields(): array
    {
        if (!$this->isValidScriptLocation()) {
            throw new BadFunctionCallException("Not a valid script location");
        }

        global $taxnow;

        foreach ($this->cpt->getTaxonomies() as $taxonomy) {
            // is current taxonomy
            if ($taxonomy->getId() === $taxnow) {
                return array_filter(
                    $taxonomy->getFields(),
                    function (IField $field) {
                        return $field->getType() === FieldType::IMAGE;
                    }
                );
            }
        }

        return array();
    }

    /**
     * Returns the render object for the media upload script.
     * 
     * @since 1.0.0
     * 
     * @return FieldRenderObject A simple field render object to be rendered.
     */
    public function getMediaUploadScript($field): FieldRenderObject
    {
        return new FieldRenderObject(
            $field,
            $this->template_service->getTemplatePath(
                TemplateType::TAXONOMY,
                "media_upload_script",
                $field->getId()
            )
        );
    }
}
