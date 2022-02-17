<?php

// phpcs:disable PSR1.Methods.CamelCapsMethodName

namespace WpYaml\ResourceControllers;

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
                throw new \Exception('capability is required for ' . $menu['type']);
            }

            if ($menu['type'] === 'main_menu') {
                $this->_loadMainMenu($menu);
            }

            if ($menu['type'] === 'sub_menu') {
                $this->_loadSubMenu($menu);
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
    private function _loadMainMenu(array $menu): void
    {
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
        } else {
            $callback = [$this, 'defaultCallback'];
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
     * @return void|WP_Error
     */
    private function _loadSubMenu(array $menu)
    {
        if (!isset($menu['callback']) || !isset($menu['method'])) {
            throw new \Exception('callback and method are required for sub_menu');
        }

        if (!isset($menu['parent'])) {
            throw new \Exception('parent is required for sub_menu');
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
