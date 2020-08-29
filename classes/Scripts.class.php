<?php

namespace BIWS\EventManager;

defined('ABSPATH') or die('Nope!');

class Scripts
{
    private const SCRIPT_PATH = BIWS_EventManager__PLUGIN_DIR_PATH . 'includes/scripts/';
    private const SCRIPT_JS_URL = BIWS_EventManager__PLUGIN_DIR_URL . 'public/js/';
    private const MEDIA_UPLOAD = self::SCRIPT_PATH . 'MediaUploaderScript.inc.php';
    private const JS_MEDIA_UPLOAD = self::SCRIPT_JS_URL . 'biws.media-uploader-0.1.2.js';

    private const METABOXES = self::SCRIPT_PATH . 'MetaboxesScript.inc.php';
    private const JS_METABOXES = self::SCRIPT_JS_URL . 'biws.metaboxes-0.1.1.js';

    private static function isLoadScript(
        $valid_pages = null,
        $post_slug = null,
        $taxonomy_slug = null
    ) {
        global $pagenow, $typenow, $taxnow;

        if ($valid_pages && !in_array($pagenow, $valid_pages)) {
            return false;
        }
        if ($post_slug && $post_slug != $typenow) {
            return false;
        }
        if ($taxonomy_slug && $taxonomy_slug != $taxnow) {
            return false;
        }

        return true;
    }

    public static function enqueueMediaUploaderScript(
        $post_slug,
        $taxonomy_slug,
        $hook,
        $location_hook,
        $containerSelector,
        $inputSelector,
        $imageContainerSelector,
        $setImageLinkSelector,
        $removeImageLinkSelector
    ) {
        add_action('init', function () {
            if (!wp_script_is('biws-media-uploader', 'registered')) {
                wp_register_script(
                    'biws-media-uploader',
                    self::JS_MEDIA_UPLOAD,
                    array('jquery'),
                    '0.1.2',
                    true
                );
            }
        });

        add_action($hook, function () use ($post_slug, $taxonomy_slug) {
            if (!self::isLoadScript(
                array('edit-tags.php', 'term.php'),
                $post_slug,
                $taxonomy_slug
            )) {
                return;
            }

            if (!did_action('wp_enqueue_media')) {
                wp_enqueue_media();
            }
            if (!wp_script_is('biws-media-uploader', 'enqueued')) {
                wp_enqueue_script('biws-media-uploader');
            }
        });

        add_action($location_hook, function () use (
            $post_slug,
            $taxonomy_slug,
            $containerSelector,
            $inputSelector,
            $imageContainerSelector,
            $setImageLinkSelector,
            $removeImageLinkSelector
        ) {
            if (!self::isLoadScript(
                array('edit-tags.php', 'term.php'),
                $post_slug,
                $taxonomy_slug
            )) {
                return;
            }

            $script_object = (object)(array());
            $script_object->containerSelector = $containerSelector;
            $script_object->inputSelector = $inputSelector;
            $script_object->imageContainerSelector = $imageContainerSelector;
            $script_object->setImageLinkSelector = $setImageLinkSelector;
            $script_object->removeImageLinkSelector = $removeImageLinkSelector;
            ob_start();
            include self::MEDIA_UPLOAD;
            ob_end_flush();
        });
    }

    public static function enqueueMetaboxesScript($post_slug, $meta_boxes)
    {
        add_action('init', function () {
            if (!wp_script_is('biws-metaboxes')) {
                wp_register_script(
                    'biws-metaboxes',
                    self::JS_METABOXES,
                    array('wp-plugins', 'wp-edit-post', 'wp-element', 'wp-components'),
                    '0.1.1',
                    true
                );
            }
        });
        add_action('enqueue_block_editor_assets', function ($post_slug) {
            if (!self::isLoadScript(array('post.php'), $post_slug)) {
                return;
            }
            wp_enqueue_script('biws-metaboxes');
        });
        add_action('admin_footer', function () use ($post_slug, $meta_boxes) {
            if (!self::isLoadScript(array('post.php'), $post_slug)) {
                return;
            }
            $script_object = (object)(array());
            $script_object->post_slug = $post_slug;
            $script_object->meta_boxes = $meta_boxes;
            ob_start();
            include self::METABOXES;
            ob_end_flush();
        });
    }
}
