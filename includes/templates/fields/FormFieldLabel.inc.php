<?php

namespace BIWS\CPTBuilder\templates\fields;

defined('ABSPATH') or die('Nope!');

?>
<label <?php echo implode(" ", array_map(
            function ($key, $value) {
                return $key . '="' . htmlspecialchars($value) . '"';
            },
            array_keys($render_object->label_attributes),
            $render_object->label_attributes
        ));
        ?>><?php echo $render_object->label; ?></label>