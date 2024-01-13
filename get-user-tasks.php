<?php
require_once 'database.php';
require_once 'session.php';
require_once 'filters-tasks/complete-task.php';

if (isset($user_id)) {
    $sql = 'SELECT TASK.id, TASK.task_name, TASK.date_create, TASK.file_url, TASK.task_completed, TASK.task_deadline '
         . 'FROM TASK '
         . 'WHERE user_id = ? AND task_completed = 0 '
         . 'ORDER BY task_deadline ASC';
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    
    
    if ($show_completed) {
        $sql = 'SELECT TASK.id, TASK.task_name, TASK.date_create, TASK.file_url, TASK.task_completed, TASK.task_deadline '
            .  'FROM TASK '
            .  'WHERE user_id = ? '
            .  'ORDER BY task_completed ASC, task_deadline ASC';
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
}