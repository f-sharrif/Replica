<?php

if(Replica::get_system('debug_mode')) {

    return
        [
            'title' => 'Show system configuration',
            'config' => array_merge(Replica::get_system(), get_defined_constants(true)['user']),
            'template' => 'system/show-system-config'
        ];
}else
{
    return [
        'title'     => 'Debug mode must be on to see system config',
        'content'   => '
                <p> Sorry but the "Debug Mode" must be on in order to view
                the system configuration settings</p>
        ',
        'config'    => [],
        'template'  => 'system/show-system-config-debug-off'
    ];
}


