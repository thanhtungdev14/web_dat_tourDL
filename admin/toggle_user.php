<?php
session_start();
include "../config/db.php";

/* ===== BẢO VỆ ADMIN ===== */
if (!isset($_SESSION['adminId'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);
$status = $_GET['status'] ?? '';

if ($id > 0 && ($status == 'y' || $status == 'n')) {

    mysqli_query($conn, "
        UPDATE tbl_users 
        SET isActive = '$status' 
        WHERE userId = $id
    ");
}

header("Location: users.php");
exit;