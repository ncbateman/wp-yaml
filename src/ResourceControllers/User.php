<?php

// phpcs:disable PSR1.Methods.CamelCapsMethodName

namespace WpYaml\ResourceControllers;

use Symfony\Component\Yaml\Yaml as Yaml;

/**
 * @SuppressWarnings(PHPMD.CamelCaseVariableName)
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class User extends ResourceController
{
    public function setup()
    {
        $existing_users = [];
        $plugin_dirs = $this->resources['plugin_directories'];

        // foreach plugin registered with WpYaml, what are the users defined?
        foreach ($plugin_dirs as $plugin_dir) {
            if (isset($plugin_dir['user']) && ! empty($plugin_dir['user'])) {
                foreach ($plugin_dir['user'] as $user) {
                    $existing_users = array_merge($existing_users, $this->get_user_slug($user));
                }
            }
        }
    }


    public function process()
    {
        foreach ($this->config as $role => $args) {
            $wpRole = get_role($role);
            if ($wpRole instanceof \WP_Role) {
                $requiredCapabilities = [];
                foreach ($args['capabilities'] as $capability => $granted) {
                    if (true === boolval($granted)) {
                        $requiredCapabilities[] = $capability;
                        if (false === $wpRole->has_cap($capability)) {
                            $wpRole->add_cap($capability);
                        }
                    }
                }
                foreach ($wpRole->capabilities as $capability => $granted) {
                    if (false === in_array($capability, $requiredCapabilities)) {
                        $wpRole->remove_cap($capability);
                    }
                }
            } else {
                add_role($role, $args['display_name'], $args['capabilities']);
            }
        }
    }

    /**
     * @param $user
     * @return array
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function get_user_slug($user)
    {
        $user_data = Yaml::parseFile($user, Yaml::PARSE_CONSTANT);
        $usernames = [];
        foreach (array_keys($user_data) as $username) {
            $usernames[$username] = true;
        }
        return $usernames;
    }
}
