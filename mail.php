<?php
use PHPMailer\PHPMailer\PHPMailer;

require_once 'database.php';
require_once 'functions.php';
require_once '/home/f0903978/vendor/autoload.php';

$phpmailer = new PHPMailer();

$phpmailer->CharSet = 'utf-8';
$phpmailer->isSMTP();
$phpmailer->Host = 'smtp.mail.ru';
$phpmailer->SMTPAuth = true;

$phpmailer->Port = 587;
$phpmailer->Username = 'todo_list@inbox.ru';
$phpmailer->Password = 'Ab5xQy09sHDsBmrpL1H9';

$phpmailer->setFrom('todo_list@inbox.ru'); 
$phpmailer->isHTML(true);                              

$users_tasks_in_day = get_user_tasks_in_day($connect);

foreach ($users_tasks_in_day as $user) {
    $phpmailer->addAddress($user['email']);
    $username = $user['username'];
    $tasks_in_day = $user['tasks_in_day'];

    $current_date = date_format(date_create(), 'd.m.Y');

    $mail_message = include_template(
        'templates/tasks-of-day-mail.php', [
        'username' => $username,
        'tasks_in_day' => $tasks_in_day,
        'current_date' => $current_date
        ]
    );

    $phpmailer->Subject = 'Ваши задачи на сегодня';
    $phpmailer->Body = $mail_message;

    $phpmailer->send();
}
