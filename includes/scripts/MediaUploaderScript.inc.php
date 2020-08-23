<?php

namespace BIWS\EventManager\scripts\taxonomy\fields;

defined('ABSPATH') or die('Nope!');

?>
<script>
    jQuery(document).ready(function($) {
        $('<?php echo $script_object->containerSelector; ?>').biws().customMediaUploader({
            inputSelector: $('<?php echo $script_object->inputSelector; ?>'),
            imageContainerSelector: $('<?php echo $script_object->imageContainerSelector; ?>'),
            setImageLinkSelector: $('<?php echo $script_object->setImageLinkSelector; ?>'),
            removeImageLinkSelector: $('<?php echo $script_object->removeImageLinkSelector; ?>')
        });
    });
</script>