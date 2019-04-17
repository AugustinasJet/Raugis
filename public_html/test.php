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
//$query = $pdo->prepare("INSERT INTO `my_db`.`new_table`"
//        . "(`email`, `password`, `full_name`, `age`, `gender`, `photo`)" .
//        "VALUES(:email, :password, :full_name, :age, :gender, :photo)");

$query = $pdo->query("SELECT * FROM `my_db`.`new_table`");

foreach ($inputs as $key => &$value) {
    $query->bindParam(':' . $key, $value);
}

$data = $query->fetchAll(PDO::FETCH_ASSOC);


//foreach ($data as $row) {
//    foreach ($row as $value) {
//        print $value;
//    }
//}

$query->execute();
?>
<html>
    <head>
        <title>Connection DB</title>
    </head>
    <body>
        <?php foreach ($data as $row): ?>
            <ul>
                <?php foreach ($row as $value): ?>
                    <li><?php print $value ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
    </body>
</html>

