<?php

namespace WpYaml\ResourceControllers;

class Action extends ResourceController
{
    public function setup()
    {
        if (isset($this->config['actions']) && isset($this->config['callback']) ) {
            foreach ( $this->config['actions'] as $action ) {
                add_action($action['hook'], [ $this->config['callback'], $action['method'] ]);
            }
        }
    }
}
