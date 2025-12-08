<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Trim & Validate
    $studentId = trim($_POST['studentId']);
    $password = $_POST['password'];
    $fullname = trim($_POST['fullname']);
    $grade = trim($_POST['grade']);
    $major = trim($_POST['major']);

    if (empty($studentId) || empty($password) || empty($fullname)) {
        header("Location: signup.html?error=empty");
        exit;
    }

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // ตรวจสอบว่าซ้ำไหม
    $check = $conn->prepare("SELECT * FROM users WHERE studentId = ?");
    $check->bind_param("s", $studentId);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        header("Location: signup.html?error=exists");
        exit;
    }

    // บันทึกข้อมูล
    $stmt = $conn->prepare(
    "INSERT INTO users (studentId, password, fullname, grade, major, raw_password) 
     VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssssss", $studentId, $passwordHash, $fullname, $grade, $major, $password);


    if ($stmt->execute()) {
        header("Location: login.html?signup=success");
        exit;
    } else {
        // Log error แล้ว redirect
        error_log("Signup error: " . $stmt->error);
        header("Location: signup.html?error=fail");
        exit;
    }
}
?>