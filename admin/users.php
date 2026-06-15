<?php
session_start();
include "../config/db.php";

/* ===== BẢO VỆ ADMIN ===== */
if (!isset($_SESSION['adminId'])) {
    header("Location: ../auth/login.php");
    exit;
}

/* ===== LẤY DANH SÁCH USER ===== */
$sql = "SELECT userId, userName, email, isActive FROM tbl_users ORDER BY userId DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">




<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body class="admin-add-body">

<header class="admin-header">
    <div class="header-left">
        <h1>Quản lí khách hàng</h1>
    </div>

    <div class="header-right">
        <span>Xin chào, <b><?php echo $_SESSION['adminName']; ?></b></span>
        <a href="../index.php" class="logout-btn">Đăng xuất</a>
    </div>
</header>

<nav class="admin-nav">
    <a href="index.php">Trở lại</a>
    <a href="users.php">👥 Người dùng</a>
    <a href="booking_list.php">Tour đã đặt</a>
     <a href="review_list.php"> Đánh giá </a>


    
</nav>

<div class="admin-table-container">

<?php if (mysqli_num_rows($result) > 0): ?>
<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên đăng nhập</th>
            <th>Email</th>
            <th>Trạng thái</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['userId']; ?></td>
            <td><?php echo htmlspecialchars($row['userName']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td>
                <?php if ($row['isActive'] == 'y'): ?>
                    <span class="status-active">Hoạt động</span>
                <?php else: ?>
                    <span class="status-block">Bị khóa</span>
                <?php endif; ?>
            </td>
           <td>
    <a href="user_bookings.php?userId=<?php echo $row['userId']; ?>"
       style="background:#0077cc;color:#fff;padding:6px 10px;border-radius:5px;">
       Xem tour
    </a>

    <?php if ($row['isActive'] == 'y'): ?>
        <a href="toggle_user.php?id=<?php echo $row['userId']; ?>&status=n"
           style="background:#ef4444;color:#fff;padding:6px 10px;border-radius:5px;margin-left:5px;"
           onclick="return confirm('Khóa người dùng này?')">
           Khóa
        </a>
    <?php else: ?>
        <a href="toggle_user.php?id=<?php echo $row['userId']; ?>&status=y"
           style="background:#22c55e;color:#fff;padding:6px 10px;border-radius:5px;margin-left:5px;"
           onclick="return confirm('Mở khóa người dùng này?')">
           Mở khóa
        </a>
    <?php endif; ?>
</td>

        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
    <p style="text-align:center">Chưa có người dùng nào</p>
<?php endif; ?>

</div>

</body>
</html>
