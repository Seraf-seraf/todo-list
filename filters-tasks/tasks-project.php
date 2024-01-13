<?php 
$project_name = filter_input(INPUT_GET, 'project_name', FILTER_DEFAULT);

if (isset($project_name)) {
    $project_name = shielding_data($project_name);

    $project_id = get_id_project($connect, $project_name, $user_id);

    if (!$project_id) {
        showError404Page();
        die();
    }

    $sql = 'SELECT TASK.id, TASK.task_name, TASK.date_create, TASK.file_url, TASK.task_completed, TASK.task_deadline '
         . 'FROM TASK '
         . 'JOIN PROJECT ON PROJECT.id = TASK.project_id WHERE PROJECT.project_name = ? '
         . 'ORDER BY task_completed ASC, task_deadline ASC';

    $stmt = mysqli_prepare($connect, $sql);
    $i = 2;
    mysqli_stmt_bind_param($stmt, 's', $project_name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
