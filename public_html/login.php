<?php
require_once '../bootloader.php';

$form = [
    'fields' => [
        'email' => [
            'label' => 'Email',
            'type' => 'text',
            'placeholder' => 'iveskite savo email',
            'validate' => [
                'validate_not_empty',
                'validate_email',
            ]
        ],
        'password' => [
            'label' => 'Password',
            'type' => 'password',
            'placeholder' => 'Slaptazodis',
            'validate' => [
                'validate_not_empty',
            ],
        ],
    ],
    'buttons' => [
        'submit' => [
            'text' => 'Irasyti!'
        ]
    ],
    'validate' => [
        'validate_login'
    ],
    'callbacks' => [
        'success' => [],
        'fail' => []
    ]
];

function validate_login(&$safe_input, &$form) {
    $db = new \Core\FileDB(DB_FILE);
    $repository = new \Core\User\Repository($db, TABLE_USERS);
    $session = new \Core\User\Session($repository);

    $status = $session->login($safe_input['email'], $safe_input['password']);
    if ($status == Core\User\Abstracts\Session::LOGIN_SUCCESS) {
        return true;
    } else {
        $form['error_msg'] = strtr('"@name" nepavyko prisijungti... ', [
            '@name' => $safe_input['email']
        ]);
    }
}

if (!empty($_POST)) {
    $safe_input = get_safe_input($form);
    $form_success = validate_form($safe_input, $form);

    if ($form_success) {
        header('Location: index.php');
        exit();
    }
}

?>
<html>
    <head>
        <title>Raugis Project</title>
        <?php foreach ($page['css'] as $value): ?>
            <link <?php print $value; ?>>
        <?php endforeach; ?>
    </head>
    <body>
        <!-- navigation -->
        <?php include ROOT_DIR . '/objects/navigation.php'; ?>

        <h1>Get on stage!</h1>
        <div class="forma"><?php require '../core/views/form.php'; ?></div>
        
        <?php if (isset($success_msg)): ?>
            <h2><?php print $success_msg; ?></h2>
        <?php endif; ?>
            
        <?php foreach ($page['script'] as $value): ?>
            <script <?php print $value; ?>></script>
        <?php endforeach; ?>
    </body>
</html>

