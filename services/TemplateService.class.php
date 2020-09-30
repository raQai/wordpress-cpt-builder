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

use InvalidArgumentException;

/**
 * Service implementation to register templates
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder
 * @subpackage services
 */
class TemplateService extends Service
{
    /**
     * @since 1.0.0
     * 
     * @global string BIWS_CPT_BUILDER__PLUGIN_DIR_PATH
     *
     * @var string TEMPLATES_PATH The path to the template files.
     */
    private const TEMPLATES_PATH = BIWS_CPT_BUILDER__PLUGIN_DIR_PATH
        . 'templates/';

    /**
     * @since 1.0.0
     * @access private
     * 
     * @var array $templates Array of registered templates.
     */
    private array $templates = array();

    /**
     * Constructs the service and registers the default templates.
     * 
     * @since 1.0.0
     * @access protected
     */
    protected function __construct()
    {
        $this->_registerDefaults();
    }

    /**
     * Checks for valid arguments and registers the template if valid.
     * 
     * @since 1.0.0
     * @see TemplateService::_register()
     * @see TemplateType
     * @see RenderService
     * 
     * @param string $path     The template path.
     * @param string $type     May be derived by TemplateType or be a custom
     *                         string.
     * @param string $template The query string for the template
     * @param string $key      May be slug or id of a given template type.
     *                         Used to specify a template for a given slug/id.
     *                         Unique for each $template.
     *                         "default" will overwrite templates for the $type
     *                         globally in all registered views in the
     *                         RenderService using this template.
     * 
     * @throws InvalidArgumentException If an invalid parameter was given.
     */
    public function register(
        string $path,
        string $type,
        string $template,
        string $key
    ): void {
        if (empty($path)) {
            throw new InvalidArgumentException(
                "Template path cannot be empty."
            );
        }
        if (empty($type)) {
            throw new InvalidArgumentException(
                "Template type cannot be empty."
            );
        }
        if (empty($template)) {
            throw new InvalidArgumentException(
                "Template name cannot be empty."
            );
        }
        if (empty($key)) {
            throw new InvalidArgumentException(
                "Template key cannot be empty."
            );
        }
        if ($key === "default") {
            throw new InvalidArgumentException(
                "Cannot overwrite default templates. Please specify a key"
            );
        }

        $this->_register($path, $type, $template, $key);
    }

    /**
     * Allows users to query registered templates by $template
     * e.g. for include/require
     * 
     * @since 1.0.0
     * @see TemplateType
     * 
     * @param string $type
     *
     * @return string Returns the templates path for the given $key.
     *                Returns the default if the provided $key was not found or
     *                empty string if the $template is not registered at all.
     */
    public function getTemplatePath(
        string $type,
        string $template,
        string $key = "default"
    ): string {
        $templates = $this->templates;
        if (array_key_exists($type, $templates)) {
            $templates = $templates[$type];
            if (array_key_exists($template, $templates)) {
                $templates = $templates[$template];
                if (!empty($key) && array_key_exists($key, $templates)) {
                    return $templates[$key];
                }
                // ensure we get the default template if the $key does not exist
                if (array_key_exists("default", $templates)) {
                    return $templates["default"];
                }
            }
        }

        return "";
    }

    /**
     * Registers the template
     * 
     * @since 1.0.0
     * @access private
     * 
     * @param string $path     The template path.
     * @param string $type     May be derived by TemplateType or be a custom
     *                         string.
     * @param string $template The query string for the template
     * @param string $key      May be slug or id of a given template type.
     *                         Used to specify a template for a given slug/id.
     *                         Unique for each $template.
     *                         "default" will overwrite templates for the $type
     *                         globally in all registered views in the
     *                         RenderService using this template.
     */
    private function _register(
        string $path,
        string $type,
        string $template,
        string $key
    ): void {
        $this->templates[$type][$template][$key] = $path;
    }

