<?php
session_start();
include "../config/db.php";

/* ===== BẢO VỆ ADMIN ===== */
if (!isset($_SESSION['adminId'])) {
    header("Location: ../auth/login.php");
    exit;
}

$userId = intval($_GET['userId'] ?? 0);

/* ===== LẤY DANH SÁCH TOUR USER ĐÃ ĐẶT ===== */
$sql = "
SELECT 
    b.bookingId,
    b.bookingDate,
    b.numAdults,
    b.numChildren,
    b.totalPrice,
    b.paymentStatus,
    t.title,
    t.destination,
    t.images
FROM tbl_booking b
JOIN tbl_tours t ON b.tourId = t.tourId
WHERE b.userId = $userId
ORDER BY b.bookingDate DESC
";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Tour đã đặt</title>
<link rel="stylesheet" href="../css/styles.css">
</head>

<body>

<header class="admin-header">
    <div class="header-left">
        <h1 style="font-size:18px;">Tour được đặt</h1>
    </div>

    <div class="header-right">
        <span>Xin chào, <b><?php echo $_SESSION['adminName']; ?></b></span>
        <a href="../index.php" class="logout-btn">Đăng xuất</a>
    </div>
</header>

<nav class="admin-nav">
  <a href="users.php">⬅ Quay lại</a>
</nav>

<div class="admin-table-container">

<?php if (mysqli_num_rows($result) > 0): ?>
<table class="admin-table">
  <thead>
    <tr>
      <th>Ảnh</th>
      <th>Tên tour</th>
      <th>Địa điểm</th>
      <th>Người lớn</th>
      <th>Trẻ em</th>
      <th>Tổng tiền</th>
      <th>Thanh toán</th>
      <th>Ngày đặt</th>
    </tr>
  </thead>
  <tbody>
  <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
      <td>
        <img src="../images/<?php echo $row['images']; ?>" width="80">
      </td>
      <td><?php echo htmlspecialchars($row['title']); ?></td>
      <td><?php echo htmlspecialchars($row['destination']); ?></td>
      <td><?php echo $row['numAdults']; ?></td>
      <td><?php echo $row['numChildren']; ?></td>
      <td><?php echo number_format($row['totalPrice']); ?> VNĐ</td>
      <td>
        <?php if ($row['paymentStatus'] == 'Đã thanh toán'): ?>
          <span class="status-active">Đã thanh toán</span>
        <?php else: ?>
          <span class="status-block">Chưa thanh toán</span>
        <?php endif; ?>
      </td>
      <td><?php echo $row['bookingDate']; ?></td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>
<?php else: ?>
  <p style="text-align:center">Người dùng này chưa đặt tour nào.</p>
<?php endif; ?>

</div>

</body>
</html>
