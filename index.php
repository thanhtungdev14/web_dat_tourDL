<?php
session_start();
include "config/db.php";

$category = $_GET['category'] ?? '';
$search   = $_GET['search'] ?? '';

$hideBanner = ($category || $search);

// ================= QUERY =================
$sql = "SELECT * FROM tbl_tours     WHERE availability = 1";

if ($category) {
    $sql .= " AND destination IN (";
    if ($category == 'north')   $sql .= "'Hà Nội','Hạ Long','Ninh Bình','Vĩnh Phúc','Sa Pa'";
    if ($category == 'central') $sql .= "'Huế','Đà Nẵng','Hội An','Thanh Hóa', 'Đà Lạt'";
    if ($category == 'south')   $sql .= "'TP. HCM','Cần Thơ','Vũng Tàu'";
    $sql .= ")";
}

if ($search) {
    $searchEscaped = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (title LIKE '%$searchEscaped%' OR destination LIKE '%$searchEscaped%')";
}

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Web Du Lịch</title>
<link rel="stylesheet" href="css/styles.css">
</head>

<body>

<header>                  </header>

<nav>
<ul>
    <li><a href="index.php">Trang chủ</a></li>
    <li><a href="index.php?category=north">Miền Bắc</a></li>
    <li><a href="index.php?category=central">Miền Trung</a></li>
    <li><a href="index.php?category=south">Miền Nam</a></li>
    <li><a href="chat/user_chat.php"> Chat </a></li>
    
    <?php if (isset($_SESSION['userId'])): ?>
        <li>👤 <?php echo $_SESSION['userName']; ?></li>
        <li><a href="profile.php"> Tour của tôi </a></li>
        <li><a href="auth/logout.php">Đăng xuất</a></li>

    <?php else: ?>
        <li><a href="auth/login.php">Đăng nhập</a></li>
    <?php endif; ?>
</ul>
</nav>

<!-- SEARCH -->
<form class="search-box" method="get">
    <input type="text" name="search"
           placeholder="Tìm tour..."
           value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Tìm</button>
</form>

<!--BANNER ================= -->
<?php if (!$hideBanner): ?>
<div class="top-banner">
    <img src="images/uu.jpg" class="banner-slide active">
    <img src="images/ban2.jpg" class="banner-slide">
    <img src="images/ban3.jpg" class="banner-slide">

    <div class="banner-overlay">
        <h1>Du lịch Việt Nam</h1>
        <p>Hành trình trải nghiệm bất tận</p>
    </div>
</div>
<?php endif; ?>

<!-- DANH SÁCH TOUR (LUÔN HIỆN) ================= -->
<div class="container">
<?php if (mysqli_num_rows($result) > 0): ?>
<?php while ($row = mysqli_fetch_assoc($result)): ?>
    <div class="tour">
        <img src="images/<?php echo $row['images']; ?>">
        <h3><?php echo $row['title']; ?></h3>
        <p>📍 <?php echo $row['destination']; ?></p>
        <p>💰 <?php echo number_format($row['priceAdult']); ?> VNĐ</p>

        <a href="chi_tiet_tour.php?tourID=<?php echo $row['tourId']; ?>">
            Xem chi tiết
        </a>
    </div>
<?php endwhile; ?>
<?php else: ?>
<p style="text-align:center">❌ Không có tour</p>
<?php endif; ?>
</div>

<!-- ================= SLIDER ================= -->
<script>
const slides = document.querySelectorAll('.banner-slide');
let i = 0;

if (slides.length > 0) {
    setInterval(() => {
        slides[i].classList.remove('active');
        i = (i + 1) % slides.length;
        slides[i].classList.add('active');
    }, 3000);
}
</script>

</body>
</html>
