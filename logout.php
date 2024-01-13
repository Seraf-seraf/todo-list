<?php
session_start();

$_SESSION = [];

header('Location: /pages/guest-page.php');
