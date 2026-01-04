<?php
session_start();

if (!isset($_SESSION['studentId'])) {
    http_response_code(401);
    exit("NOT LOGIN");
}

file_put_contents("active_user.txt", $_SESSION['studentId']);
file_put_contents("exchange_state.txt", "EXCHANGE");

echo "START EXCHANGE";
