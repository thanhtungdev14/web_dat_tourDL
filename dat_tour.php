<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // ✅ THÊM
include "config/db.php";

/* ================== THÊM: KIỂM TRA ĐĂNG NHẬP ================== */
if (!isset($_SESSION['userId'])) {
    header("Location: auth/login.php");
    exit;
}
/* ================== HẾT PHẦN THÊM ================== */

// Lấy tourID từ URL
$tourId = $_GET['tourID'] ?? 0;

// Lấy thông tin tour
$sqlTour = "SELECT * FROM tbl_tours WHERE tourId = $tourId";
$resultTour = mysqli_query($conn, $sqlTour);

if (!$resultTour || mysqli_num_rows($resultTour) == 0) {
    echo "<h2 style='color:red'>❌ Tour không tồn tại!</h2>";
    exit;
}

$tour = mysqli_fetch_assoc($resultTour);
$title = $tour['title'];
$priceAdult = $tour['priceAdult'];
$priceChild = $tour['priceChild'];

// ================= XỬ LÝ FORM =================
if (isset($_POST['submit'])) {

    /* ❌ CODE CŨ (GIỮ LOGIC – KHÔNG XÓA) */
    // $userId = 1;

    /* ✅ SỬA: LẤY userId ĐÚNG THEO TÀI KHOẢN ĐĂNG NHẬP */
    $userId = (int)$_SESSION['userId'];

    $bookingDate = date('Y-m-d');
    $numAdults = intval($_POST['numAdults']);
    $numChildren = intval($_POST['numChildren']);
    $totalPrice = $numAdults * $priceAdult + $numChildren * $priceChild;
    $paymentStatus = "Chưa thanh toán";
    $specialRequests = mysqli_real_escape_string($conn, $_POST['specialRequests']);

    $sqlInsert = "INSERT INTO tbl_booking 
        (tourId, userId, bookingDate, numAdults, numChildren, totalPrice, paymentStatus, specialRequestes)
        VALUES 
        ('$tourId', '$userId', '$bookingDate', '$numAdults', '$numChildren', '$totalPrice', '$paymentStatus', '$specialRequests')";

    if (mysqli_query($conn, $sqlInsert)) {
       echo "
<div class='success-box'>
    <div class='success-icon'>🎉</div>

    <h2>Đặt tour thành công!</h2>

    <div class='tour-name'>🧳 $title</div>

    <div class='price'>
        💰 Tổng tiền: " . number_format($totalPrice) . " VNĐ
    </div>

    <p>Cảm ơn bạn đã tin tưởng chúng tôi ❤️</p>

    <p class='redirect'> Đang quay về trang chủ...</p>
</div>

<script>
    setTimeout(function(){
        window.location.href = 'index.php';
    }, 3000);
</script>
";
exit;
    } else {
        echo "<p style='color:red'>❌ Lỗi: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt tour - <?php echo $title; ?></title>

    <style>
        /* ================= RESET ================= */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #f8cdda, #f1f1f1);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        /* ===== SUCCESS BOX ===== */
.success-box {
    background: linear-gradient(135deg, #ffffff, #fdf2f8);
    padding: 35px 30px;
    border-radius: 20px;
    text-align: center;
    max-width: 420px;
    width: 100%;
    box-shadow: 
        0 20px 40px rgba(0,0,0,0.15),
        0 0 0 1px rgba(255,255,255,0.3) inset;
    animation: fadeUp 0.4s ease, zoomIn 0.3s ease;
    position: relative;
    overflow: hidden;
}

/* hiệu ứng nền nhẹ */
.success-box::before {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at top right, #fbcfe8, transparent 60%);
    opacity: 0.4;
}
/* TOUR NAME */
.tour-name {
    font-weight: bold;
    color: #e575b6;
    margin-bottom: 10px;
}

/* PRICE */
.price {
    background: #ecfdf5;
    border: 1px dashed #22c55e;
    padding: 10px;
    border-radius: 10px;
    margin: 15px 0;
    font-weight: bold;
    color: #16a34a;
}

/* REDIRECT TEXT */
.redirect {
    font-size: 13px;
    color: #777;
}

        /* ================= KHUNG FORM ================= */
        .booking-container {
            background: #fff;
            width: 100%;
            max-width: 420px;
            padding: 30px 25px;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            animation: fadeUp 0.4s ease;
        }

        h2 {
            text-align: center;
            color: #e575b6;
            margin-bottom: 20px;
        }

        /* ================= INPUT ================= */
        label {
            font-weight: bold;
            color: #444;
            font-size: 14px;
        }

        input[type=number],
        input[type=text] {
            width: 100%;
            padding: 10px 12px;
            margin: 6px 0 15px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            transition: 0.2s;
        }

        input:focus {
            outline: none;
            border-color: #e575b6;
            box-shadow: 0 0 0 2px rgba(229,117,182,0.2);
        }

        /* ================= TỔNG TIỀN ================= */
        .total-box {
            background: #fff0f7;
            border: 1px dashed #e575b6;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .total-box label {
            color: #e575b6;
        }

        /* ================= NÚT ================= */
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

        /* ================= HIỆU ỨNG ================= */
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

<div class="booking-container">

    <h2>🧳 Đặt tour</h2>

    <form method="post">

        <label>👨‍👩‍👧 Người lớn</label>
        <input type="number" name="numAdults" id="numAdults" value="1" min="0" required>

        <label>🧒 Trẻ em</label>
        <input type="number" name="numChildren" id="numChildren" value="0" min="0" required>

        <div class="total-box">
            <label>💰 Tổng tiền (VNĐ)</label>
            <input type="text" name="totalPrice" id="totalPrice"
                   value="<?php echo $priceAdult; ?>" readonly>
        </div>

        <label>📝 Yêu cầu thêm</label>
        <input type="text" name="specialRequests"
               placeholder="Ví dụ: cần phòng riêng, suất ăn đặc biệt">

        <button type="submit" name="submit">Xác nhận đặt tour</button>

    </form>

</div>

<script>
const numAdults = document.getElementById('numAdults');
const numChildren = document.getElementById('numChildren');
const totalPrice = document.getElementById('totalPrice');

const priceAdult = <?php echo $priceAdult; ?>;
const priceChild = <?php echo $priceChild; ?>;

function updateTotal() {
    const adults = parseInt(numAdults.value) || 0;
    const children = parseInt(numChildren.value) || 0;
    totalPrice.value = adults * priceAdult + children * priceChild;
}

numAdults.addEventListener('input', updateTotal);
numChildren.addEventListener('input', updateTotal);
updateTotal();
</script>

</body>
</html>
