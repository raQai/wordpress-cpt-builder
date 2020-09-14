<?php

namespace BIWS\CPTBuilder\scripts;

defined('ABSPATH') or die('Nope!');

?>
<script>
    document.addEventListener('DOMContentLoaded', () =>
        biws.metaboxes.builder('<?php echo $script_object->post_slug; ?>-plugin')
        <?php
        foreach ($script_object->meta_boxes as $meta_box) {
            echo $meta_box->renderForScript();
        }
        ?>
        .build()
    );
</script>