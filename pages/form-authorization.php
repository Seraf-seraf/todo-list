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
        }
    ];
    
    $user = filter_input_array(
        INPUT_POST, [
        'email' => FILTER_SANITIZE_EMAIL,
        'password' => FILTER_DEFAULT
        ], true
    );

    shielding_data($user);
    
    $required = ['email', 'password'];
    $errors = get_errors_messages($user, $required, $rules);

    $errors = array_filter($errors);

    $user_data = get_user_data_by_email($connect, $user['email']) ?? false;

    if (count($errors) == 0 && $user_data && password_verify($user['password'], $user_data['password'])) {
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['username'] = $user_data['username'];

        header('Location: ../index.php');
        die();
    } else {
        $errors['error'] = 'Вы ввели неверный email/пароль!';
    } 
}

$page_content = include_template(
    '../templates/auth.php', [
    'errors' => $errors ?? []
    ]
);
        
$layout_content = include_template(
    '../templates/layout.php', [
    'page_content' => $page_content,
    'title' => 'Авторизация',
    ]
);

print($layout_content);
