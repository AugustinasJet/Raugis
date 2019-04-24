<?php
require_once '../bootloader.php';

use Core\Database\SQLBuilder;

$db = new \Core\Database\Connection([
    'host' => 'localhost',
    'user' => 'root',
    'password' => '12345'
        ]);

$pdo = $db->getPDO();

$row = [
    'email' => 'fokacija2334@gmail.com',
    'password' => '123456444',
    'full_name' => 'Augis Raugis9000',
    'gender' => 'm',
    'age' => 24,
    'photo' => 'augis2.jpg'
];

$columns = array_keys($row);

$sql = strtr("CREATE DATABASE `new_shema_name`;"
        . "CREATE USER '@user'@'@host' IDENTIFIED BY '@pass';"
        . "GRANT ALL ON `new_shema_name`.* TO '@user'@'@host';"
        . "FLUSH PRIVILEGES;", [
            '@user' => SQLBuilder::schema('my_db'),
            '@host' => SQLBuilder::table('users'),
            '@columns' => SQLBuilder::columns($columns),
            '@values' => SQLBuilder::binds($columns),
            
        ]);

$query = $pdo->prepare($sql);

foreach($row as $key => &$value) {
    $query->bindValue(SQLBuilder::bind($key), $value);
}

$query->execute();