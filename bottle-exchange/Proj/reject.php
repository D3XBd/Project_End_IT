<?php
session_start();
include 'db.php';

if (!isset($_GET['id'])) {
    echo "ไม่พบคำขอ";
    exit;
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("UPDATE exchange_requests SET status='REJECTED' WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: admin.php");
exit;
?>
