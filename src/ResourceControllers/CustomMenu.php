<?php

// phpcs:disable PSR1.Methods.CamelCapsMethodName

namespace WpYaml\ResourceControllers;

use Exception;

/**
 * @SuppressWarnings(PHPMD.CamelCaseVariableName)
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class CustomMenu extends ResourceController
{
    public function process()
    {
        if (!empty($this->config)) {
            add_action('admin_menu', [$this, 'loadMenus']);
        }
    }

    /**
     * Function loadSubMenu
     *
     * @return void
     */
    public function loadMenus()
    {
        foreach ($this->config as $menu) {
            if (!isset($menu['capability'])) {
                throw new Exception('capability is required for ' . $menu['type']);
            }

            switch ($menu['type']) {
                case 'main_menu':
                    $this->loadMainMenu($menu);
                    break;
                case 'sub_menu':
                    $this->loadSubMenu($menu);
                    break;
                default:
                    throw new Exception(
                        'invalid menu type, valid values are "main_menu" or "sub_menu"'
                    );
            }
        }
    }

    /**
     * Function loadMainMenu
     *
     * @param array $menu configuration for the menu
     *
     * @return void
     */
    private function loadMainMenu(array $menu): void
    {
        $callback = [$this, 'defaultCallback'];
        if (isset($menu['callback']) && isset($menu['method'])) {
            $callback = function () use ($menu) {
                call_user_func_array(
                    [
                        $menu['callback'],
                        $menu['method']
                    ],
                    [
                        $menu['data']
                    ]
                );
            };
        }

        add_menu_page(
            $menu['label'],
            $menu['label'],
            $menu['capability'],
            $menu['slug'],
            $callback,
            $menu['icon'],
            $menu['position']
        );
    }

    /**
     * Method loadSubMenu
     *
     * @param array $menu metadata from yaml config file
     *
     * @return void
     */
    private function loadSubMenu(array $menu)
    {
        if (!isset($menu['callback']) || !isset($menu['method'])) {
            throw new Exception('callback and method are required for sub_menu');
        }

        if (!isset($menu['parent'])) {
            throw new Exception('parent is required for sub_menu');
        }

        add_submenu_page(
            $menu['parent'],
            $menu['label'],
            $menu['label'],
            $menu['capability'],
            $menu['slug'],
            function () use ($menu) {
                call_user_func_array(
                    [
                        $menu['callback'],
                        $menu['method']
                    ],
                    [
                        $menu['data']
                    ]
                );
            }
        );
    }

    /**
     * Function defaultCallback
     *
     * @return void
     */
    public function defaultCallback(): void
    {
        echo "The callback for this menu was not setup.";
    }
}
