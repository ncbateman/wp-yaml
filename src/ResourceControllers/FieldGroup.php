<?php
// phpcs:disable PSR1.Methods.CamelCapsMethodName

namespace WpYaml\ResourceControllers;

/**
 * @SuppressWarnings(PHPMD.CamelCaseVariableName)
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 * @SuppressWarnings(PHPMD.CamelCaseParameterName)
 */
class FieldGroup extends ResourceController
{
    public const REGISTER_MODE_SINGULAR = 'singular';
    public const REGISTER_MODE_MULTI = 'multi';
    public const REGISTER_MODE_SETTINGS = 'settings_page';

    private $mode = self::REGISTER_MODE_SINGULAR;
    private $prefix;

    public function setup()
    {
        if (is_array($this->config) && ! isset($this->config['key'])) {
            $this->mode = self::REGISTER_MODE_MULTI;
        }
        if (isset($this->config['settings_page'])) {
            $this->mode = self::REGISTER_MODE_SETTINGS;
        }
    }

    public function process()
    {
        switch ($this->mode) {
            case self::REGISTER_MODE_SINGULAR:
                $this->register_singular();
                break;
            case self::REGISTER_MODE_MULTI:
                $this->register_multi();
                break;
            case self::REGISTER_MODE_SETTINGS:
                $this->register_settings_page();
                break;
        }
    }


    private function register_singular()
    {
        acf_add_local_field_group($this->config);
    }

    private function register_multi()
    {
        foreach ($this->config as $fieldgroup) {
            acf_add_local_field_group($fieldgroup);
        }
    }

    private function register_settings_page()
    {
        $config = $this->prefix_config($this->config['settings_page'], $this->config);
        acf_add_local_field_group($config);
    }

    private function prefix_config($prefix, $config)
    {
        $this->prefix = $prefix;
        array_walk_recursive($config, [ $this, 'prefix_key' ]);
        $this->prefix = null;
        return $config;
    }

    public function prefix_key(&$field_value, $field_key)
    {
        if ($field_key === "key") {
            $field_value = $this->prefix . '-' . $field_value;
        }
        if ($field_key === "name") {
            $field_value = $this->prefix . '_' . $field_value;
        }
    }
}
