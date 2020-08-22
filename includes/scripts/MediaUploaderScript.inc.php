<?php

namespace BIWS\EventManager\scripts\taxonomy\fields;

defined('ABSPATH') or die('Nope!');

?>
<script>
    jQuery(document).ready(function($) {
        $('#<?php echo $script_object->id; ?>_media-selector').biws_customMediaUploader({
            id: '#<?php echo $script_object->id; ?>',
            imageContainerId: '#<?php echo $script_object->id; ?>_media-container',
            setImageLinkId: '#<?php echo $script_object->id; ?>_media-button',
            removeImageLinkId: '#<?php echo $script_object->id; ?>_media-remove'
        });
    });
</script>