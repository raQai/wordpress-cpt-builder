<?php

/**
 * Plugin Name: Event Manager
 * Description: Simple EventManager Plugin
 * Author: Patrick Bogdan
 * Version: 0.0.1
 */

namespace BIWS\EventManager;

defined('ABSPATH') or die('Nope!');

if (!defined('WPINC')) {
    die;
}

define('BIWS_EventManager__PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ));

include BIWS_EventManager__PLUGIN_DIR_PATH . 'includes/autoloader.inc.php';

cpt\CustomPostTypeBuilder::create()
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
