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
 * IView interface
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
interface IView
{
    /**
     * Gets the controller managing this view. 
     *
     * @since 1.0.0
     *
     * @return IViewController The controller for this view.
     */
    public function getController(): IViewController;

    /**
     * Adds UI/renderiong related actions and filters for the view.
     * Actions/filters may rely on logic provided by the controller.
     *
     * @since 1.0.0
     */
    public function init(): void;

    /**
     * Removes previously registered actions and filters for the
     * view to make sure it can be overwritten by the RenderService.
     *
     * @since 1.0.0
     * 
     * @see RenderService
     */
    public function remove(): void;
}
