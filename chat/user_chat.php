<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['userId'])) {
    header("Location: ../auth/login.php");
    exit;
}

$userId   = intval($_SESSION['userId']);
$userName = $_SESSION['userName'];

if (isset($_POST['send'])) {
    $msg = trim($_POST['message']);
    if ($msg !== '') {
        $msg = mysqli_real_escape_string($conn, $msg);
        $ip  = $_SERVER['REMOTE_ADDR'];

        mysqli_query($conn, "
            INSERT INTO tbl_chat (userId, adminId, messages, readStatus, ipAdress)
            VALUES ($userId, NULL, '$msg', 'n', '$ip')
        ");
    }
}

$result = mysqli_query($conn, "
    SELECT c.*, u.userName
    FROM tbl_chat c
    JOIN tbl_users u ON c.userId = u.userId
    WHERE c.userId = $userId
    ORDER BY c.createData ASC
");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Chat với Admin</title>
<link rel="stylesheet" href="chat.css">
</head>
<body>
<header class="admin-header">

    <div class="header-left">
        <h1 style="font-size:18px;" >CHAT VỚI ADMIN</h1>
    </div>

    <div class="header-right">
        <span>Xin chào, <b><?= $_SESSION['userName'] ?? 'User' ?></b></span>
    </div>

</header>
        <a href="../index.php" class="add-btn">Trở lại</a>
<div class="chat-container">

    <div class="chat-header">
        💬 Chat với Admin
    </div>

    <div class="chat-box">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <?php if ($row['adminId'] === NULL): ?>
                <div class="chat user">
                    <strong><?= htmlspecialchars($userName) ?></strong>
                    <p><?= htmlspecialchars($row['messages']) ?></p>
                </div>
            <?php else: ?>
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
