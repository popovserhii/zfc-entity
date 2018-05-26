<?php

namespace Popov\ZfcEntity;

class Module
{
    public function getConfig()
    {
        $config = require __DIR__ . '/../config/module.config.php';
        $config['service_manager'] = $config['dependencies'];
        unset($config['dependencies']);
        unset($config['controller_plugins']);

        return $config;
    }
}
