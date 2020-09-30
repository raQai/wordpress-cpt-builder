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
 * IViewController interface
 *
 * Can be used to extend the core functionality combined with the RenderService.
 *
 * @since      1.0.0
 * 
 * @see        RenderService
 *
 * @package    BIWS\CPTBuilder
 * @subpackage views
 */
interface IViewController
{
    /**
     * Add actions and filters for the view controller here.
     * These actions/filters shall not have to run any UI code,
     * put them in IView implementations instead.
     *
     * @since 1.0.0
     *
     * @see IView
     */
    public function init(): void;

    /**
     * Removes previously registered actions and filters for this controller
     * to make sure we can overwrite/replace existing ones using the
     * RenderService.
     *
     * @since 1.0.0
     */
    public function remove(): void;
}
