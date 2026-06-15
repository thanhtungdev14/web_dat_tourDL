<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['userId'])) {
    header("Location: auth/login.php");
    exit;
}

if (!isset($_GET['bookingId'])) {
    header("Location: profile.php");
    exit;
}

$bookingId = intval($_GET['bookingId']);

// Lấy thông tin booking
$sql = "
SELECT b.bookingId, b.totalPrice, t.title
FROM tbl_booking b
JOIN tbl_tours t ON b.tourId = t.tourId
WHERE b.bookingId = $bookingId
";

$result = mysqli_query($conn, $sql);
$booking = mysqli_fetch_assoc($result);

if (!$booking) {
    echo "Booking không tồn tại!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f8cdda, #f1f1f1);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .payment-container {
            background: #fff;
            width: 100%;
            max-width: 480px;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            animation: fadeUp 0.4s ease;
        }

        h2 {
            text-align: center;
            color: #e575b6;
            margin-bottom: 20px;
        }

        .payment-info {
            background: #fafafa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .payment-info p {
            margin: 8px 0;
            font-size: 15px;
            color: #444;
        }

        .payment-info b {
            color: #333;
        }

        .price {
            font-size: 18px;
            color: #e575b6;
            font-weight: bold;
        }

        .payment-method {
            margin-bottom: 20px;
        }

        .payment-method label {
            display: block;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .payment-method input {
            margin-right: 10px;
        }

        .payment-method label:hover {
            border-color: #e575b6;
            background: #fff0f7;
        }

        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #e575b6, #f39ac7);
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(229,117,182,0.4);
        }

        .back-link {
            text-align: center;
            margin-top: 15px;
        }

        .back-link a {
            color: #555;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link a:hover {
            color: #e575b6;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

<div class="payment-container">

    <h2> Thanh toán tour</h2>

    <div class="payment-info">
        <p><b>📌 Tour:</b> <?php echo $booking['title']; ?></p>
        <p><b>💰 Tổng tiền:</b> <span class="price">
            <?php echo number_format($booking['totalPrice']); ?> VNĐ
        </span></p>
    </div>

    <form method="post" action="process_payment.php">
        <input type="hidden" name="bookingId" value="<?php echo $bookingId; ?>">
        <input type="hidden" name="amount" value="<?php echo $booking['totalPrice']; ?>">

        <div class="payment-method">
            <label>
                <input type="radio" name="paymentMethod" value="MOMO" required>
                📱 Ví MoMo (giả lập)
            </label>

            <label>
                <input type="radio" name="paymentMethod" value="Tiền mặt">
                💵 Thanh toán tiền mặt
            </label>
        </div>

        <button type="submit">Xác nhận thanh toán</button>
    </form>

    <div class="back-link">
        <a href="profile.php">⬅ Quay lại trang cá nhân</a>
    </div>

</div>

</body>
</html>
