<?php
session_start();

if (!isset($_SESSION['studentId'])) {
    http_response_code(401);
    exit("NOT LOGIN");
}

file_put_contents("active_user.txt", $_SESSION['studentId']);
echo "ACTIVE SET";
?>