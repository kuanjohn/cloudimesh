<?php

return [
    'databases' => [
        'Cassandra' => [
            'Apache Cassandra 4.0' => ['cost' => 1.80, 'min_disk' => 80, 'min_vcpu' => 2, 'min_vmem' => '8', 'cost_type' => 'Core', 'platform' => 'Linux'], 
            'Apache Cassandra 3.11' => ['cost' => 1.80, 'min_disk' => 80, 'min_vcpu' => 2, 'min_vmem' => '8', 'cost_type' => 'Core', 'platform' => 'Linux'], 
            'Apache Cassandra 3.0' => ['cost' => 1.80, 'min_disk' => 80, 'min_vcpu' => 2, 'min_vmem' => '8', 'cost_type' => 'Core', 'platform' => 'Linux']
        ],
        // 'MariaDB',
        'Microsoft SQL Server' => [
            'Microsoft SQL Server 2017 Standard Edition' => ['cost' => 3.60, 'min_disk' => 100, 'min_vcpu' => 4, 'min_vmem' => '16', 'cost_type' => 'Core', 'platform' => 'Windows'], 
            'Microsoft SQL Server 2019 Standard Edition' => ['cost' => 3.60, 'min_disk' => 100, 'min_vcpu' => 4, 'min_vmem' => '16', 'cost_type' => 'Core', 'platform' => 'Windows'], 
            'Microsoft SQL Server 2017 Datacenter Edition' => ['cost' => 7.20, 'min_disk' => 100, 'min_vcpu' => 4, 'min_vmem' => '16', 'cost_type' => 'Core', 'platform' => 'Windows'], 
            'Microsoft SQL Server 2019 Datacenter Edition' => ['cost' => 7.20, 'min_disk' => 100, 'min_vcpu' => 4, 'min_vmem' => '16', 'cost_type' => 'Core', 'platform' => 'Windows'], 
        ],
        // 'MongoDB',
        'MySQL' => [
            'MySQL 8.0' => ['cost' => 1.80, 'min_disk' => 80, 'min_vcpu' => 2, 'min_vmem' => '8', 'cost_type' => 'Core', 'platform' => 'Linux'],
            'MySQL 5.7' => ['cost' => 1.80, 'min_disk' => 80, 'min_vcpu' => 2, 'min_vmem' => '8', 'cost_type' => 'Core', 'platform' => 'Linux'],
        ],
        'Oracle Database' => [
            'Oracle Database 19c' => ['cost' => 4.50, 'min_disk' => 80, 'min_vcpu' => 2, 'min_vmem' => '8', 'cost_type' => 'Core', 'platform' => 'Linux'],
            'Oracle Database 18c' => ['cost' => 4.50, 'min_disk' => 80, 'min_vcpu' => 2, 'min_vmem' => '8', 'cost_type' => 'Core', 'platform' => 'Linux'],
        ],
        'PostgreSQL' => [
            'PostgreSQL 14' => ['cost' => 1.80, 'min_disk' => 80, 'min_vcpu' => 2, 'min_vmem' => '8', 'cost_type' => 'Core', 'platform' => 'Linux'],
            'PostgreSQL 13' => ['cost' => 1.80, 'min_disk' => 80, 'min_vcpu' => 2, 'min_vmem' => '8', 'cost_type' => 'Core', 'platform' => 'Linux'],
        ],
        'Redis' => [
            'Redis 7.0' => ['cost' => 1.80, 'min_disk' => 80, 'min_vcpu' => 2, 'min_vmem' => '8', 'cost_type' => 'Core', 'platform' => 'Linux'],
            'Redis 6.0' => ['cost' => 1.80, 'min_disk' => 80, 'min_vcpu' => 2, 'min_vmem' => '8', 'cost_type' => 'Core', 'platform' => 'Linux'],
        ],
        'SQLite' => [
            'SQLite 3.36' => ['cost' => 1.00, 'min_disk' => 80, 'min_vcpu' => 2, 'min_vmem' => '8', 'cost_type' => 'Core', 'platform' => 'Linux'],
            'SQLite 3.33' => ['cost' => 1.00, 'min_disk' => 80, 'min_vcpu' => 2, 'min_vmem' => '8', 'cost_type' => 'Core', 'platform' => 'Linux'],
        ],
    ],
];
