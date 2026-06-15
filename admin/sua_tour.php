<?php
include "../config/db.php";
$listTour = mysqli_query($conn, "SELECT * FROM tbl_tours ORDER BY tourId DESC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Danh sách tour</title>

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

/* ================= TITLE ================= */
h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #38bdf8;
    letter-spacing: 1px;
}

/* ================= TABLE ================= */
table {
    width: 100%;
    border-collapse: collapse;
    background: #020617;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0,0,0,0.6);
}

/* ================= HEADER ================= */
th {
    background: linear-gradient(135deg, #0ea5e9, #2563eb);
    color: white;
    padding: 14px;
    text-align: left;
    font-size: 14px;
}

/* ================= ROW ================= */
td {
    padding: 12px 14px;
    border-bottom: 1px solid #1e293b;
    font-size: 14px;
}

tr:hover {
    background: rgba(56,189,248,0.08);
}

/* ================= ACTION ================= */
a {
    color: #38bdf8;
    text-decoration: none;
    font-weight: bold;
    padding: 6px 10px;
    border-radius: 6px;
    transition: 0.3s;
}

a:hover {
    background: #38bdf8;
    color: #020617;
}

/* ================= PRICE ================= */
.price {
    color: #22c55e;
    font-weight: bold;
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
        border-radius: 10px;
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

</style>
</head>

<body>
<header class="admin-header">

    <div class="header-left">
        <h1 style="font-size:18px;" >SỬA TOUR</h1>
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
        <th>Giá người lớn</th>
        <th>Hành động</th>
    </tr>

<?php while ($row = mysqli_fetch_assoc($listTour)) { ?>
<tr>
    <td data-label="ID"><?= $row['tourId'] ?></td>
    <td data-label="Tên tour"><?= htmlspecialchars($row['title']) ?></td>
    <td data-label="Điểm đến"><?= htmlspecialchars($row['destination']) ?></td>
    <td data-label="Giá" class="price"><?= number_format($row['priceAdult']) ?> đ</td>
    <td data-label="Hành động">
        <a href="edit_tour.php?tourId=<?= $row['tourId'] ?>">Sửa</a>
        
    </td>
</tr>
<?php } ?>
</table>

</body>
</html>
