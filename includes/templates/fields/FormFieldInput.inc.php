<?php

namespace BIWS\EventManager\templates\fields;

defined('ABSPATH') or die('Nope!');

?>
<input <?php echo implode(" ", array_map(
            function ($key, $value) {
                return $key . '="' . htmlspecialchars($value) . '"';
            },
            array_keys($render_object->input_attributes),
            $render_object->input_attributes
        )); ?>>