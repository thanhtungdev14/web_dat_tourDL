<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['userId'])) {
    header("Location: auth/login.php");
    exit;
}

$bookingId = intval($_POST['bookingId']);
$amount = floatval($_POST['amount']);
$paymentMethod = mysqli_real_escape_string($conn, $_POST['paymentMethod']);

// Tạo mã giao dịch giả
$transactionId = "GD" . time();

//  Lưu vào bảng tbl_checkout
$sqlCheckout = "
INSERT INTO tbl_checkout
(bookingId, paymentMethod, amount, paymentStatus, transactionId)
VALUES
($bookingId, '$paymentMethod', $amount, 'Đã thanh toán', '$transactionId')
";

mysqli_query($conn, $sqlCheckout);

// p nhật trạng thái trong tbl_booking
$sqlUpdate = "
UPDATE tbl_booking
SET paymentStatus = 'Đã thanh toán'
WHERE bookingId = $bookingId
";

mysqli_query($conn, $sqlUpdate);


header("Location: profile.php");
exit;
