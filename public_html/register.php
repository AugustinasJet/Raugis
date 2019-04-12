<?php
require_once '../bootloader.php';

$form = [
    'fields' => [
        'username' => [
            'label' => 'Username',
            'type' => 'text',
            'placeholder' => 'iveskite savo username',
            'validate' => [
                'validate_not_empty'
            ]
        ],
        'email' => [
            'label' => 'Email',
            'type' => 'text',
            'placeholder' => 'iveskite savo email',
            'validate' => [
                'validate_not_empty',
                'validate_email',
                'validate_user_exists',
            ]
        ],
        'full_name' => [
            'label' => 'Full Name',
            'type' => 'text',
            'placeholder' => 'iveskite varda ir pavarde',
            'validate' => [
                'validate_not_empty',
                'validate_char',
                'validate_contains_space',
            ]
        ],
        'age' => [
            'label' => 'Age',
            'type' => 'number',
            'placeholder' => 'iveskite savo amziu',
            'validate' => [
                'validate_not_empty',
                'validate_age',
            ]
        ],
        'gender' => [
            'label' => 'Gender',
            'type' => 'select',
            'placeholder' => 'pasirinkite savo lyti',
            'validate' => [
                'validate_not_empty',
                'validate_select_option'
            ],
            'options' => \Core\User\User::getGenderOptions(),
        ],
        'orientation' => [
            'label' => 'Orientation',
            'type' => 'select',
            'placeholder' => 'pasirinkite savo orientacija',
            'validate' => [
                'validate_not_empty',
                'validate_select_option'
            ],
            'options' => \Core\User\User::getOrientationOptions(),
        ],
        'photo' => [
            'label' => 'photo',
            'type' => 'file',
            'placeholder' => 'file',
            'validate' => [
                'validate_file'
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
        'repeat_password' => [
            'label' => 'Repeat Password',
            'type' => 'password',
            'placeholder' => 'Pakartok Slaptazodi',
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
        'validate_password',
        'validate_form_file',
    ],
    'callbacks' => [
        'success' => [
            'form_success'
        ],
        'fail' => []
    ]
];

function form_success($safe_input, $form) {
    $user = new \Core\User\User([
        'username' => $safe_input['username'],
        'email' => $safe_input['email'],
        'full_name' => $safe_input['full_name'],
        'age' => $safe_input['age'],
        'gender' => $safe_input['gender'],
        'orientation' => $safe_input['orientation'],
        'photo' => $safe_input['photo'],
        'password' => $safe_input['password'],
        'account_type' => \Core\User\User::ACCOUNT_TYPE_USER,
        'is_active' => true
    ]);
    $db = new \Core\FileDB(DB_FILE);
    $repository = new \Core\User\Repository($db, TABLE_USERS);
    $repository->insert($user);
}

function validate_form_file(&$safe_input, &$form) {
    $file_saved_url = save_file($safe_input['photo']);
    if ($file_saved_url) {
        $safe_input['photo'] = 'uploads/' . $file_saved_url;
        return true;
    } else {
        $form['error_msg'] = 'blogas failas';
    }
}

function validate_password(&$safe_input, &$form) {
    if ($safe_input['password'] === $safe_input['repeat_password']) {
        return true;
    } else {
        $form['error_msg'] = 'nesutampa slaptazodziai';
    }
}

function save_file($file, $dir = 'uploads', $allowed_types = ['image/png', 'image/jpeg', 'image/gif']) {
    if ($file['error'] == 0 && in_array($file['type'], $allowed_types)) {
        $target_file_name = microtime() . '-' . strtolower($file['name']);
        $target_path = $dir . '/' . $target_file_name;
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            return $target_file_name;
        }
    }
    return false;
}

if (!empty($_POST)) {
    $safe_input = get_safe_input($form);
    $form_success = validate_form($safe_input, $form);
    if ($form_success) {
        header('Location: login.php');
        exit();
    }
}
?>
<html>
    <head>
        <title>Register</title>
        <?php foreach ($page['css'] as $value): ?>
            <link <?php print $value; ?>>
        <?php endforeach; ?>
    </head>
    <body>
        <!-- navigation -->
        <?php include ROOT_DIR . '/objects/navigation.php'; ?>

        <h1>Become a rap God</h1>
        <div class="forma"><?php require '../core/views/form.php'; ?></div>

        <?php if (isset($success_msg)): ?>
            <h2><?php print $success_msg; ?></h2>
        <?php endif; ?>

        <?php foreach ($page['script'] as $value): ?>
            <script <?php print $value; ?>></script>
        <?php endforeach; ?>
    </body>
</html>

