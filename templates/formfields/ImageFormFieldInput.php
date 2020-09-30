<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 */

/**
 * Template for image inputs in form fields
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

use BIWS\CPTBuilder\views\formfields\FormFieldInput;

if (!($render_object instanceof FormFieldInput)) {
    die("Template {$__FILE__} requires FormFieldInput");
}
?>
<input <?= implode(" ", array_map(
            function ($key, $value) {
                if ($value === "") {
                    return $key;
                }
                return $key . '="' . htmlspecialchars($value) . '"';
            },
            array_keys($render_object->attributes),
            $render_object->attributes
        )) ?>>
<div id="<?= $render_object->getId() ?>_media-container" style="cursor:pointer">
    <?= $render_object->image_html ?>
</div>
<button type="button" class="button button-secondary button-small <?= $render_object->getId() ?>_media-button <?= empty($render_object->image_html) ? '' : 'hidden' ?>" id="<?= $render_object->getId(); ?>_media-button" name="<?= $render_object->getId() ?>_media-button"><?php _e('Set Image'); ?></button>
<button type="button" class="button button-secondary button-small button-link-delete <?= $render_object->getId() ?>_media-remove <?= empty($render_object->image_html) ? 'hidden' : '' ?>" id="<?= $render_object->getId() ?>_media-remove" name="<?= $render_object->getId() ?>_media-remove"><?php _e('Remove Image'); ?></button>