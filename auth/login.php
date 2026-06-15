<?php
session_start();
include "../config/db.php";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    /* ================= THÊM: KIỂM TRA ADMIN ================= */
    $sqlAdmin = "SELECT * FROM tbl_admin WHERE userName='$username'";
    $resultAdmin = mysqli_query($conn, $sqlAdmin);

    if ($admin = mysqli_fetch_assoc($resultAdmin)) {
        // Nếu mật khẩu admin đang để dạng thường
        if ($password === $admin['passWord']) {
            $_SESSION['adminId']   = $admin['idAdmin'];
            $_SESSION['adminName'] = $admin['userName'];
            header("Location: ../admin/index.php");
            exit;
        }
    }
    /* ================= HẾT PHẦN THÊM ================= */


    /* ================= CODE CŨ CỦA BẠN (GIỮ NGUYÊN) ================= */
    $sql = "SELECT * FROM tbl_users WHERE userName='$username' AND isActive='y'";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['passWord'])) {
            $_SESSION['userId'] = $row['userId'];
            $_SESSION['userName'] = $row['userName'];
            header("Location: ../index.php");
            exit;
        }
    }

    $error = "❌ Sai tài khoản hoặc mật khẩu";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body class="register-body">


<!-- FORM ĐĂNG NHẬP -->
<div class="register-container">

    <h2>ĐĂNG NHẬP</h2>

    <?php if (!empty($error)): ?>
        <div class="register-error">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="post">

        <div class="form-group">
            <label>Tên đăng nhập</label>
            <input type="text" name="username" required>
        </div>

        <div class="form-group">
            <label>Mật khẩu</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" name="login">Đăng nhập</button>

    </form>

    <div class="register-extra">
        Chưa có tài khoản?
        <a href="register.php">Đăng ký ngay</a>
    </div>
<div class="register-extra">
        <a href="register.php"><a href="forgot_password.php">Quên mật khẩu?</a>
 </a>
    </div>
</div>


</div>

</body>
</html>
