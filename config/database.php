<?php
return [
    'Doctrine' => [
        'isDevMode' => false
    ],

    'connections' => [
        'main' => [
            'driver'   => 'pdo_mysql',
            'host'     => 'localhost',
            'dbname'   => 'codi_system',
            'user'     => 'root',
            'password' => 'admin',
            'charset'  => 'utf8'
        ]
    ]

];
