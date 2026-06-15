<?php
session_start();
include "../config/db.php";

/* ===== KIỂM TRA ĐĂNG NHẬP ADMIN ===== */
if (!isset($_SESSION['adminId'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<link rel="stylesheet" href="../css/styles.css">

<style>

/* HEADER */


.admin-header {
    background: #020617;
    padding: 15px 20px;
    color: #38bdf8;
    font-weight: bold;
}

/* NAV */
.admin-nav {
    background: #020617;
    padding: 10px 20px;
    display: flex;
    gap: 15px;
}

.admin-nav a {
    color: #e5e7eb;
    text-decoration: none;
    padding: 6px 10px;
    border-radius: 6px;
}

.admin-nav a:hover {
    background: #1e293b;
}

/* DASHBOARD GRID */
.admin-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    padding: 25px;
}

/* CARD */
.admin-card {
    text-decoration: none;
    color: white;
    padding: 25px;
    border-radius: 16px;
    text-align: center;
    transition: 0.3s;
    box-shadow: 0 10px 25px rgba(0,0,0,0.4);
}

/* ICON */
.card-icon {
    font-size: 30px;
    margin-bottom: 10px;
}

/* NUMBER */
.admin-card h2 {
    font-size: 32px;
    margin: 10px 0;
}

/* TEXT */
.admin-card p {
    font-size: 14px;
    opacity: 0.9;
}

/* HOVER */
.admin-card:hover {
    transform: translateY(-6px) scale(1.03);
    box-shadow: 0 20px 40px rgba(0,0,0,0.6);
}

/* MÀU RIÊNG */
.card-tour {
    background: linear-gradient(135deg, #22c55e, #16a34a);
}

.card-booking {
    background: linear-gradient(135deg, #facc15, #eab308);
    color: black;
}

.card-user {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
}

.card-review {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

</style>

</head>

<body>

<!-- HEADER -->
<header class="admin-header">
    Xin chào, <?php echo $_SESSION['adminName']; ?> 👋
</header>

<!-- MENU -->
<nav class="admin-nav">
    <a href="index.php">🏠 Trang chủ</a>
    <a href="quanlitour.php">Quản lí Tour</a>
    <a href="users.php"> Quản lí khách hàng</a>
     <a href="booking_list.php"> Tour được đặt</a>
    <a href="review_list.php"> Đánh giá</a>
    <a href="admin_chat_list.php">Chat</a>
     <a href="../index.php" class="logout-btn">Đăng xuất</a>
</nav>

<!-- DASHBOARD -->
<div class="admin-container">

    <!-- TOUR -->
    <a href="quanlitour.php" class="admin-card card-tour">
        <div class="card-icon">📦</div>
        <h2>
            <?php
            $q = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tbl_tours ");
            echo mysqli_fetch_assoc($q)['total'];
            ?>
        </h2>
        <p>Tổng số tour</p>
    </a>

    <!-- BOOKING -->
    <a href="booking_list.php" class="admin-card card-booking">
        <div class="card-icon">📑</div>
        <h2>
            <?php
            $q = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tbl_booking");
            echo mysqli_fetch_assoc($q)['total'];
            ?>
        </h2>
        <p>Lượt đặt tour</p>
    </a>

    <!-- USERS -->
    <a href="users.php" class="admin-card card-user">
        <div class="card-icon">👤</div>
        <h2>
            <?php
            $q = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tbl_users");
            echo mysqli_fetch_assoc($q)['total'];
            ?>
        </h2>
        <p>Người dùng</p>
    </a>

    <!-- REVIEWS -->
    <a href="review_list.php" class="admin-card card-review">
        <div class="card-icon">💬</div>
        <h2>
            <?php
            $q = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tbl_reviews");
            echo mysqli_fetch_assoc($q)['total'];
            ?>
        </h2>
        <p>Đánh giá</p>
    </a>

</div>

</body>
</html>