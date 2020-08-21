<?php

namespace BIWS\EventManager\templates\taxonomy\fields;

defined('ABSPATH') or die('Nope!');

?>
<div class="<?php echo implode(" ", $render_object->classes); ?>">
    <label <?php echo implode(" ", array_map(
                function ($key, $value) {
                    return $key . '="' . htmlspecialchars($value) . '"';
                },
                array_keys($render_object->label_attributes),
                $render_object->label_attributes
            )); ?>><?php echo $render_object->label; ?></label>
    <input <?php echo implode(" ", array_map(
                function ($key, $value) {
                    return $key . '="' . htmlspecialchars($value) . '"';
                },
                array_keys($render_object->input_attributes),
                $render_object->input_attributes
            )); ?>>
</div>