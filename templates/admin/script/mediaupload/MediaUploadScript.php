<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 */

/**
 * Media upload script template
 *
 * Template to trigger wordpress media library for supported input elements.
 *
 * @since      1.0.0
 *
 * @see        /public/js/biws.mediaupöpader-{script-version}.js
 *
 * @package    BIWS\CPTBuilder\templates\admin\script
 * @subpackage mediaupload
 *
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\templates\admin\script\mediaupload;

use BIWS\CPTBuilder\views\FieldRenderObject;

if (!($render_object instanceof FieldRenderObject)) {
    die("Template {$__FILE__} requires FieldRenderObject");
}
?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        biws.customMediaUploader({
            containerSelector: '#<?= $render_object->getId() ?>',
            inputSelector: '#<?= $render_object->getId() ?>_media-selector',
            imageContainerSelector: '#<?= $render_object->getId() ?>_media-container',
            setImageLinkSelector: '#<?= $render_object->getId() ?>_media-button',
            removeImageLinkSelector: '#<?= $render_object->getId() ?>_media-remove',
        })
    })
</script>