<?php
session_start();
include "../config/db.php";

/* ================= CẬP NHẬT ================= */
if (isset($_POST['update'])) {

    $tourId = intval($_POST['tourId']);

    $title        = mysqli_real_escape_string($conn, $_POST['title']);
    $description  = mysqli_real_escape_string($conn, $_POST['description']);
    $quantify     = intval($_POST['quantify']);
    $priceAdult   = floatval($_POST['priceAdult']);
    $priceChild   = floatval($_POST['priceChild']);
    $duration     = mysqli_real_escape_string($conn, $_POST['duration']);
    $destination  = mysqli_real_escape_string($conn, $_POST['destination']);
    $availability = intval($_POST['availability']);
    $itynerary    = mysqli_real_escape_string($conn, $_POST['itynerary']);

    // Ảnh cũ
    $imageName = $_POST['old_image'];

    // Nếu chọn ảnh mới
    if (!empty($_FILES['images']['name'])) {
        $fileName = time() . "_" . basename($_FILES['images']['name']);
        $uploadPath = "../uploads/tours/" . $fileName;

        if (move_uploaded_file($_FILES['images']['tmp_name'], $uploadPath)) {
            $imageName = $fileName;
        }
    }

    mysqli_query($conn, "
        UPDATE tbl_tours SET
            title='$title',
            description='$description',
            images='$imageName',
            quantify=$quantify,
            priceAdult=$priceAdult,
            priceChild=$priceChild,
            duration='$duration',
            destination='$destination',
            availability=$availability,
            itynerary='$itynerary'
        WHERE tourId=$tourId
    ");

    // 👉 GIỮ NGUYÊN NHƯ CODE CŨ
    header("Location: sua_tour.php?tourId=$tourId");
    exit;
}

/* ================= LẤY TOUR ================= */
if (!isset($_GET['tourId'])) {
    header("Location: sua_tour.php");
    exit;
}

$tourId = intval($_GET['tourId']);
$rs = mysqli_query($conn, "SELECT * FROM tbl_tours WHERE tourId=$tourId");
$tour = mysqli_fetch_assoc($rs);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Sửa tour</title>

<style>

.admin-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 25px;
  background: linear-gradient(135deg, #0ea5e9, #2563eb);
  color: white;
  border-radius: 0 0 12px 12px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}

*{box-sizing:border-box}
body.admin-add-body{
    font-family:Arial,sans-serif;
    background:#0f172a;
    color:#e5e7eb;
}
.admin-header{
    background:linear-gradient(135deg,#0ea5e9,#2563eb);
    padding:20px;
    text-align:center;
    color:white;
}
.admin-nav{
    background:#020617;
    padding:12px;
    text-align:center;
}
.admin-nav a{
    color:#38bdf8;
    margin:0 12px;
    text-decoration:none;
    font-weight:bold;
}
.add-tour-container{
    display:flex;
    justify-content:center;
    padding:30px;
}
.add-tour-box{
    background:#020617;
    width:100%;
    max-width:700px;
    padding:30px;
    border-radius:16px;
}
.form-group{margin-bottom:15px}
label{display:block;margin-bottom:6px}
input,textarea,select{
    width:100%;
    padding:10px;
    border-radius:8px;
    border:1px solid #1e293b;
    background:#020617;
    color:white;
}
.two-col{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
}
button{
    width:100%;
    padding:14px;
    background:linear-gradient(135deg,#0ea5e9,#2563eb);
    color:white;
    border:none;
    border-radius:30px;
    font-size:16px;
    cursor:pointer;
}
img{margin-top:10px;border-radius:10px}
@media(max-width:768px){
    .two-col{grid-template-columns:1fr}
}
</style>
</head>

<body class="admin-add-body">

<header class="admin-header">

    <div class="header-left">
        <h1 style="font-size:18px;" >SỬA TOUR</h1>
    </div>

    <div class="header-right">
        <span>Xin chào, <b><?= $_SESSION['adminName'] ?? 'Admin' ?></b></span>
    </div>

</header>
<nav class="admin-nav">
    <a href="index.php">🏠 Trang Chủ</a>
</nav>

<div class="add-tour-container">
<form method="post" enctype="multipart/form-data" class="add-tour-box">

<h2>Thông tin tour</h2>

<input type="hidden" name="tourId" value="<?= $tour['tourId'] ?>">
<input type="hidden" name="old_image" value="<?= $tour['images'] ?>">

<div class="form-group">
<label>Tên tour</label>
<input type="text" name="title" value="<?= $tour['title'] ?>" required>
</div>

<div class="form-group">
<label>Mô tả</label>
<textarea name="description" rows="4"><?= $tour['description'] ?></textarea>
</div>

<div class="form-group">
<label>Ảnh tour (chọn file)</label>
<input type="file" name="images" accept="image/*">
</div>

<?php if (!empty($tour['images'])): ?>
<div class="form-group">
<label>Ảnh hiện tại</label><br>
<img src="../uploads/tours/<?= $tour['images'] ?>" width="150">
</div>
<?php endif; ?>

<div class="two-col">
<div class="form-group">
<label>Giá người lớn</label>
<input type="number" name="priceAdult" value="<?= $tour['priceAdult'] ?>">
</div>

<div class="form-group">
<label>Giá trẻ em</label>
<input type="number" name="priceChild" value="<?= $tour['priceChild'] ?>">
</div>
</div>

<div class="two-col">
<div class="form-group">
<label>Số chỗ</label>
<input type="number" name="quantify" value="<?= $tour['quantify'] ?>">
</div>

<div class="form-group">
<label>Thời gian</label>
<input type="text" name="duration" value="<?= $tour['duration'] ?>">
</div>
</div>

<div class="form-group">
<label>Điểm đến</label>
<input type="text" name="destination" value="<?= $tour['destination'] ?>">
</div>

<div class="form-group">
<label>Lịch trình</label>
<input type="text" name="itynerary" value="<?= $tour['itynerary'] ?>">
</div>

<div class="form-group">
<label>Trạng thái</label>
<select name="availability">
    <option value="1" <?= $tour['availability']==1?'selected':'' ?>>Hoạt động</option>
    <option value="0" <?= $tour['availability']==0?'selected':'' ?>>Ẩn</option>
</select>
</div>

<button name="update">💾 Cập nhật tour</button>

</form>
</div>

</body>
</html>
