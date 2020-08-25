<?php

namespace BIWS\EventManager\scripts\taxonomy\fields;

defined('ABSPATH') or die('Nope!');

?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        biws.customMediaUploader({
            containerSelector: '<?php echo $script_object->containerSelector; ?>',
            inputSelector: '<?php echo $script_object->inputSelector; ?>',
            imageContainerSelector: '<?php echo $script_object->imageContainerSelector; ?>',
            setImageLinkSelector: '<?php echo $script_object->setImageLinkSelector; ?>',
            removeImageLinkSelector: '<?php echo $script_object->removeImageLinkSelector; ?>',
        })
    })
</script>