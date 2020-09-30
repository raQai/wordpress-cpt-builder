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
 * An extensible object to render templates wrapping around other functionality
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
class RenderObjectWrapper
{
    /**
     * @since 1.0.0
     * @access private
     *
     * @return string The template to be rendered/required at the start.
     */
    private string $start_template;

    /**
     * @since 1.0.0
     * @access private
     *
     * @return string The template to be rendered/required at the end.
     */
    private string $end_template;

    /**
     * @since 1.0.0
     *
     * @param string $start_template The template to be rendered/required at
     *                               the start of the wrapper.
     * @param string $end_template   The template to be rendered/required at
     *                               the end of the wrapper.
     */
    public function __construct(string $start_template, string $end_template)
    {
        $this->start_template = $start_template;
        $this->end_template = $end_template;
    }

    /**
     * Requires the set templates and provides this render object to handle the
     * set values within the template.
     *
     * @since 1.0.0
     * 
     * @param callable $content_between_callback The callback to define the
     *                                           content to be wrapped by the
     *                                           set templates.
     */
    public function render(callable $content_between_callback): void {
        $this->renderTemplatePart($this->start_template);
        call_user_func($content_between_callback);
        $this->renderTemplatePart($this->end_template);
    }

    /**
     * Requires the set template and provides this render object to handle the
     * set values within the template.
     *
     * @since 1.0.0
     *
     * @access private
     */
    private function renderTemplatePart(string $template) {
        if (!is_string($template) || empty($template)) {
            return;
        }

        $render_object = $this;
        require $template;
    }
}
