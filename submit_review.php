<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['userId'])) {
    header("Location: auth/login.php");
    exit;
}

$userId = intval($_SESSION['userId']);
$tourId = intval($_POST['tourId']);
$rating = floatval($_POST['rating']);
$comment = mysqli_real_escape_string($conn, $_POST['comment']);

// Kiểm tra đã thanh toán chưa
$check = mysqli_query($conn, "
    SELECT b.bookingId
    FROM tbl_booking b
    JOIN tbl_checkout c ON b.bookingId = c.bookingId
    WHERE b.userId = $userId
      AND b.tourId = $tourId
      AND c.paymentStatus = 'Đã thanh toán'
");

if (mysqli_num_rows($check) == 0) {
    die("❌ Bạn không đủ điều kiện đánh giá tour này.");
}

// Kiểm tra review trùng
$checkReview = mysqli_query($conn, "
    SELECT reviewId
    FROM tbl_reviews
    WHERE userId = $userId AND tourId = $tourId
");

if (mysqli_num_rows($checkReview) > 0) {
    die("⚠️ Bạn đã đánh giá tour này rồi.");
}

// Lưu review
mysqli_query($conn, "
    INSERT INTO tbl_reviews (tourId, userId, rating, comment)
    VALUES ($tourId, $userId, $rating, '$comment')
");

header("Location: chi_tiet_tour.php?tourID=$tourId");
exit;
