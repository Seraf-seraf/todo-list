<?php
$task_id = filter_input(INPUT_GET, 'task_id', FILTER_VALIDATE_INT);
$check = filter_input(INPUT_GET, 'check', FILTER_VALIDATE_INT);

$show_completed = filter_input(INPUT_GET, 'show_completed', FILTER_VALIDATE_INT);

if(isset($task_id) && isset($check)) {
    $task_id = shielding_data($task_id);
    $check = shielding_data($check);

    $sql = 'UPDATE TASK SET task_completed = ? WHERE user_id = ? AND id = ?';
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, 'iii', $check, $user_id, $task_id);
    mysqli_stmt_execute($stmt);
}

if(isset($show_completed)) {
    $show_completed = shielding_data($show_completed);

    $show_completed = $show_completed ? 1 : 0;
}

?>