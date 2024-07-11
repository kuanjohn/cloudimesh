<?php

return [
    'name' => 'Default VM Policy',
    'min_vcpu' => 1,
    'max_vcpu' => 128,
    'inc_vcpu' => [1,2,4,8],
    'min_vmem' => 1,
    'max_vmem' => 128,
    'inc_vmem' => [1,2,4,8,16],
    'cost_vcpu' => 0.1346,
    'cost_vmem' => 0.2470,
];