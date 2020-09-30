<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\services;

/**
 * By default supported template types
 * 
 * Cosntants for the currently supported template types
 *
 * @since      1.0.0
 *
 * @see        TemplateService
 *
 * @package    BIWS\CPTBuilder
 * @subpackage services
 *
 * @abstract
 */
abstract class TemplateType
{
    const TAXONOMY = "taxonomy";
    const META_BOX = "meta_box";
}
