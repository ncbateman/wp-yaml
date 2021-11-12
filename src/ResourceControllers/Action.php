<?php

namespace WpYaml\ResourceControllers;

class Action extends ResourceController
{
    public function setup()
    {
        if (isset($this->config['actions']) && isset($this->config['callback'])) {
            foreach ($this->config['actions'] as $action) {
                if (! isset($action['priority'])) {
                    $action['priority'] = 10;
                }
                if (! isset($action['args'])) {
                    $action['args'] = 1;
                }
                add_action(
                    $action['hook'],
                    [
                        $this->config['callback'],
                        $action['method']
                    ],
                    $action['priority'],
                    $action['args']
                );
            }
        }
    }
}
