<?php
//Есть фильтры, к ним еще добавляется условие, показывать выполненные задачи или нет
$date_filter = filter_input(INPUT_GET, 'date_filter', FILTER_DEFAULT);
$filters = ['all', 'today', 'tomorrow', 'passed'];

if($date_filter == null && !empty($project_name)) {
    $date_filter = 'all';
}

if(isset($date_filter)) {
    if(!in_array($date_filter, $filters)) {
        showError404Page();
    }

    if($date_filter == 'today') {
        if($show_completed) {
            $sql = 'SELECT TASK.id, TASK.task_name, TASK.date_create, TASK.file_url, TASK.task_completed, TASK.task_deadline '
             . 'FROM TASK '
             . 'WHERE user_id = ? AND task_deadline = CURDATE() '
             . 'ORDER BY task_completed ASC';
        } else {
            $sql = 'SELECT TASK.id, TASK.task_name, TASK.date_create, TASK.file_url, TASK.task_completed, TASK.task_deadline '
             . 'FROM TASK '
             . 'WHERE user_id = ? AND task_deadline = CURDATE() AND task_completed = 0 '
             . 'ORDER BY task_completed ASC';
        }
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $user_id);

    } elseif($date_filter == 'tomorrow') {
        if($show_completed) {
            $sql = 'SELECT TASK.id, TASK.task_name, TASK.date_create, TASK.file_url, TASK.task_completed, TASK.task_deadline '
             . 'FROM TASK '
             . 'WHERE user_id = ? AND task_deadline = DATE_ADD(CURDATE(), INTERVAL 1 DAY) '
             . 'ORDER BY task_completed ASC';
        } else {
            $sql = 'SELECT TASK.id, TASK.task_name, TASK.date_create, TASK.file_url, TASK.task_completed, TASK.task_deadline '
             . 'FROM TASK '
             . 'WHERE user_id = ? AND task_deadline = DATE_ADD(CURDATE(), INTERVAL 1 DAY) AND task_completed = 0 '
             . 'ORDER BY task_completed ASC';
        }
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $user_id);

    } elseif($date_filter == 'passed') {
        if($show_completed) {
            $sql = 'SELECT TASK.id, TASK.task_name, TASK.date_create, TASK.file_url, TASK.task_completed, TASK.task_deadline '
            .  'FROM TASK '
            .  'WHERE user_id = ? AND task_deadline < CURDATE() '
            .  'ORDER BY task_completed ASC, task_deadline ASC';
        } else {
            $sql = 'SELECT TASK.id, TASK.task_name, TASK.date_create, TASK.file_url, TASK.task_completed, TASK.task_deadline '
            .  'FROM TASK '
            .  'WHERE user_id = ? AND task_deadline < CURDATE() AND task_completed = 0 '
            .  'ORDER BY task_completed ASC, task_deadline ASC';
        }
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $user_id);

    } elseif($date_filter == 'all' && !empty($project_name)) {
        if($show_completed) {
            $sql = 'SELECT TASK.id, TASK.task_name, TASK.date_create, TASK.file_url, TASK.task_completed, TASK.task_deadline '
            .  'FROM TASK '
            .  'JOIN PROJECT ON PROJECT.id = TASK.project_id '
            .  'WHERE TASK.user_id = ? AND PROJECT.project_name = ? '
            .  'ORDER BY task_completed ASC, task_deadline ASC';
        } else {
            $sql = 'SELECT TASK.id, TASK.task_name, TASK.date_create, TASK.file_url, TASK.task_completed, TASK.task_deadline '
            .  'FROM TASK '
            .  'JOIN PROJECT ON PROJECT.id = TASK.project_id '
            .  'WHERE TASK.user_id = ? AND PROJECT.project_name = ? AND task_completed = 0 '
            .  'ORDER BY task_completed ASC, task_deadline ASC';
        }
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, 'is', $user_id, $project_name);

    } elseif($date_filter == 'all') {
        if($show_completed) {
            $sql = 'SELECT TASK.id, TASK.task_name, TASK.date_create, TASK.file_url, TASK.task_completed, TASK.task_deadline '
            .  'FROM TASK WHERE user_id = ? '
            .  'ORDER BY task_completed ASC, task_deadline ASC';
        } else {
            $sql = 'SELECT TASK.id, TASK.task_name, TASK.date_create, TASK.file_url, TASK.task_completed, TASK.task_deadline '
            .  'FROM TASK WHERE user_id = ? AND task_completed = 0 '
            .  'ORDER BY task_completed ASC, task_deadline ASC';
        }
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
