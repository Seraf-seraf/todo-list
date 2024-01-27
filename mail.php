<?php
use PHPMailer\PHPMailer\PHPMailer;

require_once 'database.php';
require_once 'functions.php';
require_once 'vendor/autoload.php';

$phpmailer = new PHPMailer();

$phpmailer->CharSet = 'utf-8';
$phpmailer->isSMTP();
$phpmailer->Host = ;
$phpmailer->SMTPAuth = true;

$phpmailer->Port = ;
$phpmailer->Username = '';
$phpmailer->Password = '';

$phpmailer->setFrom(''); 
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
