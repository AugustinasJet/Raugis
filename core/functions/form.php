<?php

/**
 * Gauname saugu patikrinta user input.
 * 
 * @param type $form
 * @return type
 */
function get_safe_input($form) {
    $filtro_parametrai = [
        'action' => FILTER_SANITIZE_SPECIAL_CHARS
    ];
    foreach ($form['fields'] as $field_id => $value) {
        $filtro_parametrai[$field_id] = FILTER_SANITIZE_SPECIAL_CHARS;
    }
    return filter_input_array(INPUT_POST, $filtro_parametrai);
}

/**
 * Patikriname ar formoje esancios validacijos funkcijos yra teisingos ir iskvieciame ju funkcijas(not empty, not a number).
 * 
 * @param type $safe_input
 * @param type $form
 * @return boolean
 * @throws Exception
 */
function validate_form($safe_input, &$form) {
    $success = true;
    foreach ($form['fields'] as $field_id => &$field) {
        foreach ($field['validate'] as $validator) {
            if (is_callable($validator)) {
                $field['id'] = $field_id;

                if (!$validator($safe_input[$field_id], $field, $safe_input)) {
                    $success = false;
                    break;
                }
            } else {
                throw new Exception(strtr('Not callable @validator function', [
                    '@validator' => $validator
                ]));
            }
        }
    }
    if ($success) {
        $form['validate'] = $form['validate'] ?? [];

        foreach ($form['validate'] as $validator) {
            if (is_callable($validator)) {
                if (!$validator($safe_input, $form)) {
                    $success = false;
                    break;
                }
            } else {
                throw new Exception(strtr('Not callable @validator function', [
                    '@validator' => $validator
                ]));
            }
        }
    }

    if ($success) {
        foreach ($form['callbacks']['success'] as $callback) {
            if (is_callable($callback)) {
                $callback($safe_input, $form);
            } else {
                throw new Exception(strtr('Not callable @function function', [
                    '@function' => $callback
                ]));
            }
        }
    } else {
        foreach ($form['callbacks']['fail'] as $callback) {
            if (is_callable($callback)) {
                $callback($safe_input, $form);
            } else {
                throw new Exception(strtr('Not callable @function function', [
                    '@function' => $callback
                ]));
            }
        }
    }

    return $success;
}

/**
 * Checks if field is empty
 * 
 * @param string $field_input
 * @param array $field $form Field
 * @return boolean
 */
function validate_not_empty($field_input, &$field, $safe_input) {
    if (strlen($field_input) == 0) {
        $field['error_msg'] = strtr('Neuzpildei @field', ['@field' => $field['label']
        ]);
    } else {
        return true;
    }
}

/**
 * Checks if field is a number
 * 
 * @param string $field_input
 * @param array $field $form Field
 * @return boolean
 */
function validate_is_number($field_input, &$field, $safe_input) {
    if (!is_numeric($field_input)) {
        $field['error_msg'] = strtr('Jobans/a tu buhurs/gazele, '
                . 'nes @field nera skaicius!', ['@field' => $field['label']
        ]);
    } else {
        return true;
    }
}

function validate_file($field_input, &$field, &$safe_input) {
    $file = $_FILES[$field['id']] ?? false;

    if ($file) {
        if ($file['error'] == 0) {
            $safe_input[$field['id']] = $file;
            return true;
        }
    }
    $field['error_msg'] = 'Nenurodei fotkes';
}

function validate_email($field_input, &$field, $safe_input) {
    if (!preg_match("/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/", $field_input)) {
        $field['error_msg'] = 'Blogas email';
    } else {
        return true;
    }
}

function validate_age($field_input, &$field, $safe_input) {
    if ($field_input <= 0) {
        $field['error_msg'] = 'Negali buti neigiamo amziaus';
    } else {
        return true;
    }
}

function validate_contains_space($field_input, &$field, $safe_input) {
    if ($field_input == trim($field_input) && strpos($field_input, ' ') !== false) {
        return true;
    } else {
        $field['error_msg'] = 'Nera tarpu tarp vardo ir pavardes';
    }
}

function validate_char($field_input, &$field, $safe_input) {
    $min_char = 4;

    if (mb_strlen($field_input) < $min_char) {
        $field['error_msg'] = 'Per trumpas vardas';
    } else {
        return true;
    }
}

function validate_user_exists($field_input, &$field, $safe_input) {
    $db = new \Core\FileDB(DB_FILE);
    $user = new \Core\User\User();
    $user->setEmail($field_input);
    $repository = new \Core\User\Repository($db, TABLE_USERS);


    if (!$repository->exists($user)) {
        return true;
    } else {
        $field['error_msg'] = 'toks useris jau egzistuoja';
    }
}

function validate_select_option($field_input, &$field, $safe_input) {
    if (array_key_exists($field_input, $field['options'])) {
        return true;
    } else {
        $field['error_msg'] = 'sito eroro tikriausiai niekada neismes';
    }
}




