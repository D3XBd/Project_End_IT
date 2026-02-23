<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'ADMIN') {
    echo "คุณไม่มีสิทธิ์เข้าใช้งานหน้านี้";
    exit;
}

$result = $conn->query("SELECT er.*, u.fullname 
                        FROM exchange_requests er
                        JOIN users u ON er.user_id = u.id
                        ORDER BY er.created_at DESC");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>Admin Panel</title>
<style>
body{
    font-family: Arial, sans-serif;
    background:#f4f6f9;
    margin:0;
}
.header{
    background:#2c3e50;
    color:white;
    padding:20px;
    text-align:center;
}
.container{
    width:90%;
    margin:30px auto;
}
.card{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
table{
    width:100%;
    border-collapse:collapse;
}
th, td{
    padding:12px;
    text-align:center;
}
th{
    background:#34495e;
    color:white;
}
tr:nth-child(even){
    background:#f2f2f2;
}
.status-pending{
    color:orange;
    font-weight:bold;
}
.status-approved{
    color:green;
    font-weight:bold;
}
.status-rejected{
    color:red;
    font-weight:bold;
}
.btn{
    padding:6px 12px;
    border:none;
    border-radius:5px;
    cursor:pointer;
    text-decoration:none;
    color:white;
}
.approve{
    background:green;
}
.reject{
    background:red;
}
.logout{
    display:inline-block;
    margin-top:20px;
    background:#555;
}
</style>
</head>

<body>

<div class="header">
    <h2>👑 Admin Control Panel</h2>
    <p>จัดการคำขอแลกคะแนน</p>
</div>

<div class="container">
<div class="card">

<table border="0">
<tr>
    <th>ชื่อผู้ใช้</th>
    <th>ของรางวัล</th>
    <th>Point</th>
    <th>สถานะ</th>
    <th>วันที่</th>
    <th>จัดการ</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?php echo $row['fullname']; ?></td>
    <td><?php echo $row['reward_name']; ?></td>
    <td><?php echo $row['point_used']; ?></td>

    <td>
        <?php 
        if($row['status']=='PENDING'){
            echo "<span class='status-pending'>PENDING</span>";
        } elseif($row['status']=='APPROVED'){
            echo "<span class='status-approved'>APPROVED</span>";
        } else {
            echo "<span class='status-rejected'>REJECTED</span>";
        }
        ?>
    </td>

    <td><?php echo $row['created_at']; ?></td>

    <td>
        <?php if($row['status']=='PENDING'): ?>
            <a class="btn approve" href="approve.php?id=<?php echo $row['id']; ?>">อนุมัติ</a>
            <a class="btn reject" href="reject.php?id=<?php echo $row['id']; ?>">ไม่อนุมัติ</a>
        <?php else: ?>
            -
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>

</table>

<a href="dashboard.php" class="btn logout">กลับหน้า Dashboard</a>

</div>
</div>

</body>
</html>
