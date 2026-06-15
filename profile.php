<?php
session_start();
include "config/db.php";


if (!isset($_SESSION['userId'])) {
    header("Location: auth/login.php");
    exit;
}

$userId = intval($_SESSION['userId']);
// Lấy tourID từ URL



$sql = "
SELECT 
    b.bookingId,
    b.bookingDate,
    b.numAdults,
    b.numChildren,
    b.totalPrice,
    b.paymentStatus AS bookingPaymentStatus,

    t.title,
    t.destination,
    t.images,
    t.duration,
  t.tourId,          

    c.paymentMethod,
    c.paymentDate,
    c.transactionId

FROM tbl_booking b
JOIN tbl_tours t ON b.tourId = t.tourId
LEFT JOIN tbl_checkout c ON b.bookingId = c.bookingId

WHERE b.userId = $userId
ORDER BY b.bookingDate DESC
";


$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tour của tôi</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>

</header>

<nav>
    <ul>
        <li><a href="index.php">⬅ Trang chủ</a></li>
        <li><a href="auth/logout.php">Đăng xuất</a></li>
    </ul>
</nav>

<div class="container">

<?php if (mysqli_num_rows($result) > 0): ?>
<?php while ($row = mysqli_fetch_assoc($result)): ?>
    <div class="tour">

        <img src="images/<?php echo htmlspecialchars($row['images']); ?>">

        <h3><?php echo htmlspecialchars($row['title']); ?></h3>

        <p>📍 Địa điểm: <?php echo htmlspecialchars($row['destination']); ?></p>
        <p>⏱ Thời gian: <?php echo htmlspecialchars($row['duration']); ?></p>

        <p>👨 Người lớn: <?php echo $row['numAdults']; ?></p>
        <p>👶 Trẻ em: <?php echo $row['numChildren']; ?></p>

        <p>💰 Tổng tiền:
            <b><?php echo number_format($row['totalPrice']); ?> VNĐ</b>
        </p>

        <p>📅 Ngày đặt: <?php echo $row['bookingDate']; ?></p>

        <hr>

        <!-- ================= THANH TOÁN ================= -->

        <?php if ($row['bookingPaymentStatus'] == 'Đã thanh toán'): ?>
            <p style="color:green;font-weight:bold;">✔ Đã thanh toán</p>
            <p>💳 Hình thức: <?php echo $row['paymentMethod']; ?></p>
            <p>🧾 Mã GD: <?php echo $row['transactionId']; ?></p>
            <p>📅 Ngày TT: <?php echo $row['paymentDate']; ?></p>
       <a href="chi_tiet_tour.php?tourID=<?php echo isset($row['tourId']) ? intval($row['tourId']) : 0; ?>"
                   style="background:#0077cc;color:#fff;padding:8px 12px;border-radius:5px;display:inline-block;margin-top:10px;">
                   Xem chi tiết
                </a>
        <?php else: ?>
            <p style="color:red;font-weight:bold;">✖ Chưa thanh toán</p>

            <!-- ✅ BƯỚC 2: NÚT THANH TOÁN -->
            <a href="checkout.php?bookingId=<?php echo $row['bookingId']; ?>"
               style="background:#ff9800;color:#fff;padding:8px 12px;border-radius:5px;display:inline-block;margin-right:10px;">
               Thanh toán
            </a>
<!-- Xem chi tiết tour -->
<a href="chi_tiet_tour.php?tourID=<?php echo $row['tourId']; ?>"
   style="background:#0077cc;color:#fff;padding:8px 12px;border-radius:5px;display:inline-block;margin-right:10px;">
   Xem chi tiết
</a>

            <!-- Hủy tour -->
            <a href="cancel_booking.php?bookingId=<?php echo $row['bookingId']; ?>"
               onclick="return confirm('Bạn có chắc muốn hủy tour này?');"
               style="background:#dc3545;color:#fff;padding:8px 12px;border-radius:5px;display:inline-block;">
               Hủy tour
            </a>
        <?php endif; ?>

    </div>
<?php endwhile; ?>
<?php else: ?>
    <p style="text-align:center;font-size:18px;">Bạn chưa đặt tour nào.</p>
<?php endif; ?>

</div>

</body>
</html>
