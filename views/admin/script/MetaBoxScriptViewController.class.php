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

use BIWS\CPTBuilder\models\CustomPostType;
use BIWS\CPTBuilder\models\fields\FieldType;
use BIWS\CPTBuilder\models\fields\IField;
use BIWS\CPTBuilder\models\MetaBox;
use BIWS\CPTBuilder\services\TemplateService;
use BIWS\CPTBuilder\services\TemplateType;
use BIWS\CPTBuilder\views\FieldRenderObject;
use BIWS\CPTBuilder\views\IViewController;
use BIWS\CPTBuilder\views\RenderObjectWrapper;
use InvalidArgumentException;

/**
 * Meta box script view controller implementation
 * 
 * Provides the necessarry functionality to render the meta box view
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder\views\admin
 * @subpackage script
 */
class MetaBoxScriptViewController implements IViewController
{
    /**
     * The current metaboxes script version to use.
     * 
     * @since 1.0.0
     * @access private
     *
     * @var string JS_METABOXES_VERSION The script version.
     */
    private const JS_METABOXES_VERSION = '0.1.4';

    /**
     * The metaboxes script url to register and enqueue.
     *
     * @since 1.0.0
     * @access private
     * 
     * @global string BIWS_CPT_BUILDER__PLUGIN_DIR_URL
     *
     * @var string JS_METABOXES The metaboxes script url.
     */
    private const JS_METABOXES = BIWS_CPT_BUILDER__PLUGIN_DIR_URL
        . 'public/js/biws.metaboxes-'
        . self::JS_METABOXES_VERSION
        . '.js';

    /**
     * @since 1.0.0
     * @access private
     * 
     * @var CustomPostType $cpt The custom post type to render the meta boxes for.
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
     * @param CustomPostType $cpt The custom post type to render the meta boxes
     *                            for.
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
     * @link https://developer.wordpress.org/reference/hooks/enqueue_block_editor_assets/
     */
    public function init(): void
    {
        add_action('init', array($this, 'registerScript'));
        add_action(
            'enqueue_block_editor_assets',
            array($this, 'enqueueScript')
        );
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
            'enqueue_block_editor_assets',
            array($this, 'enqueueScript')
        );
    }

    /**
     * Registers the metaboxes script if it os not already registered.
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
        if (wp_script_is('biws-metaboxes')) {
            return;
        }

        wp_register_script(
            'biws-metaboxes',
            self::JS_METABOXES,
            array('wp-plugins', 'wp-edit-post', 'wp-element', 'wp-components'),
            self::JS_METABOXES_VERSION,
            true
        );
    }

    /**
     * Enqueues the script if it is in a valid location.
     * 
     * @since 1.0.0
     * 
     * @see MetaBoxScriptViewController::isValidScriptLocation()
     * @see wp_enqueue_script()
     * 
     * @link https://developer.wordpress.org/reference/functions/wp_enqueue_script/
     */
    public function enqueueScript()
    {
        if (!$this->isValidScriptLocation()) {
            return;
        }
        wp_enqueue_script('biws-metaboxes');
    }

    /**
     * Returns all meta boxes for the provided $cpt.
     * 
     * @since 1.0.0
     * 
     * @see MetaBoxScriptViewController::$cpt
     * 
     * @return MetaBox[] The meta boxes of the $cpt.
     */
    public function getMetaBoxes(): array
    {
        return $this->cpt->getMetaBoxes();
    }


    /**
     * Determines whether the meta boxes script is called on a valid location
     * to avoid including the script unnecessarily.
     * 
     * @since 1.0.0
     * 
     * @global string $pagenow
     * @global string $typenow
     *
     * @return bool true if $pagenow is a post/post-new page and if the $cpt
     *              slug equals $typenow, false otherwise.
     */
    public function isValidScriptLocation(): bool
    {
        global $pagenow, $typenow;

        if (!in_array($pagenow, array('post.php', 'post-new.php'))) {
            return false;
        }

        if ($this->cpt->getSlug() !== $typenow) {
            return false;
        }

        return true;
    }

    /**
     * Returns the for the script necessary render object script wrapper to
     * contain the java script parts.
     * 
     * @since 1.0.0
     * 
     * @return RenderObjectWrapper The render object wrapper.
     */
    public function getMetaBoxScript(): RenderObjectWrapper
    {
        $post_slug = $this->cpt->getSlug();
        $render_object = new RenderObjectWrapper(
            $this->template_service->getTemplatePath(
                TemplateType::META_BOX,
                "script_start",
                $post_slug
            ),
            $this->template_service->getTemplatePath(
                TemplateType::META_BOX,
                "script_end",
                $post_slug
            )
        );
        $render_object->post_slug = $post_slug;
        return $render_object;
    }

    /**
     * Returns the for the script necessary render object script part wrapper to
     * contain the field parts.
     * 
     * @since 1.0.0
     * 
     * @return RenderObjectWrapper The render object wrapper.
     */
    public function getMetaBoxScriptPart(MetaBox $meta_box): RenderObjectWrapper
    {
        $meta_box_id = $meta_box->getId();
        $render_object = new RenderObjectWrapper(
            $this->template_service->getTemplatePath(
                TemplateType::META_BOX,
                "script_builder_meta_box_start",
                $meta_box_id
            ),
            $this->template_service->getTemplatePath(
                TemplateType::META_BOX,
                "script_builder_meta_box_end",
                $meta_box_id
            )
        );
        $render_object->meta_box_id = $meta_box_id;
        $render_object->meta_box_label = $meta_box->getLabel();
        return $render_object;
    }

    /**
     * Returns the for the script necessary render object script part to render
     * the fields.
     *
     * @since 1.0.0
     * 
     * @return FieldRenderObject The field rende robject.
     */
    public function getFieldScriptPart(IField $field): FieldRenderObject
    {
        $field_template = $this->getFieldTemplateName($field);
        return new FieldRenderObject(
            $field,
            $this->template_service->getTemplatePath(
                TemplateType::META_BOX,
                $field_template,
                $field->getId()
            )
        );
    }

    /**
     * Helps obtaining the field template name based on the FieldType.
     * 
     * @since 1.0.0
     * @access private
     *
     * @see FieldType
     *
     * @param IField $field The field for which to obtain the template name.
     * 
     * @return string The field template name
     * 
     * @throws InvalidArgumentException If a not supported field type has been
     *                                  provided.
     */
    private function getFieldTemplateName(IField $field): string
    {
        $template_name = "script_builder_";
        $fieldType = $field->getType();
        switch ($fieldType) {
            case FieldType::NUMBER:
                $template_name .= "number";
                break;
            case FieldType::TEXT:
                $template_name .= "text";
                break;
            case FieldType::CHECKBOX:
                $template_name .= "check_box";
                break;
            case FieldType::DATE:
                $template_name .= "date";
                break;
            case FieldType::TIME:
                $template_name .= "time";
                break;
            case FieldType::EMAIL:
                $template_name .= "email";
                break;
            default:
                throw new InvalidArgumentException(
                    "Field type {$fieldType} not supported."
                );
        }
        return $template_name;
    }
}
