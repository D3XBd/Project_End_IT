<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $studentId = $_POST['studentId'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM users WHERE studentId = ?");
  $stmt->bind_param("s", $studentId);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['studentId'] = $user['studentId'];
    $_SESSION['user'] = $user;
    header("Location: dashboard.php");
  } else {
    echo "<script>alert('รหัสนักศึกษาหรือรหัสผ่านไม่ถูกต้อง'); window.location='login.html';</script>";
  }
}
?>