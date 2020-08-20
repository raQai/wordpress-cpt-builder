<?php

/**
 * Plugin Name: Event Manager
 * Description: Simple EventManager Plugin
 * Author: Patrick Bogdan
 * Version: 0.2.2
 */

namespace BIWS\EventManager;

defined('ABSPATH') or die('Nope!');

if (!defined('WPINC')) {
    die;
}

define('BIWS_EventManager__PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));

include BIWS_EventManager__PLUGIN_DIR_PATH . 'includes/autoloader.inc.php';

$events_args = array(
    'label' => __('Events', 'biws-textdomain'),
    'description' => __('Turniere und Events', 'biws-textdomain'),
    'labels' => array(
        'name' => __('Events', 'biws-textdomain'),
        'singular_name' => __('Event', 'biws-textdomain'),
    ),
    'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
    'hierarchical' => true,
    'has_archive' => true,
    'capability_type' => 'page',
    'menu_icon' => 'dashicons-calendar-alt',
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_rest' => true,
    'menu_position' => 5,
    'exclude_from_search' => true,
    'rewrite' => array('slug' => "events"),
);


$tags_taxonomy = taxonomy\TaxonomyBuilder::create("taxonomyExample")
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
    ->addField(taxonomy\fields\FieldType::NUMBER, 'order', 'Order#', true)
    ->addField(taxonomy\fields\FieldType::TEXT, 'texttest', 'TextTest', true, 'text placeholder')
    ->build();

$testMetaBox = metabox\MetaBoxBuilder::create("testbox")
    ->title("test meta box title")
    ->context("side")
    ->priority("high")
    ->build();

cpt\CustomPostTypeBuilder::create("events")
    ->args($events_args)
    ->addTaxonomy($tags_taxonomy)
    ->addMetaBox($testMetaBox)
    ->buildAndInit();
