<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 * 
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\views;

/**
 * Currently supported render types
 * 
 * Cosntants for the by default supported render types within the RenderService.
 * 
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder
 * @subpackage views
 * 
 * @abstract
 */
abstract class RenderType
{
    const CPT_COLUMN = "cpt_column";
    const CPT_META_BOX_SCRIPT = "cpt_meta_box_script";
    const CPT_MEDIA_UPLOADER_SCRIPT = "cpt_media_uploader_script";

    const TAXONOMY_NEW = "taxonomy_new";
    const TAXONOMY_EDIT = "taxonomy_edit";
    const TAXONOMY_COLUMN = "taxonomy_column";
}
