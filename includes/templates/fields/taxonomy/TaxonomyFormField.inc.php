<?php

namespace BIWS\EventManager\templates\fields\taxonomy;

defined('ABSPATH') or die('Nope!');

?>
<div <?php echo implode(" ", array_map(
            function ($key, $value) {
                return $key . '="' . htmlspecialchars($value) . '"';
            },
            array_keys($render_object->container_attributes),
            $render_object->container_attributes
        )); ?>>
    <?php include $render_object->label_template; ?>
    <?php include $render_object->input_template; ?>
</div>