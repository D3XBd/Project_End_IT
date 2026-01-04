<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("METHOD NOT ALLOWED");
}

if (!file_exists("active_user.txt")) {
    http_response_code(400);
    exit("NO ACTIVE USER");
}

$studentId = trim(file_get_contents("active_user.txt"));

if ($studentId === "") {
    http_response_code(400);
    exit("EMPTY USER");
}

if (!isset($_POST['bottle'], $_POST['coin'])) {
    http_response_code(400);
    exit("MISSING DATA");
}

$bottle = (int)$_POST['bottle'];
$coin   = (float)$_POST['coin'];

$sql = "UPDATE users 
        SET bottles = bottles + ?, 
            points = points + ?
        WHERE studentId = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ids", $bottle, $coin, $studentId);

$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "SUCCESS";
} else {
    echo "NO ROW UPDATED";
}
?>