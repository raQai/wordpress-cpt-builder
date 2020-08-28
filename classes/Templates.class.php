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
}