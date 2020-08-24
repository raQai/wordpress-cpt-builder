<?php

namespace BIWS\EventManager\fields\metabox;

use BIWS\EventManager\Templates;

defined('ABSPATH') or die('Nope!');

class CheckBoxField extends AbstractMetaBoxField
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
        return Templates::META_BOX_CHECK_BOX_INPUT;
    }

    function buildRenderObject($post)
    {
        $render_object = parent::buildRenderObject($post);
        $render_object->label_attributes["class"] = "components-checkbox-control__label";
        $render_object->input_attributes["type"] = "checkbox";
        //FIXME cannot use basic ui class because it avoids checking the checkbox (probably needs to add script)
        //$render_object->input_attributes["class"] = "components-checkbox-control__input";
        $render_object->input_attributes['value'] = 1;
        if ($this->getValue($post->ID)) {
            $render_object->input_attributes["checked"] = "checked";
        }
        return $render_object;
    }

    public function renderField($post)
    {
        $render_object = $this->buildRenderObject($post);

        include Templates::META_BOX_CHECK_BOX_FORM_FIELD;
    }
}
