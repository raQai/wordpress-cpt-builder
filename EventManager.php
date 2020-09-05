<?php

/**
 * Plugin Name: Event Manager
 * Description: Simple EventManager Plugin
 * Author: Patrick Bogdan
 * Version: 0.7.1
 */

namespace BIWS\EventManager;

use BIWS\EventManager\cpt\CustomPostTypeBuilder;
use BIWS\EventManager\fields\FieldType;
use BIWS\EventManager\metabox\MetaBoxBuilder;
use BIWS\EventManager\taxonomy\TaxonomyBuilder;
use DateTime;

defined('ABSPATH') or die('Nope!');

if (!defined('WPINC')) {
    die;
}

define('BIWS_EventManager__PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
define('BIWS_EventManager__PLUGIN_DIR_URL', plugin_dir_url(__FILE__));

include BIWS_EventManager__PLUGIN_DIR_PATH . 'includes/autoloader.inc.php';

$registration_args = array(
    'label' => 'Anmeldungen',
    'description' => 'Anmeldungen',
    'labels' => array(
        'name' => 'Anmeldungen',
        'singular_name' => 'Anmeldung',
    ),
    'supports' => array('title', 'custom-fields'),
    'menu_icon' => 'dashicons-tickets',
    'public' => false,
    'show_ui' => true,
    'show_in_menu' => 'edit.php?post_type=events',
    'show_in_rest' => false,
    'exclude_from_search' => true,
);

$registration_status_taxonomy = TaxonomyBuilder::create('biws__status_tax')
    ->args(array(
        'labels' => array(
            'name' => 'Anmeldestatus',
            'singular_name' => 'Anmeldestatus',
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

$registration_cpt = CustomPostTypeBuilder::create('biws__reg_cpt')
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

$category_taxonomy = TaxonomyBuilder::create('biws__cat_tax')
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

$contact_taxonomy = TaxonomyBuilder::create('biws__contact_tax')
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
            'show_in_rest' => true,
        )
    )
    ->addField(FieldType::TEXT, 'phone', 'Telefonnummer', true, true, '+49 (0) 111 - 222 333 44')
    ->addField(FieldType::EMAIL, 'email', 'E-Mail', true, true, 'name@domain.de')
    ->build();

$location_taxonomy = TaxonomyBuilder::create('biws__location_tax')
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
            'show_in_rest' => true,
        )
    )
    ->addField(FieldType::TEXT, 'building', 'Gebäude', false, true)
    ->addField(FieldType::TEXT, 'street', 'Straße', false, true)
    ->addField(FieldType::TEXT, 'street_nr', 'Hausnummer', false, true)
    ->addField(FieldType::TEXT, 'zip', 'PLZ', false, true)
    ->addField(FieldType::TEXT, 'location', 'Ort', false, true)
    ->build();

$datetime_meta_box = MetaBoxBuilder::create('biws__datetime_meta')
    ->title('Zeitangaben')
    ->addField(FieldType::DATE, 'datetime__start_date', 'Anfangsdatum', true)
    ->addField(FieldType::DATE, 'datetime__end_date', 'Enddatum', true)
    ->addField(FieldType::TIME, 'datetime__start_time', 'Uhrzeit von', true)
    ->addField(FieldType::TIME, 'datetime__end_time', 'Uhrzeit bis', true)
    ->build();

$registration_meta_box = MetaBoxBuilder::create("biws__reg_meta")
    ->title('Anmeldung')
    ->addField(FieldType::CHECKBOX, 'registration__enable', 'Anmeldeformular anzeigen', true)
    ->addField(FieldType::DATE, 'registration__enddate', 'Anmeldung bis', true)
    ->addField(FieldType::CHECKBOX, 'registration__email_enabled', 'Email-Benachrichtigung', true)
    ->addField(FieldType::EMAIL, 'registration__email', 'Empfänger', false, 'name@domain.de')
    ->build();

$today_date = new DateTime('today');
$today_tc = $today_date->format('Y-m-d');

$events_rest_params = array(
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key' => 'datetime__start_date',
            'value' => "",
            'compare' => '=',
        ),
        array(
            'key' => 'datetime__start_date',
            'compare' => 'NOT EXISTS',
        ),
        'start_date_clause' => array(
            'key' => 'datetime__start_date',
            'value' => $today_tc,
            'compare' => '>=',
        ),
        'end_date_clause' => array(
            'key' => 'datetime__end_date',
            'value' => $today_tc,
            'compare' => '>=',
        ),
    ),
    'orderby' => array(
        'start_date_clause' => 'ASC',
        'end_date_clause' => 'ASC',
    ),
);

CustomPostTypeBuilder::create("events")
    ->args($events_args)
    ->addTaxonomy($category_taxonomy)
    ->addTaxonomy($contact_taxonomy)
    ->addTaxonomy($location_taxonomy)
    ->addMetaBox($datetime_meta_box)
    ->addMetaBox($registration_meta_box)
    ->addCPT($registration_cpt)
    ->unsetColumns('date')
    ->setRestRoute('biws/v1', 'biws__events', $events_rest_params)
    ->buildAndInit();

add_action('rest_api_init', function () {
    header("Access-Control-Allow-Origin: *");
    /*
        // handle individually for each project
        $origin = get_http_origin();
        $allowed_origins = ['localhost:5000'];
        if ($origin && in_array($origin, $allowed_origins)) {
            // see https://stackoverflow.com/questions/25702061/enable-cors-on-json-api-wordpress
            header('Access-Control-Allow-Methods: GET');
            header('Access-Control-Allow-Origin: ' . esc_url_raw($origin));
        }
        */
}, 15);
