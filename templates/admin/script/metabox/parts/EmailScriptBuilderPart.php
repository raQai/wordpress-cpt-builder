<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 */

/**
 * Email field meta box script part
 * 
 * Template to display meta boxes in the gutenberg editor for registered
 * custom post types.
 *
 * @since      1.0.0
 *
 * @see        /public/js/biws.metaboxes-{script-version}.js
 *
 * @package    BIWS\CPTBuilder\templates\admin\script\metabox
 * @subpackage parts
 *
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\templates\admin\script\metabox\parts;

use BIWS\CPTBuilder\views\FieldRenderObject;

if (!($render_object instanceof FieldRenderObject)) {
    die("Template {$__FILE__} requires FieldRenderObject");
}
?>
.addEmailField('<?= $render_object->getId() ?>', '<?= $render_object->getLabel() ?>', '<?= $render_object->getPlaceholder() ?>')