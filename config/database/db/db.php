<?php
return [
    'db'=>[
        'driver'=>Sunny\ext\db\Mysql::class,
        'dns'=>'mysql:host=127.0.0.1;dbname=test',
        'username'=>'root',
        'password'=>'toot',
        'setAttribute'=>[
                           [\PDO::ATTR_CASE,\PDO::CASE_LOWER],
                           [\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION]

        ],
        'pdoAttr'=>[\PDO::ATTR_PERSISTENT => true]
    ],
    'db2'=>[
        'driver'=>Sunny\ext\db\Mysql::class,
        'dns'=>'mysql:host=127.0.0.1;dbname=test',
        'username'=>'root',
        'password'=>'root',
        'setAttribute'=>[
            [\PDO::ATTR_CASE,\PDO::CASE_LOWER],
            [\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION]

        ],
        'pdoAttr'=>[\PDO::ATTR_PERSISTENT => true]
    ]

];