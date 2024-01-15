<?php
// Поиск на странцие
$search = filter_input(INPUT_GET, 'search-form', FILTER_DEFAULT);
$search = ($search !== null) ? trim($search) : null;

if(isset($search) && $search !== '') {
    $search = shielding_data($search);

	if($show_completed) {
		$sql = 'SELECT * FROM TASK WHERE MATCH (task_name) AGAINST(? IN BOOLEAN MODE) ORDER BY task_completed ASC, task_deadline ASC';
		$stmt = mysqli_prepare($connect, $sql);
		mysqli_stmt_bind_param($stmt, 's', $search);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
	} else {
		$sql = 'SELECT * FROM TASK WHERE MATCH (task_name) AGAINST(? IN BOOLEAN MODE) AND task_complete = 0 ORDER BY task_completed ASC, task_deadline ASC';
		$stmt = mysqli_prepare($connect, $sql);
		mysqli_stmt_bind_param($stmt, 's', $search);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
	}

	$tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
}