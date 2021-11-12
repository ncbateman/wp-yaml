<?php

namespace WpYaml\ResourceControllers;

class Filter extends ResourceController
{
    public function process()
    {
        if (isset($this->config['filters']) && isset($this->config['callback'])) {
            foreach ($this->config['filters'] as $action) {
                if (! isset($action['priority'])) {
                    $action['priority'] = 10;
                }
                if (! isset($action['args'])) {
                    $action['args'] = 1;
                }
                add_filter(
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
