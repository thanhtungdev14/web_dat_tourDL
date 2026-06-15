<?php
session_start();
include "../config/db.php";

/* ===== BẢO VỆ ADMIN ===== */
if (!isset($_SESSION['adminId'])) {
    header("Location: ../auth/login.php");
    exit;
}

/* ===== LẤY DANH SÁCH TOUR ===== */
$sql = "
SELECT 
    tourId,
    title,
    destination,
    images,
    priceAdult,
    availability
FROM tbl_tours
ORDER BY tourId DESC
";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý Tour</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="../css/styles.css">

<style>
.top-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.btn-add {
    background: #22c55e;
    padding: 8px 14px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
}

.btn-edit {
    background: #facc15;
    padding: 8px 14px;
    border-radius: 6px;
    color: black;
    text-decoration: none;
}

.btn-delete {
    background: #ef4444;
    padding: 8px 14px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
}
</style>

</head>

<body>

<!-- HEADER -->
<header class="admin-header">
    <div class="header-left">
        <h1>📦 Quản lý Tour</h1>
    </div>

    <div class="header-right">
        <span>Xin chào, <b><?php echo $_SESSION['adminName']; ?></b></span>
        <a href="../index.php" class="logout-btn">Đăng xuất</a>
    </div>
</header>

<!-- MENU -->
<nav class="admin-nav">
  <a href="index.php">🏠 TRANG CHỦ</a>
</nav>

<div class="admin-table-container">

<!-- 🔥 NÚT TRÊN -->
<div class="top-actions">
  <a href="them_tour.php" class="btn-add">➕ Thêm tour</a>
  <a href="sua_tour.php" class="btn-edit">✏️ Sửa tour</a>
  <a href="xoa_tour.php" class="btn-delete">🗑 Xóa tour</a>
</div>

<?php if (mysqli_num_rows($result) > 0): ?>
<table class="admin-table">
  <thead>
    <tr>
      <th>ID</th>
      <th>Ảnh</th>
      <th>Tên tour</th>
      <th>Điểm đến</th>
      <th>Giá</th>
      <th>Trạng thái</th>
    </tr>
  </thead>

  <tbody>
  <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
      <td><?= $row['tourId'] ?></td>

      <td>
        <img src="../images/<?= $row['images']; ?>" width="80">
      </td>

      <td><?= htmlspecialchars($row['title']); ?></td>

      <td><?= htmlspecialchars($row['destination']); ?></td>

      <td><?= number_format($row['priceAdult']); ?> VNĐ</td>

      <td>
        <?php if ($row['availability'] == 1): ?>
          <span class="status-active">Hoạt dộng
          </span>
        <?php else: ?>
          <span class="status-block">Không hoạt động</span>
        <?php endif; ?>
      </td>

    </tr>
  <?php endwhile; ?>
  </tbody>
</table>

<?php else: ?>
  <p style="text-align:center">Chưa có tour nào.</p>
<?php endif; ?>

</div>

</body>
</html>