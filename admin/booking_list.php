<?php
session_start();

include "../config/db.php";

$sql = "
SELECT 
    b.bookingId,
    u.userName,
    t.title AS tourName,
    b.bookingDate,
    b.numAdults,
    b.numChildren,
    b.totalPrice,
    b.paymentStatus
FROM tbl_booking b
JOIN tbl_users u ON b.userId = u.userId
JOIN tbl_tours t ON b.tourId = t.tourId
ORDER BY b.bookingDate DESC
";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách đặt tour</title>

    <style>
        /* ================= RESET ================= */

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
.add-btn {
    display: inline-block;
    margin-bottom: 18px;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #020617;
    padding: 12px 18px;
    border-radius: 10px;
    font-weight: bold;
    text-decoration: none;
    transition: 0.3s;
}

.add-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(34,197,94,0.4);
}
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #0f172a; /* đen xanh */
            padding: 30px;
            color: #e5e7eb;
        }

        /* ================= TIÊU ĐỀ ================= */
        h2 {
    text-align: center;
    margin: 25px 0;
    font-size: 26px;
    font-weight: 600;
    color: #38bdf8;
    position: relative;
    letter-spacing: 1px;
}

/* GẠCH DƯỚI ĐẸP */
h2::after {
    content: "";
    width: 80px;
    height: 3px;
    background: linear-gradient(135deg, #38bdf8, #2563eb);
    display: block;
    margin: 10px auto 0;
    border-radius: 5px;
}

        /* ================= KHUNG BẢNG ================= */
        .table-wrapper {
            background: #020617;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.6);
            overflow-x: auto;
        }

        /* ================= TABLE ================= */
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th, td {
            padding: 12px 10px;
            text-align: center;
        }

        th {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            color: white;
            font-size: 14px;
            text-transform: uppercase;
        }

        td {
            background: #020617;
            color: #e5e7eb;
            border-bottom: 1px solid #1e293b;
            font-size: 14px;
        }

        /* ================= HOVER ================= */
        tr:hover td {
            background: #020617;
            color: #38bdf8;
            transition: 0.2s;
        }

        /* ================= TRẠNG THÁI ================= */
        .status-paid {
            color: #22c55e;
            font-weight: bold;
        }

        .status-unpaid {
            color: #facc15;
            font-weight: bold;
        }

        .status-cancel {
            color: #ef4444;
            font-weight: bold;
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            h2 {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
<header class="admin-header">
    <div class="header-left">
        <h1 style="font-size:18px;">Danh sách Tour đã được đặt </h1>
    </div>

    <div class="header-right">
        <span>Xin chào, <b><?php echo $_SESSION['adminName']; ?></b></span>
        <a href="../index.php" class="logout-btn">Đăng xuất</a>
    </div>
</header>
    <a href="index.php" class="add-btn">Trở lại</a>



<div class="table-wrapper">
    <table>
        <tr>
            <th>ID</th>
            <th>Khách hàng</th>
            <th>Tên tour</th>
            <th>Ngày đặt</th>
            <th>Người lớn</th>
            <th>Trẻ em</th>
            <th>Tổng tiền</th>
            <th>Thanh toán</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['bookingId'] ?></td>
            <td><?= $row['userName'] ?></td>
            <td><?= $row['tourName'] ?></td>
            <td><?= $row['bookingDate'] ?></td>
            <td><?= $row['numAdults'] ?></td>
            <td><?= $row['numChildren'] ?></td>
            <td><?= number_format($row['totalPrice']) ?> VNĐ</td>
            <td>
                <?= $row['paymentStatus'] ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
