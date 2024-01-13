<?php
require_once 'init.php';
require_once 'get-user-tasks.php';
require_once 'filters-tasks/tasks-project.php';
require_once 'filters-tasks/tasks-date.php';
require_once 'filters-tasks/search-tasks.php';
require_once 'filters-tasks/complete-task.php';

if (!$user_id) {
    header('Location: /pages/guest-page.php');
    die();
}

$types_tasks = get_types_tasks($connect, $user_id);

// Пагинация
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
$page = $page ? strip_tags(htmlspecialchars($page)) : 1;
$count_tasks_on_page = 5;
$offset = ($page - 1) * $count_tasks_on_page;
$page_count = ceil(count($tasks) / $count_tasks_on_page);

if ($page - 1 > $page_count) {
    $page = 1;
}

$tasks = array_slice($tasks, $offset, $count_tasks_on_page, true);

$params = [
'show_completed' => $show_completed,
'tasks' => $tasks,
'page' => $page,
'page_count' => $page_count,
'project_name' => $project_name,
'search' => $search,
'date_filter' => $date_filter
];

$page_content = include_template('templates/main.php', $params);

$params['title'] = 'Дела в порядке';
$params['types_tasks'] = $types_tasks;
$params['page_content'] = $page_content;
$params['user_id'] = $user_id;
$params['username'] = $username;

$layout_content = include_template('templates/layout.php', $params);

print($layout_content);
