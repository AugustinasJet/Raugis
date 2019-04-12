<?php
require_once '../bootloader.php';

$form = [
    'fields' => [],
    'buttons' => [
        'submit' => [
            'text' => 'Logout bič!'
        ]
    ],
    'validate' => [],
    'callbacks' => [
        'success' => [],
        'fail' => []
    ]
];

$db = new \Core\FileDB(DB_FILE);
$repository = new \Core\User\Repository($db, TABLE_USERS);
$session = new \Core\User\Session($repository);

if ($session->isLoggedIn()) {
    if (!empty($_POST)) {
        $safe_input = get_safe_input($form);
        $form_success = validate_form($safe_input, $form);

        if ($form_success) {
            $session->logout();
        }
    }
}

if (!$session->isLoggedIn()) {
    header('Location: login.php');
    exit();
}
?>
<html>
    <head>
        <title>Logout</title>
        <?php foreach ($page['css'] as $value): ?>
            <link <?php print $value; ?>>
        <?php endforeach; ?>
    </head>
    <body>
        <!-- navigation -->
        <?php include ROOT_DIR . '/objects/navigation.php'; ?>

        <h1>See you later alligator!</h1>
        <div class="forma"><?php require '../core/views/form.php'; ?></div>

        <?php if (isset($success_msg)): ?>
            <h2><?php print $success_msg; ?></h2>
        <?php endif; ?>

        <?php foreach ($page['script'] as $value): ?>
            <script <?php print $value; ?>></script>
        <?php endforeach; ?>
    </body>
</html>

