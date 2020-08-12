<?php

/**
 * Plugin Name: Event Manager
 * Description: Simple EventManager Plugin
 * Author: Patrick Bogdan
 * Version: 0.0.1
 */

namespace BIWS;

use BIWS\CPT\Builder\CustomPostTypeBuilder;

defined('ABSPATH') or die('Nope!');

if (!defined('WPINC')) {
    die;
}
 
// FIXME use autoloader
$plugin_dir_path = plugin_dir_path(__FILE__);

if (!class_exists('\BIWS\CPT\CustomPostType')) {
    require $plugin_dir_path . 'core/CustomPostType.class.php';
}
if (!class_exists('\BIWS\CPT\Builder\CustomPostTypeBuilder')) {
    require $plugin_dir_path . 'core/CustomPostTypeBuilder.class.php';
}

CustomPostTypeBuilder::create()
    ->slug("events")
    ->args(
        array(
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
        )
    )
    ->buildAndInit();