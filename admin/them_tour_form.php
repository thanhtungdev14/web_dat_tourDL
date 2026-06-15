<?php
include "../config/db.php";

if (isset($_POST['submit'])) {

    $title        = mysqli_real_escape_string($conn, $_POST['title']);
    $description  = mysqli_real_escape_string($conn, $_POST['description']);
    $priceAdult   = intval($_POST['priceAdult']);
    $priceChild   = intval($_POST['priceChild']);
    $quantify     = intval($_POST['quantify']);
    $duration     = mysqli_real_escape_string($conn, $_POST['duration']);
    $destination  = mysqli_real_escape_string($conn, $_POST['destination']);
    $itynerary    = mysqli_real_escape_string($conn, $_POST['itynerary']);
    $availability = intval($_POST['availability']);

    /* ================= UPLOAD ẢNH ================= */
    $imageName = $_FILES['images']['name'];
    $tmpName   = $_FILES['images']['tmp_name'];

    // Đổi tên ảnh tránh trùng
    $newImageName = time() . "_" . basename($imageName);

    // Thư mục lưu ảnh
    $uploadDir = "../images/";
    $uploadPath = $uploadDir . $newImageName;

    // Kiểm tra thư mục tồn tại
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    move_uploaded_file($tmpName, $uploadPath);

    /* ================= INSERT DB ================= */
    mysqli_query($conn, "
        INSERT INTO tbl_tours
        (title, description, images, priceAdult, priceChild, quantify, duration, destination, itynerary, availability)
        VALUES
        ('$title', '$description', '$newImageName', $priceAdult, $priceChild,
         $quantify, '$duration', '$destination', '$itynerary', $availability)
    ");

    header("Location: them_tour.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm tour mới</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body class="admin-add-body">

<!-- HEADER MỚI (KHÔNG ẢNH HƯỞNG CŨ) -->
<header class="admin-header" style="margin-bottom: 15px;">

    <div class="header-left">
        <h1 style="margin:0;">👤 Quản lí người dùng</h1>
    </div>

    <div class="header-right">
        <span>Xin chào, <b><?= $_SESSION['adminName'] ?? 'Admin' ?></b></span>

        <!-- giữ style cũ nếu có -->
        <a href="../index.php" class="logout-btn">Đăng xuất</a>
    </div>

</header>

<nav class="admin-nav">
    <a href="index.php">Trở lại</a>
  
</nav>

<div class="add-tour-container">

    <form method="post" class="add-tour-box" enctype="multipart/form-data">

        <h2>Thông tin tour</h2>

        <div class="form-group">
            <label>Tên tour</label>
            <input type="text" name="title" required>
        </div>

        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="description" rows="4" required></textarea>
        </div>

        <div class="form-group">
            <label>Ảnh tour</label>
            <input type="file" name="images" accept="image/*" required>
        </div>

        <div class="two-col">
            <div class="form-group">
                <label>Giá người lớn (VNĐ)</label>
                <input type="number" name="priceAdult" required>
            </div>

            <div class="form-group">
                <label>Giá trẻ em (VNĐ)</label>
                <input type="number" name="priceChild" required>
            </div>
        </div>

        <div class="two-col">
            <div class="form-group">
                <label>Số chỗ (quantify)</label>
                <input type="number" name="quantify" required>
            </div>

            <div class="form-group">
                <label>Thời gian</label>
                <input type="text" name="duration" placeholder="3N2Đ" required>
            </div>
        </div>

        <div class="form-group">
            <label>Điểm đến</label>
            <input type="text" name="destination" required>
        </div>

        <div class="form-group">
            <label>Lịch trình</label>
            <input type="text" name="itynerary">
        </div>

        <div class="form-group">
            <label>Trạng thái</label>
            <select name="availability">
                <option value="1">Hoạt động</option>
                <option value="0">Ẩn tour</option>
            </select>
        </div>

        <button type="submit" name="submit">💾 Lưu tour</button>

    </form>
</div>

</body>
</html>
