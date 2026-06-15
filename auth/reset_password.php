<?php
session_start();
include "../config/db.php";

$email = $_GET['email'] ?? '';
$msg = "";

if (!$email) {
    die("Thiếu email!");
}

if (isset($_POST['reset'])) {
    $pass = $_POST['pass'];

    // mã hóa password
    $hashed = password_hash($pass, PASSWORD_DEFAULT);

    mysqli_query($conn, "
        UPDATE tbl_users 
        SET passWord = '$hashed'
        WHERE email = '$email'
    ");

    $msg = "✅ Đổi mật khẩu thành công!";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đặt lại mật khẩu</title>
<link rel="stylesheet" href="../css/styles.css">
</head>

<body class="user-login-body">

<div class="user-login-container">
    <div class="user-login-box">

        <h2>🔒 Đặt lại mật khẩu</h2>

        <?php if ($msg): ?>
            <div class="login-error" style="background:#e0ffe0;color:#0a7a0a;">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Mật khẩu mới</label>
                <input type="password" name="pass" required>
            </div>

            <button name="reset">Đổi mật khẩu</button>
        </form>

        <div class="login-extra">
            <a href="login.php">⬅ Quay lại đăng nhập</a>
        </div>

    </div>
</div>

</body>
</html>