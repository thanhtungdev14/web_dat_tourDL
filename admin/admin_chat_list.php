<?php
session_start();
include "../config/db.php";

$result = mysqli_query($conn, "
    SELECT 
        u.userId,
        u.userName,
        MAX(c.createData) AS lastTime,
        SUBSTRING_INDEX(
            GROUP_CONCAT(c.messages ORDER BY c.createData DESC),
            ',', 1
        ) AS lastMessage
    FROM tbl_chat c
    JOIN tbl_users u ON c.userId = u.userId
    GROUP BY u.userId
    ORDER BY lastTime DESC
");

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách chat</title>
    <link rel="stylesheet" href="../chat/chat.css">
</head>
<body>

<header class="admin-header">

    <div class="header-left">
        <h1 style="font-size:18px;" >CHAT VỚI KHÁCH HÀNG</h1>
    </div>

    <div class="header-right">
        <span>Xin chào, <b><?= $_SESSION['adminName'] ?? 'Admin' ?></b></span>
        <a href="../index.php" class="logout-btn">Đăng xuất</a>
    </div>

</header>
        <a href="index.php" class="add-btn">Trở lại</a>


<div class="chat-list-container">

    <h2>💬 Danh sách chat với khách hàng</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="chat-list">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="chat-item">

                    <div class="chat-avatar">
                        👤
                    </div>

                    <div class="chat-info">
                        <div class="chat-username">
                            <?php echo htmlspecialchars($row['userName']); ?>
                        </div>

                        <div class="chat-last-msg">
                            <?php echo htmlspecialchars($row['lastMessage']); ?>
                        </div>
                    </div>

                    <div class="chat-action">
                        <a href="admin_chat.php?userId=<?php echo $row['userId']; ?>">
                            Vào chat
                        </a>
                    </div>

                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="no-chat">❌ Chưa có cuộc trò chuyện nào</p>
    <?php endif; ?>

</div>

</body>
</html>

