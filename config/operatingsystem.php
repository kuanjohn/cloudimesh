<?php

return [
    'Windows' => [
        'Windows 2000 Server Standard Edition' => ['cost' => 1.80, 'min_disk' => 100, 'cost_type' => 'Instance'],
        'Windows 2003 Server Standard Edition' => ['cost' => 1.80, 'min_disk' => 100, 'cost_type' => 'Instance'],
        'Windows 2008 Server Standard Edition' => ['cost' => 1.80, 'min_disk' => 100, 'cost_type' => 'Instance'],
        'Windows 2008 R2 Server Standard Edition' => ['cost' => 1.80, 'min_disk' => 100, 'cost_type' => 'Instance'],
        'Windows 2012 Server Standard Edition' => ['cost' => 1.80, 'min_disk' => 100, 'cost_type' => 'Core'],
        'Windows 2012 R2 Server Standard Edition' => ['cost' => 1.80, 'min_disk' => 100, 'cost_type' => 'Core'],
        'Windows 2016 Server Standard Edition' => ['cost' => 1.80, 'min_disk' => 100, 'cost_type' => 'Core'],
        'Windows 2019 Server Standard Edition' => ['cost' => 1.80, 'min_disk' => 100, 'cost_type' => 'Core'],
        'Windows 2000 Server Datacenter Edition' => ['cost' => 2.60, 'min_disk' => 100, 'cost_type' => 'Instance'],
        'Windows 2003 Server Datacenter Edition' => ['cost' => 2.60, 'min_disk' => 100, 'cost_type' => 'Instance'],
        'Windows 2008 Server Datacenter Edition' => ['cost' => 2.60, 'min_disk' => 100, 'cost_type' => 'Instance'],
        'Windows 2008 R2 Server Datacenter Edition' => ['cost' => 2.60, 'min_disk' => 100, 'cost_type' => 'Instance'],
        'Windows 2012 Server Datacenter Edition' => ['cost' => 2.60, 'min_disk' => 100, 'cost_type' => 'Core'],
        'Windows 2012 R2 Server Datacenter Edition' => ['cost' => 2.60, 'min_disk' => 100, 'cost_type' => 'Core'],
        'Windows 2016 Server Datacenter Edition' => ['cost' => 2.60, 'min_disk' => 100, 'cost_type' => 'Core'],
        'Windows 2019 Server Datacenter Edition' => ['cost' => 2.60, 'min_disk' => 100, 'cost_type' => 'Core'],
    ],
    
    'Ubuntu' => [
        'Ubuntu Server 20.04 LTS (Focal Fossa)' => ['cost' => 0.30, 'min_disk' => 80, 'cost_type' => 'Core'],
        'Ubuntu Server 18.04 LTS (Bionic Beaver)' => ['cost' => 0.30, 'min_disk' => 80, 'cost_type' => 'Core'],
        'Ubuntu Server 16.04 LTS (Xenial Xerus)' => ['cost' => 0.30, 'min_disk' => 80, 'cost_type' => 'Core'],
    ],
    
    'CentOS' => [
        'CentOS 8' => ['cost' => 0.30, 'min_disk' => 80, 'cost_type' => 'Core'],
        'CentOS 7' => ['cost' => 0.30, 'min_disk' => 80, 'cost_type' => 'Core'],
    ],
    
    'Debian' => [
        'Debian 10 (Buster)' => ['cost' => 0.30, 'min_disk' => 80, 'cost_type' => 'Core'],
        'Debian 9 (Stretch)' => ['cost' => 0.30, 'min_disk' => 80, 'cost_type' => 'Core'],
    ],
       
    'Fedora Server' => [
        'Fedora Server 35' => ['cost' => 0.30, 'min_disk' => 80, 'cost_type' => 'Core'],
        'Fedora Server 34' => ['cost' => 0.30, 'min_disk' => 80, 'cost_type' => 'Core'],
    ],
    
    'Red Hat Enterprise Linux' => [
        'RHEL 8' => ['cost' => 0.60, 'min_disk' => 80, 'cost_type' => 'Core'],
        'RHEL 7' => ['cost' => 0.60, 'min_disk' => 80, 'cost_type' => 'Core'],
    ],

    'OpenSUSE' => [
        'openSUSE Leap 15.x' => ['cost' => 0.30, 'min_disk' => 80, 'cost_type' => 'Core'],
        'openSUSE Tumbleweed' => ['cost' => 0.30, 'min_disk' => 80, 'cost_type' => 'Core'],
    ],
];

