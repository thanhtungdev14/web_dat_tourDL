<?php
session_start();
include "../config/db.php";

/* ================= CHECK ADMIN LOGIN ================= */
if (!isset($_SESSION['adminId'])) {
    header("Location: login.php");
    exit;
}

$adminId = intval($_SESSION['adminId']);
$userId  = intval($_GET['userId'] ?? 0);

if ($userId <= 0) {
    die("❌ Không tìm thấy user");
}

/* ================= GỬI TIN NHẮN ADMIN ================= */
if (isset($_POST['send'])) {
    $msg = trim($_POST['message']);
    if ($msg != '') {
        $msg = mysqli_real_escape_string($conn, $msg);
        $ip  = $_SERVER['REMOTE_ADDR'];

        mysqli_query($conn, "
            INSERT INTO tbl_chat (userId, adminId, messages, readStatus, ipAdress)
            VALUES ($userId, $adminId, '$msg', 'y', '$ip')
        ");
    }
}

/* ================= LẤY CHAT ================= */
$result = mysqli_query($conn, "
    SELECT 
        c.*,
        u.userName AS user_name,
        a.userName AS admin_name
    FROM tbl_chat c
    JOIN tbl_users u ON c.userId = u.userId
    LEFT JOIN tbl_admin a ON c.adminId = a.idAdmin
    WHERE c.userId = $userId
    ORDER BY c.createData ASC
");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Admin Chat</title>
<link rel="stylesheet" href="../chat/chat.css">
</head>

<header class="admin-header">

    <div class="header-left">
        <h1 style="font-size:18px;" >CHAT VỚI KHÁCH HÀNG</h1>
    </div>

    <div class="header-right">
        <span>Xin chào, <b><?= $_SESSION['adminName'] ?? 'Admin' ?></b></span>
        <a href="../index.php" class="logout-btn">Đăng xuất</a>
    </div>

</header>
        <a href="admin_chat_list.php" class="add-btn">Trở lại</a>

<body>

<div class="chat-container">

    <div class="chat-header">
        💬 Chat với khách hàng
    </div>

    <div class="chat-box">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>

            <?php if ($row['adminId'] == NULL): ?>
                <!-- USER -->
                <div class="chat user">
                    <strong><?= htmlspecialchars($row['user_name']) ?></strong>
                    <p><?= htmlspecialchars($row['messages']) ?></p>
                </div>
            <?php else: ?>
                <!-- ADMIN -->
                <div class="chat admin">
                    <strong>Admin</strong>
                    <p><?= htmlspecialchars($row['messages']) ?></p>
                </div>
            <?php endif; ?>

        <?php endwhile; ?>
    </div>

    <form method="post" class="chat-form">
        <input type="text" name="message" placeholder="Nhập tin nhắn..." required>
        <button name="send">Gửi</button>
    </form>

</div>

</body>
</html>
