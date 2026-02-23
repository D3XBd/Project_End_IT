<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit;
}

$userId = (int)$_SESSION['user']; // บังคับให้เป็นตัวเลข

if (!isset($_POST['reward'])) {
    header("Location: dashboard.php");
    exit;
}

$rewardData = explode("|", $_POST['reward']);

$rewardName = $rewardData[0];
$pointUsed  = (int)$rewardData[1];


// ✅ ใช้ prepare ปลอดภัยกว่า
$stmt = $conn->prepare("SELECT points FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows == 0) {
    echo "ไม่พบผู้ใช้";
    exit;
}

$user = $result->fetch_assoc();

if ($user['points'] < $pointUsed) {
    echo "<script>alert('Point ไม่พอ');window.location='dashboard.php';</script>";
    exit;
}


// บันทึกคำขอ (ยังไม่ตัด point)
$stmt2 = $conn->prepare("INSERT INTO exchange_requests (user_id, reward_name, point_used) VALUES (?,?,?)");
$stmt2->bind_param("isi", $userId, $rewardName, $pointUsed);
$stmt2->execute();

echo "<script>alert('ส่งคำขอแล้ว รอ Admin อนุมัติ');window.location='dashboard.php';</script>";
?>
