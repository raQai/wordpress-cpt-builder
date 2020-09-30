<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 */

/**
 * Template for form field labels
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder\templates
 * @subpackage formfields
 *
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\templates\formfields;

use BIWS\CPTBuilder\views\formfields\FormFieldLabel;

if (!($render_object instanceof FormFieldLabel)) {
    die("Template {$__FILE__} requires FormFieldLabel");
}
?>
<label <?= implode(" ", array_map(
            function ($key, $value) {
                if ($value === "") {
                    return $key;
                }
                return $key . '="' . htmlspecialchars($value) . '"';
            },
            array_keys($render_object->attributes),
            $render_object->attributes
        )) ?>><?= $render_object->getLabel() ?></label>