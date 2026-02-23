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
    <p><strong>จำนวนขวด:</strong> <?php echo $user['bottles']; ?></p>
    <p><strong>Point:</strong> <?php echo $user['points']; ?></p>
    <button onclick="startExchange()">เริ่มแลกขวด</button>
<br>
<script>
function startExchange() {
  fetch("setActiveUser.php")
    .then(res => res.text())
    .then(data => alert("เริ่มแลกขวดแล้ว"));
}
</script>
<button onclick="endExchange()" style="background:red;color:white;">
  จบการแลก
</button>
<br>
<script>
function endExchange() {
  fetch("endExchange.php")
    .then(res => res.text())
    .then(data => {
      alert("จบการแลกเรียบร้อย");
      location.reload();
    });
}
</script>
<?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN'): ?>
    <hr>
    <div style="margin-top:15px;">
        <a href="admin.php" 
           style="background:#2c3e50;
                  color:white;
                  padding:10px 20px;
                  border-radius:8px;
                  text-decoration:none;
                  display:inline-block;">
            👑 ไปหน้า Admin
        </a>
    </div>
<?php endif; ?>

<br>
<hr>
<h3>🎁 แลกของรางวัล</h3>

<form action="request_exchange.php" method="POST">
    <button type="submit" name="reward" value="น้ำ 1 ขวด|10">
        10 Point แลก น้ำ 1 ขวด
    </button>
</form>

<br>

<form action="request_exchange.php" method="POST">
    <button type="submit" name="reward" value="คะแนนกิจกรรม 1 ชม|15">
        15 Point แลก คะแนนกิจกรรม 1 ชม
    </button>
</form>

<hr>
<h3>📜 ประวัติการแลก</h3>

<?php
$userId = (int)$_SESSION['user']; // บังคับให้เป็นตัวเลข

$stmt = $conn->prepare("SELECT reward_name, point_used, status, created_at 
                        FROM exchange_requests 
                        WHERE user_id = ? 
                        ORDER BY created_at DESC");

if($stmt){
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()){
        echo "<p>
        {$row['reward_name']} - {$row['point_used']} Point
        <b>Status:</b> {$row['status']}
        </p>";
    }
}else{
    echo "ยังไม่มีประวัติการแลก";
}
?>
<hr>

<br>
    <a href="logout.php" class="btn">ออกจากระบบ</a>
  </div>
</body>
</html>