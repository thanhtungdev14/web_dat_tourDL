<?php
include "../config/db.php";

if (isset($_POST['reset'])) {

    $email = trim($_POST['email']);
    $newpass = trim($_POST['password']);

    // kiểm tra email tồn tại
    $sql = "SELECT * FROM tbl_users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {

        // hash mật khẩu mới
        $hash = password_hash($newpass, PASSWORD_DEFAULT);

        // cập nhật
       if (mysqli_query($conn, "UPDATE tbl_users SET passWord='$hash' WHERE email='$email'")) {
    $success = "✅ Đổi mật khẩu thành công!";
} else {
    die("Lỗi SQL: " . mysqli_error($conn));
}
    } else {
        $error = "❌ Email không tồn tại!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quên mật khẩu</title>
<link rel="stylesheet" href="../css/styles.css">
</head>

<body class="register-body">

<div class="register-container">
    <h2>QUÊN MẬT KHẨU</h2>

    <?php if (!empty($error)): ?>
        <div class="register-error"><?= $error ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div style="background:#d4edda;color:#155724;padding:10px;border-radius:6px;">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <form method="post">

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Mật khẩu mới</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" name="reset">Đổi mật khẩu</button>

    </form>

    <div class="register-extra">
        <a href="login.php">← Quay lại đăng nhập</a>
    </div>
</div>

</body>
</html>