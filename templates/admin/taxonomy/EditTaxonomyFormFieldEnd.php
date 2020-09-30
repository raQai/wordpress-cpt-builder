<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 */

/**
 * End of taxonomy edit form fields
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder\templates\admin
 * @subpackage taxonomy
 *
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\templates\admin\taxonomy;

use BIWS\CPTBuilder\views\admin\taxonomy\formfields\TaxonomyFormFieldContainer;

if (!($render_object instanceof TaxonomyFormFieldContainer)) {
    die("Template {$__FILE__} requires TaxonomyFormFieldContainer");
}
?>
</tr>