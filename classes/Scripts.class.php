<?php

namespace BIWS\EventManager;

defined('ABSPATH') or die('Nope!');

class Scripts
{
    private const SCRIPT_PATH = BIWS_EventManager__PLUGIN_DIR_PATH . 'includes/scripts/';
    private const SCRIPT_JS_URL = BIWS_EventManager__PLUGIN_DIR_URL . 'public/js/';
    private const MEDIA_UPLOAD = self::SCRIPT_PATH . 'MediaUploaderScript.inc.php';
    private const JS_MEDIA_UPLOAD = self::SCRIPT_JS_URL . 'biws.media-uploader-0.1.1.js';

    public static function enqueueMediaUploaderScript(
        $hook,
        $location_hook,
        $containerSelector,
        $inputSelector,
        $imageContainerSelector,
        $setImageLinkSelector,
        $removeImageLinkSelector
    ) {
        add_action('init', function() {
            if (!wp_script_is('biws-media-uploader', 'registered')) {
                wp_register_script(
                    'biws-media-uploader',
                    self::JS_MEDIA_UPLOAD,
                    array('jquery'),
                    '0.1.1',
                    true
                );
            }
        });
        add_action($hook, function () {
            if (!did_action('wp_enqueue_media')) {
                wp_enqueue_media();
            }
            if (!wp_script_is('biws-media-uploader', 'enqueued')) {
                wp_enqueue_script('biws-media-uploader');
            }
        });
        add_action($location_hook, function () use (
            $containerSelector,
            $inputSelector,
            $imageContainerSelector,
            $setImageLinkSelector,
            $removeImageLinkSelector
        ) {
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
}
