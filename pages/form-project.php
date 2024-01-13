<?php
require_once '../init.php';

if (empty($user_id)) {
    header('Location: /pages/guest-page.php');
    die();
}

$types_tasks = get_types_tasks($connect, $user_id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // функция get_errors_messages для составления сообщений об ошибках принимает массив
    $project = filter_input_array(INPUT_POST, ['name' => FILTER_DEFAULT], true);

    $rules = [  
        'name' => function ($value) use ($connect, $user_id) {
            return validate_create_project($connect, $user_id, $value);
        }
    ];

    shielding_data($project);

    $required = ['name'];

    $errors = get_errors_messages($project, $required, $rules);

    $errors = array_filter($errors);

    if (count($errors) == 0) {
        create_project($connect, $user_id, $project['name']);
        header('Location: ../index.php');
        die();
    }
}

$page_content = include_template(
    '../templates/add-project.php', [
    'errors' => $errors ?? [],
    'project' => $project ?? []
    ]
);

$layout_content = include_template(
    '../templates/layout.php', [
    'page_content' => $page_content,
    'user_id' => $user_id,
    'username' => $username,
    'title' => 'Добавить проект',
    'types_tasks' => $types_tasks
    ]
);

print($layout_content);
