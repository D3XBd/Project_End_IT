<?php
include 'db.php';

// รับค่าจาก ESP32
$studentId = $_GET['studentId'];
$bottle = $_GET['bottle']; // จำนวนขวดที่เพิ่มเข้ามา

// ตรวจคะแนนปัจจุบัน
$sql = "SELECT points, bottles FROM users WHERE studentId='$studentId'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$currentPoints = $row['points'];
$currentBottles = $row['bottles'];

// คิดคะแนน เช่น 1 ขวด = 1 คะแนน
$newPoints = $currentPoints + ($bottle * 1);
$newBottles = $currentBottles + $bottle;

// อัปเดตกลับเข้า database
$update = "UPDATE users SET points='$newPoints', bottles='$newBottles' WHERE studentId='$studentId'";

if ($conn->query($update)) {
    echo "OK";
} else {
    echo "ERROR";
}
?>
