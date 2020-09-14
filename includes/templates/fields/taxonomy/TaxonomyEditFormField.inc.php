<?php

namespace BIWS\CPTBuilder\templates\fields\taxonomy;

defined('ABSPATH') or die('Nope!');

?>
<tr <?php echo implode(" ", array_map(
        function ($key, $value) {
            return $key . '="' . htmlspecialchars($value) . '"';
        },
        array_keys($render_object->container_attributes),
        $render_object->container_attributes
    )); ?>>
    <th scope="row">
        <?php include $render_object->label_template; ?>
    </th>
    <td>
        <?php include $render_object->input_template; ?>
    </td>
</tr>