    /**
     * Helps registering default templates
     * 
     * @since 1.0.0
     * @access private
     * 
     * @see TemplateService::_register()
     */
    private function _default(
        string $path,
        string $type,
        string $template
    ): void {
        $this->_register($path, $type, $template, "default");
    }

    /**
     * Registers all default tempaltes.
     * 
     * @since 1.0.0
     * @access private
     * 
     * @see TemplateService::_default()
     */
    private function _registerDefaults()
    {
        // default Taxonomy tempaltes
        $this->_default(
            self::TEMPLATES_PATH
                . 'admin/taxonomy/NewTaxonomyFormFieldStart.php',
            TemplateType::TAXONOMY,
            "start_new_form_field"
        );
        $this->_default(
            self::TEMPLATES_PATH
                . 'admin/taxonomy/NewTaxonomyFormFieldEnd.php',
            TemplateType::TAXONOMY,
            "end_new_form_field"
        );
        $this->_default(
            self::TEMPLATES_PATH
                . 'admin/taxonomy/EditTaxonomyFormFieldStart.php',
            TemplateType::TAXONOMY,
            "start_edit_form_field"
        );
        $this->_default(
            self::TEMPLATES_PATH
                . 'admin/taxonomy/EditTaxonomyFormFieldEnd.php',
            TemplateType::TAXONOMY,
            "end_edit_form_field"
        );
        $this->_default(
            self::TEMPLATES_PATH
                . 'formfields/FormFieldLabel.php',
            TemplateType::TAXONOMY,
            "form_field_label"
        );
        $this->_default(
            self::TEMPLATES_PATH
                . 'formfields/FormFieldInput.php',
            TemplateType::TAXONOMY,
            "form_field_input"
        );
        $this->_default(
            self::TEMPLATES_PATH
                . 'formfields/ImageFormFieldInput.php',
            TemplateType::TAXONOMY,
            "form_field_input_image"
        );

        // default media upload script
        $this->_default(
            self::TEMPLATES_PATH
                . 'admin/script/mediaupload/MediaUploadScript.php',
            TemplateType::TAXONOMY,
            "media_upload_script"
        );

        // default MetaBox tempaltes
        $this->_default(
            self::TEMPLATES_PATH
                . 'admin/script/metabox/StartMetaBoxScript.php',
            TemplateType::META_BOX,
            "script_start"
        );
        $this->_default(
            self::TEMPLATES_PATH
                . 'admin/script/metabox/EndMetaBoxScript.php',
            TemplateType::META_BOX,
            "script_end"
        );
        $this->_default(
            self::TEMPLATES_PATH
                . 'admin/script/metabox/parts/MetaBoxScriptBuilderPart.php',
            TemplateType::META_BOX,
            "script_builder_meta_box_start"
        );
        $this->_default(
            self::TEMPLATES_PATH
                . 'admin/script/metabox/parts/NumberScriptBuilderPart.php',
            TemplateType::META_BOX,
            "script_builder_number"
        );
        $this->_default(
            self::TEMPLATES_PATH
                . 'admin/script/metabox/parts/TextScriptBuilderPart.php',
            TemplateType::META_BOX,
            "script_builder_text"
        );
        $this->_default(
            self::TEMPLATES_PATH
                . 'admin/script/metabox/parts/CheckBoxScriptBuilderPart.php',
            TemplateType::META_BOX,
            "script_builder_check_box"
        );
        $this->_default(
            self::TEMPLATES_PATH
                . 'admin/script/metabox/parts/DateScriptBuilderPart.php',
            TemplateType::META_BOX,
            "script_builder_date"
        );
        $this->_default(
            self::TEMPLATES_PATH
                . 'admin/script/metabox/parts/TimeScriptBuilderPart.php',
            TemplateType::META_BOX,
            "script_builder_time"
        );
        $this->_default(
            self::TEMPLATES_PATH
                . 'admin/script/metabox/parts/EmailScriptBuilderPart.php',
            TemplateType::META_BOX,
            "script_builder_email"
        );
    }
}
