<?php 
session_start();
include "config/db.php";

// Lấy tourID từ URL
$tourId = $_GET['tourID'] ?? 0;

// Lấy thông tin tour từ DB
$sqlTour = "SELECT * FROM tbl_tours WHERE tourId = $tourId";
$resultTour = mysqli_query($conn, $sqlTour);
$canReview = false;

if (isset($_SESSION['userId'])) {
    $userId = intval($_SESSION['userId']);

    // Kiểm tra đã đặt tour + đã thanh toán
    $check = mysqli_query($conn, "
        SELECT b.bookingId
        FROM tbl_booking b
        JOIN tbl_checkout c ON b.bookingId = c.bookingId
        WHERE b.userId = $userId
          AND b.tourId = $tourId
          AND c.paymentStatus = 'Đã thanh toán'
    ");

    if (mysqli_num_rows($check) > 0) {

        // Kiểm tra REVUEW
        $checkReview = mysqli_query($conn, "
            SELECT reviewId
            FROM tbl_reviews
            WHERE userId = $userId AND tourId = $tourId
        ");

        if (mysqli_num_rows($checkReview) == 0) {
            $canReview = true;
        }
    }
}

if (!$resultTour || mysqli_num_rows($resultTour) == 0) {
    echo "<h2 style='color:red'>❌ Tour không tồn tại!</h2>";
    exit;
}

$tour = mysqli_fetch_assoc($resultTour);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết tour - <?php echo $tour['title']; ?></title>

    <style>
        /* ================= RESET & CHUNG ================= */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f8cdda, #f1f1f1);
            padding: 30px 15px;
        }

        h2, h3 {
            color: #e575b6;
        }

        p {
            margin: 6px 0;
            line-height: 1.5;
            color: #444;
        }

        /* ================= KHUNG CHUNG ================= */
        .tour-detail,
        .review-box,
        .review-item {
            background: #fff;
            padding: 25px;
            border-radius: 14px;
            max-width: 800px;
            margin: 25px auto;
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        }

        /* ================= ẢNH TOUR ================= */
        .tour-detail img {
            width: 100%;
            height: 360px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        /* ================= NÚT ĐẶT TOUR ================= */
        a.button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 26px;
            background: linear-gradient(135deg, #e575b6, #f39ac7);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            transition: 0.3s;
        }

        a.button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(229,117,182,0.5);
        }

        /* ================= REVIEW ================= */
        .review-item {
            margin-top: 15px;
            background: #fafafa;
            border-left: 5px solid #e575b6;
        }

        .review-item b {
            color: #333;
            font-size: 15px;
        }

        .review-item small {
            color: #888;
            font-size: 12px;
        }

        /* ================= FORM ================= */
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-top: 6px;
        }

        textarea:focus,
        select:focus {
            outline: none;
            border-color: #e575b6;
            box-shadow: 0 0 0 2px rgba(229,117,182,0.2);
        }

        button {
            padding: 12px 22px;
            background: linear-gradient(135deg, #0077cc, #3399ff);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 15px;
            transition: 0.3s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,119,204,0.4);
        }

        label {
            font-weight: bold;
            color: #555;
        }
    </style>
</head>

<body>

<!-- ================= CHI TIẾT TOUR ================= -->
<div class="tour-detail">
    <img src="images/<?php echo $tour['images']; ?>" alt="<?php echo $tour['title']; ?>">

    <h2><?php echo $tour['title']; ?></h2>

    <p><strong>📍 Địa điểm:</strong> <?php echo $tour['destination']; ?></p>
    <p><strong>⏱ Thời gian:</strong> <?php echo $tour['duration']; ?></p>
    <p><strong>👨‍👩‍👧 Giá người lớn:</strong> <?php echo number_format($tour['priceAdult']); ?> VNĐ</p>
    <p><strong>🧒 Giá trẻ em:</strong> <?php echo number_format($tour['priceChild']); ?> VNĐ</p>
    <p><strong>🗺 Lịch trình:</strong> <?php echo $tour['itynerary']; ?></p>

    <a class="button" href="dat_tour.php?tourID=<?php echo $tour['tourId']; ?>">
        Đặt tour ngay
    </a>
</div>

<!-- ================= HIỂN THỊ REVIEW ================= -->
<div class="review-box">
    <h3>⭐ Đánh giá từ khách hàng</h3>

    <?php
    $reviews = mysqli_query($conn, "
        SELECT r.rating, r.comment, r.timestamp, u.userName
        FROM tbl_reviews r
        JOIN tbl_users u ON r.userId = u.userId
        WHERE r.tourId = $tourId
        ORDER BY r.timestamp DESC
    ");

    if (mysqli_num_rows($reviews) > 0):
        while ($r = mysqli_fetch_assoc($reviews)):
    ?>
        <div class="review-item">
            <b><?php echo htmlspecialchars($r['userName']); ?></b>
            ⭐ <?php echo $r['rating']; ?>/5
            <br>
            <small><?php echo $r['timestamp']; ?></small>
            <p><?php echo htmlspecialchars($r['comment']); ?></p>
        </div>
    <?php endwhile; else: ?>
        <p>Chưa có đánh giá nào cho tour này.</p>
    <?php endif; ?>
</div>

<!-- ================= FORM REVIEW ================= -->
<?php if ($canReview): ?>
<div class="review-box">
    <h3>📝 Đánh giá tour</h3>

    <form action="submit_review.php" method="post">
        <input type="hidden" name="tourId" value="<?php echo $tourId; ?>">

        <label>⭐ Số sao</label>
        <select name="rating" required>
            <option value="">-- Chọn --</option>
            <option value="5">⭐⭐⭐⭐⭐</option>
            <option value="4">⭐⭐⭐⭐</option>
            <option value="3">⭐⭐⭐</option>
            <option value="2">⭐⭐</option>
            <option value="1">⭐</option>
        </select>

        <br><br>

        <label>💬 Nhận xét</label>
        <textarea name="comment" rows="4" required></textarea>

        <br><br>

        <button type="submit">Gửi đánh giá</button>
    </form>
</div>
<?php endif; ?>

</body>
</html>
