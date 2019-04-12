<?php
require_once '../bootloader.php';

$form = [
    'fields' => [
        'rap_line' => [
            'label' => 'rap line',
            'type' => 'text',
            'placeholder' => 'rap šmap',
            'validate' => [
                'validate_not_empty',
                'validate_line'
            ]
        ],
    ],
    'buttons' => [
        'submit' => [
            'text' => 'Aš reperis'
        ]
    ],
    'validate' => [],
    'callbacks' => [
        'success' => [
            'form_success'
        ],
        'fail' => []
    ],
    'errors' => []
];

function validate_line($field_input, &$field, $safe_input) {
    if (1 !== preg_match('~[0-9]~', $field_input)) {
        if (strlen($field_input) >= 10 && strlen($field_input) <= 60) {
            return true;
        } else {
            $field['error_msg'] = 'netinkamas simboliu kiekis madafaka';
        }
    } else {
        $field['error_msg'] = 'Tavo repe negali buti skaiciu madafaka';
    }
}

function form_success($safe_input, $form) {
    $db = new \Core\FileDB(DB_FILE);
    $repository = new \Core\User\Repository($db, TABLE_USERS);
    $session = new \Core\User\Session($repository);
    $user = $session->getUser();
    $line = new App\Rap\Line([
        'email' => $user->getEmail(),
        'line' => $safe_input['rap_line'],
    ]);
    $song_table = new App\Rap\Song($db, TABLE_LINES);
    $song_table->insert($line);
}

if (!empty($_POST)) {
    $safe_input = get_safe_input($form);
    $form_success = validate_form($safe_input, $form);
    if ($form_success) {
        $success_msg = 'tavo repas kietas';
    }
}

$db = new \Core\FileDB(DB_FILE);
$repository = new \Core\User\Repository($db, TABLE_USERS);
$session = new \Core\User\Session($repository);
$song_table = new App\Rap\Song($db, TABLE_LINES);
$song = $song_table->loadAll();

if ($session->isLoggedIn()) {
    $email = $session->getUser()->getEmail();
}
?>
<html>
    <head>
        <title>Raugis</title>
        <?php foreach ($page['css'] as $value): ?>
            <link <?php print $value; ?>>
        <?php endforeach; ?>
    </head>
    <body>
        <!-- navigation -->
        <?php include ROOT_DIR . '/objects/navigation.php'; ?>

        <h1>P-OOPMC</h1>

        <?php if ($song !== []): ?>
            <div class="text-center">

                <?php foreach ($song as $line): ?>
                    <span><?php print $line->getLine(); ?><span class="rap-author"><?php print ' ' . $repository->load($line->getEmail())->getFullName(); ?></span></span><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($session->isLoggedIn()): ?>
            <div class="container text-center">
                <?php require '../core/views/form.php'; ?>
            </div>
            <h1>Labas, <?php print $session->getUser()->getFullName(); ?>, esi prisijunges bič. Jei nori atsijungti spausk <a href="./logout.php">aš nebenoriu repuot.</a></h1>
        <?php endif; ?>

        <?php foreach ($page['script'] as $script): ?>
            <script <?php print $script; ?>></script>
        <?php endforeach; ?>
    </body>
</html>