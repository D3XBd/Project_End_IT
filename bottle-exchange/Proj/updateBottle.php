<?php
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("METHOD NOT ALLOWED");
}

if (!file_exists("active_user.txt")) {
    exit("NO ACTIVE USER FILE");
}

$studentId = trim(file_get_contents("active_user.txt"));
echo "USER=" . $studentId . "\n";

if ($studentId === "") {
    exit("EMPTY USER");
}

if (!isset($_POST['bottle'], $_POST['coin'])) {
    exit("MISSING DATA");
}

$bottle = (int)$_POST['bottle'];
$coin   = (float)$_POST['coin'];

$stmt = $conn->prepare(
  "UPDATE users SET bottles = bottles + ?, points = points + ? WHERE studentId = ?"
);

$stmt->bind_param("ids", $bottle, $coin, $studentId);

$stmt->execute();

echo "AFFECTED: " . $stmt->affected_rows . "\n";

if ($stmt->affected_rows > 0) {
    echo "SUCCESS";
} else {
    echo "NO ROW UPDATED";
}
