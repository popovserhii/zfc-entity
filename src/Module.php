<?php

namespace Popov\ZfcEntity;

use Zend\Console\Console;

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

    public function getConsoleUsage($console) {
        return [
            'Usage:',
            'entity [<command>]' => '',

            'Command:',
            ['sync', 		                    'Run sync process for module and entity database tables.']
        ];
    }
}
