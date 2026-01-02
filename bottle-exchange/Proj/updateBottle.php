<?php
include 'db.php';

/* รับเฉพาะ POST */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("POST ONLY");
}

/* เช็กข้อมูลจาก ESP32 */
if (!isset($_POST['bottle']) || !isset($_POST['coin'])) {
    exit("NO DATA FROM ESP32");
}

/* เช็ก user ที่กำลังใช้งาน */
if (!file_exists("active_user.txt")) {
    exit("NO ACTIVE USER");
}

$studentId = trim(file_get_contents("active_user.txt"));

$bo
?>