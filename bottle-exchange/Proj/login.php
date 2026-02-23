<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $studentId = $_POST['studentId'];
    $password  = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE studentId = ?");
    $stmt->bind_param("s", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['studentId'] = $user['studentId'];
            $_SESSION['user']      = $user['id'];
            $_SESSION['role']      = $user['role'];

            if ($user['role'] === 'ADMIN') {
                header("Location: admin.php");
            } else {
                header("Location: dashboard.php");
            }

            exit; // 🔥 สำคัญมาก ต้องมี
        }
    }

    echo "<script>alert('รหัสนักศึกษาหรือรหัสผ่านไม่ถูกต้อง'); window.location='login.html';</script>";
    exit;
}
?>
