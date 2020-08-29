<?php

/**
 * Plugin Name: Event Manager
 * Description: Simple EventManager Plugin
 * Author: Patrick Bogdan
 * Version: 0.5.0
 */

namespace BIWS\EventManager;

use BIWS\EventManager\cpt\CustomPostTypeBuilder;
use BIWS\EventManager\fields\FieldType;
use BIWS\EventManager\metabox\MetaBoxBuilder;
use BIWS\EventManager\taxonomy\TaxonomyBuilder;

defined('ABSPATH') or die('Nope!');

if (!defined('WPINC')) {
    die;
}

define('BIWS_EventManager__PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
define('BIWS_EventManager__PLUGIN_DIR_URL', plugin_dir_url(__FILE__));

include BIWS_EventManager__PLUGIN_DIR_PATH . 'includes/autoloader.inc.php';

$registration_args = array(
    'label' => __('Anmeldungen', 'biws-textdomain'),
    'description' => __('Anmeldungen', 'biws-textdomain'),
    'labels' => array(
        'name' => __('Anmeldungen', 'biws-textdomain'),
        'singular_name' => __('Anmeldung', 'biws-textdomain'),
    ),
    'supports' => array('title', 'custom-fields'),
    'menu_icon' => 'dashicons-tickets',
    'public' => false,
    'show_ui' => true,
    'show_in_menu' => 'edit.php?post_type=events',
    'show_in_rest' => false,
    'exclude_from_search' => true,
);

$registration_status_taxonomy = TaxonomyBuilder::create("biws__registration_status")
    ->args(array(
        'labels' => array(
            'name' => _x('Anmeldestatus', 'taxonomy general name'),
            'singular_name' => _x('Anmeldestatus', 'taxonomy singular name'),
        ),
        'hierarchical' => true,
        'description' => 'Anmeldestatus',
        'public' => false,
        'show_admin_column' => true,
        'show_tag_cloud' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => false,
        'query_var' => false,
    ))
    ->build();

$registration_cpt = CustomPostTypeBuilder::create('biws__registration')
    ->args($registration_args)
    ->addTaxonomy($registration_status_taxonomy)
    ->buildAndInit();

$events_args = array(
    'label' => __('Events', 'biws-textdomain'),
    'description' => __('Turniere und Events', 'biws-textdomain'),
    'labels' => array(
        'name' => __('Events', 'biws-textdomain'),
        'singular_name' => __('Event', 'biws-textdomain'),
    ),
    'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
    'has_archive' => true,
    'menu_icon' => 'dashicons-calendar-alt',
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_rest' => true,
    'menu_position' => 5,
    'exclude_from_search' => false,
    'rewrite' => array('slug' => "events"),
);

$tags_taxonomy = TaxonomyBuilder::create("taxonomyExample")
    ->args(
        array(
            'hierarchical' => true,
            'labels' => array(
                'name' => _x("tags", 'taxonomy general name'),
                'singular_name' => _x("tag", 'taxonomy singular name'),
                'search_items' =>  __('Search ' . "tag"),
                'all_items' => __('All ' . "tags"),
                'parent_item' => __('Parent ' . "tag"),
                'parent_item_colon' => __('Parent ' . "tag" . ':'),
                'edit_item' => __('Edit ' . "tag"),
                'update_item' => __('Update ' . "tag"),
                'add_new_item' => __('Add New ' . "tag"),
                'new_item_name' => __('New ' . "tag" . ' Name'),
                'menu_name' => __("tags")
            ),
            'show_ui' => true,
            'show_admin_column' => true,
            'show_tag_cloud' => false,
            'show_in_rest' => true,
            'query_var' => true,
            'rewrite' => array('slug' => "taxample"),
        )
    )
    ->addField(FieldType::NUMBER, 'order', 'Order#', true)
    ->addField(FieldType::TEXT, 'texttest', 'TextTest', true, 'text placeholder')
    ->addField(FieldType::COLOR, 'testcolor', 'ColorTest', false, '#a1a1b0')
    ->addField(FieldType::IMAGE, 'testimage', 'TestImage')
    ->build();

$test_meta_box = MetaBoxBuilder::create("testbox")
    ->title("test meta box title")
    ->addField(FieldType::NUMBER, 'testmeta_number', 'number test')
    ->addField(FieldType::TEXT, 'texttest', 'TextTest', 'text placeholder')
    ->addField(FieldType::CHECKBOX, 'checkbox_test_0', 'CheckBoxTest inactive')
    ->addField(FieldType::DATE, 'datetest', 'DateTest')
    ->addField(FieldType::TIME, 'timetest', 'TimeTest')
    ->build();

CustomPostTypeBuilder::create("events")
    ->args($events_args)
    ->addTaxonomy($tags_taxonomy)
    ->addMetaBox($test_meta_box)
    ->addCPT($registration_cpt)
    ->buildAndInit();

/**
 * DEV_MODE_SETTINGS
 */
function overwrite_error_log()
{
    ini_set('error_log', '/dev/stdout'); // phpcs:ignore
}
add_action('init', function () {
    overwrite_error_log();
}, 10);

if (!function_exists('write_log')) {
    function write_log($log)
    {
        if (is_array($log) || is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
    }
}
