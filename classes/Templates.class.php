<?php

namespace BIWS\EventManager;

defined('ABSPATH') or die('Nope!');

class Templates
{
    private const TEMPLATE_PATH = BIWS_EventManager__PLUGIN_DIR_PATH . 'includes/templates/';
    const FORM_FIELD_INPUT = self::TEMPLATE_PATH . 'fields/FormFieldInput.inc.php';
    const FORM_FIELD_LABEL = self::TEMPLATE_PATH . 'fields/FormFieldLabel.inc.php';

    const IMAGE_FIELD_INPUT = self::TEMPLATE_PATH . 'fields/ImageFormFieldInput.inc.php';

    const TAXONOMY_FORM_FIELD = self::TEMPLATE_PATH . 'fields/taxonomy/TaxonomyFormField.inc.php';
    const TAXONOMY_EDIT_FORM_FIELD = self::TEMPLATE_PATH . 'fields/taxonomy/TaxonomyEditFormField.inc.php';

    const META_BOX_FIELD_WRAPPER_START = self::TEMPLATE_PATH . 'fields/metabox/MetaBoxFieldWrapperStart.inc.php';
    const META_BOX_FIELD_WRAPPER_END = self::TEMPLATE_PATH . 'fields/metabox/MetaBoxFieldWrapperEnd.inc.php';
    const META_BOX_CHECK_BOX_FORM_FIELD = self::TEMPLATE_PATH . 'fields/metabox/MetaBoxCheckBoxFormField.inc.php';
    const META_BOX_CHECK_BOX_INPUT = self::TEMPLATE_PATH . 'fields/metabox/MetaBoxCheckBoxFormFieldInput.inc.php';
}