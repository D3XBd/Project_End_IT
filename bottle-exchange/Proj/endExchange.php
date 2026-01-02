<?php
session_start();

/* ต้อง login ก่อน */
if (!isset($_SESSION['studentId'])) {
    http_response_code(401);
    exit("NOT LOGIN");
}

/* ลบ active user */
if (file_exists("active_user.txt")) {
    unlink("active_user.txt");
}

echo "END EXCHANGE";
?>