<?php
require_once '../bootloader.php';

$test = new Core\Database\Connection(
        [
            'host' => 'localhost',
            'user' => 'ruta',
            'password' => 'root'
        ]);

$test->connect();