<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 * 
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\models\fields;

/**
 * Currently supported fields
 * 
 * Cosntants for the currently supported Taxonomy and MetaBox fields.
 * NOTE: IMAGE and COLOR are currently not supported by MetaBox.
 * 
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder\models
 * @subpackage fields
 * 
 * @abstract
 */
abstract class FieldType
{
    const NUMBER = 0;
    const TEXT = 1;
    const COLOR = 2;
    const IMAGE = 3;
    const CHECKBOX = 4;
    const DATE = 5;
    const TIME = 6;
    const EMAIL = 7;
}
