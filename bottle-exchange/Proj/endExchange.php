<?php
session_start();

if (!isset($_SESSION['studentId'])) {
    http_response_code(401);
    exit("NOT LOGIN");
}

if (file_exists("active_user.txt")) {
    unlink("active_user.txt");
}

file_put_contents("exchange_state.txt", "IDLE");

echo "END EXCHANGE";
