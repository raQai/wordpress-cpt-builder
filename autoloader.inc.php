<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 */

/**
 * Autoloader for this plugin
 *
 * @since     1.0.0
 * 
 * @package   BIWS\CPTBuilder
 * 
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder;

defined('ABSPATH') or die('Nope!');

if (!defined('WPINC')) {
    die;
}

spl_autoload_register(function ($className) {
    if (strpos($className, __NAMESPACE__) !== 0) {
        return;
    }
    $className = str_replace(__NAMESPACE__, "", $className);
    $path = preg_replace(
        '#\\\+|\/+#',
        '!',
        BIWS_CPT_BUILDER__PLUGIN_DIR_PATH . $className . '.class.php'
    );
    require_once preg_replace('#!+#', DIRECTORY_SEPARATOR, $path);
});
