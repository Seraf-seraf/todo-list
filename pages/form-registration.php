<?php
require_once '../init.php';

if (!empty($user_id)) {
    header('Location: ../index.php');
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $rules = [
        'email' => function ($value) {
            return validate_email($value);
        },
        'password' => function ($value) {
            return validate_value_string($value, 8, 128);
        },
        'username' => function ($value) {
            return validate_value_string($value, 1, 64, true);
        }
    ];

    $user = filter_input_array(
        INPUT_POST, [
        'email' => FILTER_SANITIZE_EMAIL,
        'password' => FILTER_DEFAULT,
        'username' => FILTER_DEFAULT ], 
        true
    );

    shielding_data($user);

    $required = ['email', 'password', 'username'];
    $errors = get_errors_messages($user, $required, $rules);

    if (get_user_data_by_email($connect, $user['email'])) {
        $errors['email'] = 'Пользователь с таким email уже существует!';
    }

    $errors = array_filter($errors);

    if (count($errors) == 0) {
        $password = password_hash($user['password'], PASSWORD_DEFAULT);

        create_user($connect, $user['email'], $user['username'], $password);

        header('Location: /pages/form-authorization.php');
        die();
    }
}

$page_content = include_template(
    '../templates/registration.php', [
    'errors' => $errors ?? []]
);

$layout_content = include_template(
    '../templates/layout.php', [
    'page_content' => $page_content,
    'title' => 'Регистрация']
);

print($layout_content);
