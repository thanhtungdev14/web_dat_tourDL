<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['userId'])) {
    header("Location: auth/login.php");
    exit;
}

$userId = intval($_SESSION['userId']);
$bookingId = intval($_GET['bookingId'] ?? 0);

if ($bookingId == 0) {
    die("❌ Booking không hợp lệ");
}

/* ❗ Chỉ cho hủy khi CHƯA thanh toán */
$check = mysqli_query($conn, "
    SELECT *
    FROM tbl_booking
    WHERE bookingId = $bookingId
      AND userId = $userId
      AND paymentStatus != 'Đã thanh toán'
");

if (mysqli_num_rows($check) == 0) {
    die("❌ Không thể hủy tour đã thanh toán hoặc không thuộc về bạn");
}

/* Xóa booking */
mysqli_query($conn, "
    DELETE FROM tbl_booking
    WHERE bookingId = $bookingId
");

/* (Nếu có checkout thì xóa luôn) */
mysqli_query($conn, "
    DELETE FROM tbl_checkout
    WHERE bookingId = $bookingId
");

header("Location: profile.php");
exit;
