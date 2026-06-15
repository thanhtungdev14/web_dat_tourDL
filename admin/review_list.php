<?php
include "../config/db.php";

/* ===== XỬ LÝ XÓA ===== */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    mysqli_query($conn, "DELETE FROM tbl_reviews WHERE reviewId = $id");

    // reload tránh xóa lại khi F5
    header("Location: review_list.php");
    exit;
}

/* ===== LẤY DỮ LIỆU ===== */
$sql = "
SELECT 
    r.reviewId,
    u.userName,
    t.title AS tourName,
    r.rating,
    r.comment,
    r.timestamp
FROM tbl_reviews r
JOIN tbl_users u ON r.userId = u.userId
JOIN tbl_tours t ON r.tourId = t.tourId
ORDER BY r.timestamp DESC
";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Danh sách bình luận</title>

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


* { box-sizing: border-box; }

body {
    font-family: Arial;
    background: #48619c;
    padding: 30px;
    color: #e5e7eb;
}

h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #38bdf8;
}

.table-wrapper {
    background: #020617;
    padding: 20px;
    border-radius: 14px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.6);
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1000px;
}

th, td {
    padding: 12px;
    text-align: center;
}

th {
    background: linear-gradient(135deg, #0ea5e9, #2563eb);
    color: white;
}

td {
    border-bottom: 1px solid #1e293b;
}

/* COMMENT */
td:nth-child(5) {
    text-align: left;
    max-width: 300px;
}

/* HOVER */
tr:hover td {
    color: #38bdf8;
}

/* RATING */
.rating {
    color: #facc15;
    font-weight: bold;
}

/* DELETE BUTTON */
.btn-delete {
    background: #ef4444;
    padding: 6px 10px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
    font-size: 13px;
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
    box-shadow: 0 10px 25px rgba(34,197,94,0.4);}
</style>

</head>

<body>
<header class="admin-header">

    <div class="header-left">
        <h1 style="font-size:18px;" >XEM ĐÁNH GIÁ</h1>
    </div>

    <div class="header-right">
        <span>Xin chào, <b><?= $_SESSION['adminName'] ?? 'Admin' ?></b></span>
    </div>

</header>
    <a href="index.php" class="add-btn">Trở lại</a>



<h2>💬 Danh sách bình luận & đánh giá</h2>

<div class="table-wrapper">
<table>
<tr>
    <th>ID</th>
    <th>Người dùng</th>
    <th>Tour</th>
    <th>Đánh giá</th>
    <th>Bình luận</th>
    <th>Thời gian</th>
    <th>Hành động</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)): ?>
<tr>
    <td><?= $row['reviewId'] ?></td>
    <td><?= $row['userName'] ?></td>
    <td><?= $row['tourName'] ?></td>

    <td class="rating"><?= $row['rating'] ?>/5 ⭐</td>

    <td><?= $row['comment'] ?></td>

    <td><?= $row['timestamp'] ?></td>

    <td>
        <a href="?delete=<?= $row['reviewId'] ?>"
           class="btn-delete"
           onclick="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
           Xóa
        </a>
    </td>
</tr>
<?php endwhile; ?>

</table>
</div>

</body>
</html>