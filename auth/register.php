<?php
include "../config/db.php";

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    /* ========== THÊM: KIỂM TRA TRÙNG USER / EMAIL ========== */
    $checkSql = "SELECT * FROM tbl_users 
                 WHERE userName='$username' OR email='$email'";
    $checkResult = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        $error = "❌ Tên đăng nhập hoặc email đã tồn tại!";
    } else {

        /* ========== CODE CŨ GIỮ NGUYÊN ========== */
        $sql = "INSERT INTO tbl_users (userName, passWord, email, isActive)
                VALUES ('$username', '$password', '$email', 'y')";

        if (mysqli_query($conn, $sql)) {
            header("Location: login.php");
            exit;
        } else {
            $error = "❌ Lỗi đăng ký!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body class="register-body">

    <div class="register-container">

        <h2>Đăng ký tài khoản</h2>

        <?php if (!empty($error)): ?>
            <div class="register-error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="post">

            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="Nhập tên đăng nhập"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="example@email.com"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••"
                    required
                >
            </div>

            <button type="submit" name="register">
                Đăng ký
            </button>

        </form>

        <div class="register-extra">
            <p>
                Đã có tài khoản?
                <a href="login.php">Đăng nhập</a>
            </p>
        </div>

    </div>

</body>
</html>


<?php if (!empty($error)) echo "<p style='color:red'>$error</p>"; ?>
