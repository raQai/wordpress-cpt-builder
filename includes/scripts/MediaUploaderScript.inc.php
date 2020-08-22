<?php

namespace BIWS\EventManager\scripts\taxonomy\fields;

defined('ABSPATH') or die('Nope!');

?>
<script>
    jQuery(document).ready(function($) {
        $('#<?php echo $script_object->id; ?>_media-selector').biws().customMediaUploader({
            inputSelector: $("#<?php echo $script_object->id; ?>"),
            imageContainerSelector: $('#<?php echo $script_object->id; ?>_media-container'),
            setImageLinkSelector: $('#<?php echo $script_object->id; ?>_media-button'),
            removeImageLinkSelector: $('#<?php echo $script_object->id; ?>_media-remove')
        });
    });
</script>