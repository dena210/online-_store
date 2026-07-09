<?php
session_start();
include 'db.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$total_products = $conn->query("SELECT COUNT(*) as c FROM products")->fetch_assoc()['c'];
$total_users = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='user'")->fetch_assoc()['c'];
$total_orders = $conn->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc()['c'];
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand">مرحبا <?php echo $_SESSION['username']; ?> - لوحة التحكم</span>
    <a href="../logout.php" class="btn btn-danger">تسجيل خروج</a>
  </div>
</nav>

<div class="container mt-4">
    <div class="row text-center">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5>المنتجات</h5>
                    <h2><?php echo $total_products; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5>العملاء</h5>
                    <h2><?php echo $total_users; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5>الطلبات</h5>
                    <h2><?php echo $total_orders; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="list-group mt-4">
        <a href="products.php" class="list-group-item list-group-item-action">إدارة المنتجات - إضافة/تعديل/حذف</a>
        <a href="orders.php" class="list-group-item list-group-item-action">عرض الطلبات</a>
        <a href="../index.php" class="list-group-item list-group-item-action">الذهاب للمتجر</a>
    </div>
</div>
</body>
</html>
