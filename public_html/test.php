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
$objects = [];
$last_gender = '';

while ($data = $query->fetch(PDO::FETCH_LAZY)) {
    $gender = $data['gender']; // Requestas i duombaze
    if ($gender == $last_gender && $gender == 'f') {
        break;
    } else {
        $last_gender = $gender;
        $objects[] = [
            'full_name' => $data['full_name'],
            'age' => $data['age'],
            'email' => $data['email'],
            'gender' => $data['gender'],
            'photo' => $data['photo']
        ];
    }
}
?>
<html>
    <head>
        <title>Connection DB</title>
    </head>
    <body>
        <?php foreach ($objects as $row): ?>
            <ul>
                <li><?php print $row['email'] ?></li>
                <li><?php print $row['full_name'] ?></li>
                <li><?php print $row['age'] ?></li>
                <li><?php print $row['gender'] ?></li>
                <li><?php print $row['photo'] ?></li>
            </ul>
        <?php endforeach; ?>
    </body>
</html>

