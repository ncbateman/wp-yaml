<?php

namespace WpYaml\ResourceControllers;

/**
 * @SuppressWarnings(PHPMD.CamelCaseVariableName)
 */
class FieldGroup extends ResourceController
{
    private $mode = 'singluar';

    public function setup()
    {
        if (is_array($this->config) && ! isset($this->config['key'])) {
            $this->mode = 'multi';
        }
        if (isset($this->config['settings_page'])) {
            $this->mode = 'settings_page';
        }
    }

    public function process()
    {
        $register_mode = 'register_' . $this->mode;
        $this->$register_mode();
    }
}
