<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'ADMIN') {
    exit("ไม่มีสิทธิ์");
}

if (!isset($_GET['id'])) {
    exit("ไม่พบคำขอ");
}

$id = (int)$_GET['id'];


// ดึงข้อมูลคำขอ
$stmt = $conn->prepare("SELECT * FROM exchange_requests WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows == 0) {
    echo "ไม่พบข้อมูลคำขอ";
    exit;
}

$request = $result->fetch_assoc();

$userId = $request['user_id'];
$pointUsed = $request['point_used'];

// ใช้ transaction กันข้อมูลพัง
$conn->begin_transaction();

try {

    // ตัด point
    $stmt2 = $conn->prepare("UPDATE users SET points = points - ? WHERE id = ?");
    $stmt2->bind_param("ii", $pointUsed, $userId);
    $stmt2->execute();

    // เปลี่ยนสถานะ
    $stmt3 = $conn->prepare("UPDATE exchange_requests SET status='APPROVED' WHERE id = ?");
    $stmt3->bind_param("i", $id);
    $stmt3->execute();

    $conn->commit();

    header("Location: admin.php");
    exit;

} catch (Exception $e) {
    $conn->rollback();
    echo "เกิดข้อผิดพลาด";
}
?>
