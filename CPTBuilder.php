<?php

/**
 * BIWS CPT Builder plugin
 *
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * Requirements not tested below specified versions.
 * 
 * @package   BIWS\CPTBuilder
 * @author    Patrick Bogdan
 * @copyright 2020 Patrick Bogdan
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 * @since     1.0.0
 * 
 * @wordpress-plugin
 * Plugin Name:       BIWS CPT Builder
 * Plugin URI:        https://github.com/raQai/wordpress-cpt-builder
 * Description:       Builder Plugin to generate custom post types
 * Requires at least: 5.5
 * Requires PHP:      7.4
 * Author:            Patrick Bogdan
 * Author URI:        https://github.com/raQai
 * Version:           1.0.0
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace BIWS\CPTBuilder;

defined('ABSPATH') or die('Nope!');

if (!defined('WPINC')) {
    die;
}

define('BIWS_CPT_BUILDER__PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
define('BIWS_CPT_BUILDER__PLUGIN_DIR_URL', plugin_dir_url(__FILE__));

require_once BIWS_CPT_BUILDER__PLUGIN_DIR_PATH . 'autoloader.inc.php';
