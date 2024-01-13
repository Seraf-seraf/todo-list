<?php 
require_once '../init.php';

if(!empty($user_id)) {
    header('Location: ../index.php');
    die();
}

$page_body_class = 'body-background';

$page_content = include_template('../templates/guest.php');

$layout_content = include_template(
    '../templates/layout.php', [
    'page_content' => $page_content,
    'page_body_class' => $page_body_class,
    'title' => 'Дела в порядке'
    ]
);

print($layout_content);
?>
