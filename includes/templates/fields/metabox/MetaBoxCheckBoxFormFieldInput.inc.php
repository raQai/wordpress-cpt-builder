<?php

namespace BIWS\EventManager\templates\fields\metabox;

defined('ABSPATH') or die('Nope!');

?>
<span class="components-checkbox-control__input-container">
    <input <?php echo implode(" ", array_map(
                function ($key, $value) {
                    return $key . '="' . htmlspecialchars($value) . '"';
                },
                array_keys($render_object->input_attributes),
                $render_object->input_attributes
            )); ?>>
</span>