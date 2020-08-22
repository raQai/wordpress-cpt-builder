<?php

namespace BIWS\EventManager\templates\taxonomy\fields;

defined('ABSPATH') or die('Nope!');

?>
<tr id="<?php echo $render_object->id; ?>_media-selector" class="<?php echo implode(" ", $render_object->classes); ?>">
    <th scope="row">
        <label <?php echo implode(" ", array_map(
                    function ($key, $value) {
                        return $key . '="' . htmlspecialchars($value) . '"';
                    },
                    array_keys($render_object->label_attributes),
                    $render_object->label_attributes
                ));
                ?>><?php echo $render_object->label; ?></label>
    </th>
    <td>
        <input <?php echo implode(" ", array_map(
                    function ($key, $value) {
                        return $key . '="' . htmlspecialchars($value) . '"';
                    },
                    array_keys($render_object->input_attributes),
                    $render_object->input_attributes
                )); ?> class="<?php echo $render_object->id; ?>_media-url">
        <div id="<?php echo $render_object->id; ?>_media-container">
            <?php if ($render_object->has_image_set) echo $render_object->attachment_image; ?>
        </div>
        <input type="button" class="button button-secondary button-small <?php echo $render_object->id; ?>_media-button <?php if ($render_object->has_image_set) echo 'hidden'; ?>" id="<?php echo $render_object->id; ?>_media-button" name="<?php echo $render_object->id; ?>_media-button" value="<?php _e('Set Image'); ?>" />
        <input type="button" class="button button-secondary button-small button-link-delete <?php echo $render_object->id; ?>_media-remove <?php if (!$render_object->has_image_set) echo 'hidden'; ?>" id="<?php echo $render_object->id; ?>_media-remove" name="<?php echo $render_object->id; ?>_media-remove" value="<?php _e('Remove Image'); ?>" />
    </td>
</tr>