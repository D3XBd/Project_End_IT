<?php
session_start();
include 'db.php';

// ถ้าไม่มี session user = ยังไม่ได้ login
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit;
}

$userId = $_SESSION['user'];

// ดึงข้อมูลจาก DB
$stmt = $conn->prepare("SELECT studentId, fullname, grade, major, bottles, points FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc(); // <-- ตรงนี้สำคัญมาก

if (!$user) {
    echo "ไม่พบข้อมูลผู้ใช้!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container">
    <h2>ยินดีต้อนรับ <?php echo $user['fullname']; ?></h2>
    <p><strong>รหัสนักศึกษา:<?php echo $user['studentId'];?></p>
    <p><strong>ระดับชั้น:</strong> <?php echo $user['grade']; ?></p>
    <p><strong>สาขา:</strong> <?php echo $user['major']; ?></p>
    <p><strong>จำนวขวด:</strong> <?php echo $user['bottles']; ?></p>
    <p><strong>คะแนน:</strong> <?php echo $user['points']; ?></p>
    <a href="logout.php" class="btn">ออกจากระบบ</a>
  </div>
</body>
</html>