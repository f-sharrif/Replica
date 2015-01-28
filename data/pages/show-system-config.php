<?php

if(Replica::get('debug_mode',Replica::conf()))
{

    return
        [
            'title' => 'Show system configuration',
            'config' => array_merge(Replica::conf(), get_defined_constants(true)['user']),
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


