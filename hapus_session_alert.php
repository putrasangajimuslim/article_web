<?php
session_start();
unset($_SESSION['error']);
unset($_SESSION['message']);
$msg = 'success';
header('Content-Type: application/json');
echo json_encode($msg);
