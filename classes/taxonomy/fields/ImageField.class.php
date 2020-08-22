<?php

namespace BIWS\EventManager\taxonomy\fields;

defined('ABSPATH') or die('Nope!');

class ImageField extends AbstractTaxonomyField
{
    function sanitizePostData($post_data)
    {
        return filter_var(
            parent::sanitizePostData($post_data),
            FILTER_SANITIZE_NUMBER_INT
        );
    }

    function getInputTemplatePath()
    {
        return BIWS_EventManager__PLUGIN_DIR_PATH . 'includes/templates/fields/ImageFormFieldInput.inc.php';
    }

    function buildRenderObject()
    {
        $render_object = parent::buildRenderObject();
        $render_object->container_attributes["id"] = $this->getId() . '_media-selector';
        $render_object->input_attributes["type"] = "hidden";
        $render_object->has_image_set = false;
        // TODO define
        return $render_object;
    }

    function buildEditRenderObject($term, $slug)
    {
        $image_id = parent::getValue($term->term_id);
        $render_object = $this->buildRenderObject();
        $render_object->input_attributes["value"] = $image_id;
        $render_object->has_image_set = $image_id;
        if ($image_id) {
            $render_object->attachment_image = wp_get_attachment_image($image_id, 'thumbnail');
        }
        return $render_object;
    }

    public function init($slug)
    {
        parent::init($slug);
        add_action('admin_enqueue_scripts', array($this, 'loadMediaScripts'));
        add_action('admin_footer', array($this, 'addScript'));
    }

    public function loadMediaScripts()
    {
        if (!did_action('wp_enqueue_media')) {
            wp_enqueue_media();
        }
        if (!wp_script_is('media-uploader')) {
            wp_enqueue_script(
                'media-uploader',
                BIWS_EventManager__PLUGIN_DIR_URL . 'public/js/jquery.media-uploader-0.0.1.js',
                array('jquery'),
                '0.0.1',
                true
            );
        }
    }

    public function addScript()
    {
        $script_object = (object)(array());
        $script_object->id = $this->getId();
        ob_start();
        include BIWS_EventManager__PLUGIN_DIR_PATH . 'includes/scripts/MediaUploaderScript.inc.php';
        ob_end_flush();
    }

    public function addTableColumn($columns)
    {
        return $columns;
    }

    public function addTableContent($content, $column_name, $term_id)
    {
        return $content;
    }
}
