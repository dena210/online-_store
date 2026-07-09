<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(empty($_SESSION['cart'])){
    header("Location: cart.php");
    exit();
}

$msg = "";

if(isset($_POST['place_order'])){
    $user_id = $_SESSION['user_id'];
    $total = 0;
    
    foreach($_SESSION['cart'] as $item){
        $total += $item['price'] * $item['qty'];
    }
    
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("id", $user_id, $total);
    $stmt->execute();
    $order_id = $conn->insert_id; 
    $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?)");
    foreach($_SESSION['cart'] as $product_id => $item){
        $qty = $item['qty'];
        $price = $item['price'];
        $stmt2->bind_param("iiid", $order_id, $product_id, $qty, $price);
        $stmt2->execute();
    }
    
    unset($_SESSION['cart']);
    
    $msg = "<div class='alert alert-success'>تم استلام طلبك رقم #$order_id بنجاح! سيتم التواصل معك قريبا</div>";
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>اتمام الطلب</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">المتجر</a>
  </div>
</nav>

<div class="container my-4">
    <h2>اتمام الطلب</h2>
    <?php echo $msg; ?>
    
    <?php if($msg == ""): ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">ملخص الطلب</div>
                <div class="card-body">
                    <table class="table">
                        <?php 
                        $total = 0;
                        foreach($_SESSION['cart'] as $item): 
                            $subtotal = $item['price'] * $item['qty'];
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td><?php echo $item['name']; ?> x <?php echo $item['qty'];
