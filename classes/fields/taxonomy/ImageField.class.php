<?php

namespace BIWS\EventManager\fields\taxonomy;

use BIWS\EventManager\Scripts;
use BIWS\EventManager\Templates;

defined('ABSPATH') or die('Nope!');

class ImageField extends AbstractTaxonomyField
{

    public function __construct($id, $label)
    {
        parent::__construct($id, $label, false);
    }

    function sanitizePostData($post_data)
    {
        return filter_var(
            parent::sanitizePostData($post_data),
            FILTER_SANITIZE_NUMBER_INT
        );
    }

    function getInputTemplatePath()
    {
        return Templates::IMAGE_FIELD_INPUT;
    }

    function buildRenderObject()
    {
        $render_object = parent::buildRenderObject();
        $render_object->container_attributes["id"] = $this->getId() . '_media-selector';
        $render_object->input_attributes["type"] = "hidden";
        $render_object->has_image_set = false;
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

    public function init($post_slug)
    {
        parent::init($post_slug);

        $inputSelector = '#' . $this->getId();
        $containerSelector = $inputSelector . '_media-selector';
        $imageContainerSelector = $inputSelector . '_media-container';
        $setImageLinkSelector = $inputSelector . '_media-button';
        $removeImageLinkSelector = $inputSelector . '_media-remove';

        Scripts::enqueueMediaUploaderScript(
            'admin_enqueue_scripts',
            'admin_footer',
            $containerSelector,
            $inputSelector,
            $imageContainerSelector,
            $setImageLinkSelector,
            $removeImageLinkSelector
        );
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
