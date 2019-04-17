<?php
require_once '../bootloader.php';

$db = new \Core\Database\Connection([
    'host' => 'localhost',
    'user' => 'root',
    'password' => '123456'
        ]);
$inputs = [
    'email' => 'a@aasd.lt',
    'password' => 'pass',
    'full_name' => 'Jonas Paulius',
    'age' => 55,
    'gender' => 'm',
    'photo' => 'as.jpg'
];

$pdo = $db->getPDO();
$query = $pdo->query("SELECT * FROM `my_db`.`new_table`");
$data = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<html>
    <head>
        <title>Connection DB</title>
    </head>
    <body>
        <?php foreach ($data as $row): ?>
            <ul>
                
                <?php foreach ($row as $col): ?>
                    <li><?php print $col ?></li>
                <?php endforeach; ?>
                    
            </ul>
        <?php endforeach; ?>
    </body>
</html>

