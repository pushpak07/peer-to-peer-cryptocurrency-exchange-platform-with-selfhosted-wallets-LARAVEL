<?php
/**
 * ======================================================================================================
 * File Name: Template.php
 * ======================================================================================================
 * Author: HolluwaTosin360
 * ------------------------------------------------------------------------------------------------------
 * Portfolio: http://codecanyon.net/user/holluwatosin360
 * ------------------------------------------------------------------------------------------------------
 * Date & Time: 11/19/2018 (10:01 AM)
 * ------------------------------------------------------------------------------------------------------
 *
 * Copyright (c) 2018. This project is released under the standard of CodeCanyon License.
 * You may NOT modify/redistribute this copy of the project. We reserve the right to take legal actions
 * if any part of the license is violated. Learn more: https://codecanyon.net/licenses/standard.
 *
 * ------------------------------------------------------------------------------------------------------
 */

namespace App\Logics\Support;


class Template
{
    /**
     * @var string
     */
    protected $template;

    /**
     * @var string
     */
    protected $theme_color;

    /**
     * Template constructor.
     *
     * @param string $template
     * @param string $theme_color
     */
    public function __construct($template, $theme_color)
    {
        $this->template = $template;
        $this->theme_color = $theme_color;
    }

    /**
     * Get template stylesheet
     *
     * @return string
     */
    public function stylesheet()
    {
        switch (strtolower($this->template)) {
            case 'vertical':
                $stylesheet = asset('css/core/menu/menu-types/vertical-menu.css');
                break;

            case 'vertical-compact':
                $stylesheet = asset('css/core/menu/menu-types/vertical-compact-menu.css');
                break;

            case 'vertical-overlay':
                $stylesheet = asset('css/core/menu/menu-types/vertical-overlay-menu.css');
                break;

            case 'horizontal':
                $stylesheet = asset('css/core/menu/menu-types/horizontal-menu.css');
                break;

            default:
                $stylesheet = asset('css/core/menu/menu-types/vertical-menu.css');
                break;
        }

        return $stylesheet;
    }

    /**
     * Get template body class
     *
     * @return string
     */
    public function bodyClass()
    {
        switch (strtolower($this->template)) {
            case 'vertical':
                $class = 'vertical-layout vertical-menu';
                break;

            case 'vertical-compact':
                $class = 'vertical-layout vertical-compact-menu';
                break;

            case 'vertical-overlay':
                $class = 'vertical-layout vertical-overlay-menu';
                break;

            case 'horizontal':
                $class = 'horizontal-layout horizontal-menu';
                break;

            default:
                $class = 'vertical-layout vertical-menu';
                break;
        }

        return " {$class} ";
    }

    /**
     * Get body data menu
     *
     * @return string
     */
    public function bodyDataMenu()
    {
        switch (strtolower($this->template)) {
            case 'vertical':
                $menu = 'vertical-menu';
                break;

            case 'vertical-compact':
                $menu = 'vertical-compact-menu';
                break;

            case 'vertical-overlay':
                $menu = 'vertical-overlay-menu';
                break;

            case 'horizontal':
                $menu = 'horizontal-menu';
                break;

            default:
                $menu = 'vertical-menu';
                break;
        }

        return $menu;
    }

    /**
     * @return string
     */
    public function background()
    {
        return " bg-{$this->theme_color} ";
    }

    /**
     * @return string
     */
    public function backgroundGradient()
    {
        return " bg-gradient-directional-{$this->theme_color} ";
    }

    /**
     * @return string
     */
    public function button()
    {
        return " btn-{$this->theme_color} ";
    }

    /**
     * @return string
     */
    public function borderTop()
    {
        return " border-top-{$this->theme_color} ";
    }

    /**
     * @return string
     */
    public function borderBottom()
    {
        return " border-bottom-{$this->theme_color} ";
    }

    /**
     * @return string
     */
    public function borderLeft()
    {
        return " border-left-{$this->theme_color} ";
    }

    /**
     * @return string
     */
    public function borderRight()
    {
        return " border-left-{$this->theme_color} ";
    }

    /**
     * @return string
     */
    public function border()
    {
        return " border-{$this->theme_color} ";
    }
}
