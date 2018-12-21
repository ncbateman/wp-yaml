<?php

namespace WpYaml\ResourceControllers;

class Filter extends ResourceController
{
    public function process()
    {
        if (isset($this->config['filters']) && isset($this->config['callback']) ) {
            foreach ( $this->config['filters'] as $action ) {
                add_filter($action['hook'], [ $this->config['callback'], $action['method'] ]);
            }
        }
    }
}
