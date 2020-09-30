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
 * An extensible object to render templates
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
class RenderObject
{
    /**
     * @since 1.0.0
     * @access private
     *
     * @return string The template to be rendered/required.
     */
    private string $template;

    /**
     * @since 1.0.0
     *
     * @param string $template The template to be rendered/required.
     */
    public function __construct(string $template)
    {
        $this->template = $template;
    }

    /**
     * Requires the set template and provides this render object to handle the
     * set values within the template.
     *
     * @since 1.0.0
     */
    public function render(): void
    {
        if (!is_string($this->template)) {
            return;
        }

        $render_object = $this;

        /**
         * Requires the template path to display it in the view.
         */
        require $this->template;
    }
}
