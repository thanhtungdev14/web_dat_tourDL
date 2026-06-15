<?php
include "../config/db.php";

/* ================= XÓA TOUR ================= */
if (isset($_GET['tourId'])) {
    $tourId = intval($_GET['tourId']);

    // Kiểm tra tour tồn tại
    $check = mysqli_query($conn, "SELECT tourId FROM tbl_tours WHERE tourId=$tourId");
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "DELETE FROM tbl_tours WHERE tourId=$tourId");
    }

    header("Location: xoa_tour.php");
    exit;
}

/* ================= DANH SÁCH TOUR ================= */
$listTour = mysqli_query($conn, "SELECT * FROM tbl_tours ORDER BY tourId DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Xóa tour</title>

<style>
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

/* ================= RESET ================= */
* {
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

/* ================= BODY ================= */
body {
    background: #0f172a;
    color: #e5e7eb;
    padding: 25px;
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
/* ================= TITLE ================= */
h2 {
    text-align: center;
    color: #f87171;
    margin-bottom: 25px;
    letter-spacing: 1px;
}

/* ================= TABLE ================= */
table {
    width: 100%;
    background: #020617;
    border-collapse: collapse;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0,0,0,0.6);
}

/* ================= HEADER ================= */
th {
    background: linear-gradient(135deg, #dc2626, #991b1b);
    color: white;
    padding: 14px;
    text-align: left;
    font-size: 14px;
}

/* ================= CELL ================= */
td {
    padding: 12px 14px;
    border-bottom: 1px solid #1e293b;
    font-size: 14px;
}

/* ================= ROW HOVER ================= */
tr:hover {
    background: rgba(248,113,113,0.08);
}

/* ================= PRICE ================= */
.price {
    color: #22c55e;
    font-weight: bold;
}

/* ================= DELETE BUTTON ================= */
a.delete {
    display: inline-block;
    background: linear-gradient(135deg, #ef4444, #b91c1c);
    color: white;
    padding: 8px 14px;
    border-radius: 8px;
    font-weight: bold;
    text-decoration: none;
    transition: 0.3s;
}

a.delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(239,68,68,0.5);
}

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
    table, thead, tbody, th, td, tr {
        display: block;
    }

    th {
        display: none;
    }

    tr {
        margin-bottom: 15px;
        border: 1px solid #1e293b;
        border-radius: 12px;
        padding: 10px;
    }

    td {
        border: none;
        padding: 6px 0;
    }

    td::before {
        content: attr(data-label);
        font-weight: bold;
        color: #94a3b8;
        display: block;
        margin-bottom: 3px;
    }
}
</style>
</head>

<body>
    <header class="admin-header">

    <div class="header-left">
        <h1 style="font-size:18px;" >XÓA TOUR</h1>
    </div>

    <div class="header-right">
        <span>Xin chào, <b><?= $_SESSION['adminName'] ?? 'Admin' ?></b></span>
    </div>

</header>
    <a href="quanlitour.php" class="add-btn">Trở lại</a>


<table>
<tr>
  <th>ID</th>
  <th>Tên tour</th>
  <th>Điểm đến</th>
  <th>Giá NL</th>
  <th>Hành động</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($listTour)) { ?>
<tr>
  <td data-label="ID"><?= $row['tourId'] ?></td>
  <td data-label="Tên tour"><?= htmlspecialchars($row['title']) ?></td>
  <td data-label="Điểm đến"><?= htmlspecialchars($row['destination']) ?></td>
  <td data-label="Giá NL" class="price"><?= number_format($row['priceAdult']) ?> đ</td>
  <td data-label="Hành động">
    <a class="delete"
       href="xoa_tour.php?tourId=<?= $row['tourId'] ?>"
       onclick="return confirm('⚠️ Bạn có chắc muốn xóa tour này? Hành động không thể hoàn tác!');">
       ❌ Xóa
    </a>
  </td>
</tr>
<?php } ?>

</table>

</body>
</html>
