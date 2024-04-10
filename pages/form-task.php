<?php 
require_once '../init.php';

if (empty($user_id)) {
    header('Location: /pages/guest-page.php');
    die();
}

$types_tasks = get_types_tasks($connect, $user_id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required = ['name', 'project', 'date'];
    
    $rules = [
        'name' => function ($value) {
            return validate_value_string($value, 2, 64, true);
        },
        'project' => function ($value) use ($types_tasks) {
            return validate_project($value, $types_tasks);
        },
        'date' => function ($value) {
            return validate_date($value);
        }
    ];
    
    $task = filter_input_array(
        INPUT_POST, [
        'name' => FILTER_DEFAULT,
        'project' => FILTER_DEFAULT,
        'date' => FILTER_DEFAULT
        ], true
    );
    
    shielding_data($task);

    $errors = get_errors_messages($task, $required, $rules);

    $errors = array_filter($errors);

    if (count($errors) === 0) {
        if (!empty($_FILES['file']['name'])) {
            $tmp_name = $_FILES['file']['tmp_name'];
            $path = $_FILES['file']['tmp_name'];
    
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $tmp_name);

            switch ($file_type) {
                case 'image/jpeg':
                    $extension = '.jpeg';
                    break;
                case 'image/png':
                    $extension = '.png';
                    break;
                default:
                    $errors['file'] = 'Допустимые форматы файлов: png, jpg';
            }
    
            if (isset($extension)) {
                $filename = uniqid() . $extension;
                $task['path'] = 'uploads/' . $filename;
                move_uploaded_file($path, '../uploads/' . $filename);
            }
        }

        if (count($errors) === 0) {
            $project_id = get_id_project($connect, $task['project'], $user_id);

            create_task($connect, $project_id, $user_id, $task);
    
            header('Location: ../index.php');
            die();
        }
    }
}

$page_content = include_template(
    '../templates/add-task.php', [
    'types_tasks' => $types_tasks,
    'task' => $task ?? [],
    'errors' => $errors ?? []
    ]
);

$layout_content = include_template(
    '../templates/layout.php', [
    'title' => 'Добавить задачу',
    'page_content' =>  $page_content,
    'types_tasks' => $types_tasks,
    'user_id' => $user_id,
    'username' => $username,
    ]
);

print($layout_content);
?>
