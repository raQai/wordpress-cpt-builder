<?php

/**
 * Plugin Name: Event Manager
 * Description: Simple EventManager Plugin
 * Author: Patrick Bogdan
 * Version: 0.6.2
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
    'label' => 'Veranstaltungen',
    'description' => 'Taki Vreanstaltungen',
    'labels' => array(
        'name' => 'Veranstaltungen',
        'singular_name' => 'Veranstaltung',
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

$category_taxonomy = TaxonomyBuilder::create('biws__category')
    ->args(
        array(
            'hierarchical' => true,
            'labels' => array(
                'name' => 'Kategorien',
                'singular_name' => 'Kategorie',
                'search_items' =>  'Kategorie suchen',
                'all_items' => 'Alle Kategorien',
                'parent_item' => 'Übergeordnete Kategorie',
                'parent_item_colon' => 'Übergeordnete Kategorie:',
                'edit_item' => 'Kategorie bearbeiten',
                'update_item' => 'Kategorie aktualisieren',
                'add_new_item' => 'Neue Kategorie hinzufügen',
                'new_item_name' => 'Neuer Kategoriename',
            ),
            'show_ui' => true,
            'show_admin_column' => true,
            'show_tag_cloud' => false,
            'show_in_rest' => true,
            'query_var' => true,
        )
    )
    ->build();

$contact_taxonomy = TaxonomyBuilder::create('biws__contact')
    ->args(
        array(
            'hierarchical' => true,
            'labels' => array(
                'name' => 'Ansprechpartner*In',
                'singular_name' => 'Ansprechpartner*In',
                'search_items' =>  'Ansprechpartner*In suchen',
                'all_items' => 'Alle Ansprechpartner*Innen',
                'parent_item' => 'Übergeordnete/r Ansprechpartner*In',
                'parent_item_colon' => 'Übergeordnete/r Ansprechpartner*In:',
                'edit_item' => 'Ansprechpartner*In bearbeiten',
                'update_item' => 'Ansprechpartner*In aktualisieren',
                'add_new_item' => 'Neue/n Ansprechpartner*In hinzufügen',
                'new_item_name' => 'Neuer Ansprechpartner*Innen-Name',
            ),
            'show_ui' => true,
            'show_admin_column' => true,
            'public' => false,
            'show_tag_cloud' => false,
            'show_in_rest' => false,
        )
    )
    ->addField(FieldType::TEXT, 'phone', 'Telefonnummer', true, true, '+49 (0) 111 - 222 333 44')
    ->addField(FieldType::EMAIL, 'email', 'E-Mail', true, true, 'name@domain.de')
    ->build();

$location_taxonomy = TaxonomyBuilder::create('biws__location')
    ->args(
        array(
            'hierarchical' => true,
            'labels' => array(
                'name' => 'Orte',
                'singular_name' => 'Ort',
                'search_items' =>  'Ort suchen',
                'all_items' => 'Alle Orte',
                'parent_item' => 'Übergeordneter Ort',
                'parent_item_colon' => 'Übergeordneter Ort:',
                'edit_item' => 'Ort bearbeiten',
                'update_item' => 'Ort aktualisieren',
                'add_new_item' => 'Neuen Ort hinzufügen',
                'new_item_name' => 'Neuer Ortname',
            ),
            'show_ui' => true,
            'show_admin_column' => true,
            'public' => false,
            'show_tag_cloud' => false,
            'show_in_rest' => false,
        )
    )
    ->addField(FieldType::TEXT, 'building', 'Gebäude', false, true)
    ->addField(FieldType::TEXT, 'street', 'Straße', false, true)
    ->addField(FieldType::TEXT, 'street_nr', 'Hausnummer', false, true)
    ->addField(FieldType::TEXT, 'zip', 'PLZ', false, true)
    ->addField(FieldType::TEXT, 'location', 'Ort', false, true)
    ->build();

$datetime_meta_box = MetaBoxBuilder::create("biws__datetime")
    ->title('Zeitangaben')
    ->addField(FieldType::DATE, 'start_date', 'Anfangsdatum', true)
    ->addField(FieldType::DATE, 'end_date', 'Enddatum', true)
    ->addField(FieldType::TIME, 'start_time', 'Uhrzeit von', true)
    ->addField(FieldType::TIME, 'end_time', 'Uhrzeit bis', true)
    ->build();

CustomPostTypeBuilder::create("events")
    ->args($events_args)
    ->addTaxonomy($category_taxonomy)
    ->addTaxonomy($contact_taxonomy)
    ->addTaxonomy($location_taxonomy)
    ->addMetaBox($datetime_meta_box)
    ->addCPT($registration_cpt)
    ->unsetColumns('date')
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
