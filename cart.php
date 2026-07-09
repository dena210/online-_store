<?php
session_start();
include 'db.php';
if(isset($_GET['add'])){
    $id = $_GET['add'];
    if(isset($_SESSION['cart'][$id])){
        $_SESSION['cart'][$id]['qty']++;
    } else {
        $res = $conn->query("SELECT * FROM products WHERE id=$id");
        $product = $res->fetch_assoc();
        $_SESSION['cart'][$id] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'qty' => 1
        ];
    }
    header("Location: cart.php");
}
if(isset($_GET['remove'])){
    unset($_SESSION['cart'][$_GET['remove']]);
    header("Location: cart.php");
}

if(isset($_POST['update_cart'])){
    foreach($_POST['qty'] as $id => $qty){
        if($qty <= 0) unset($_SESSION['cart'][$id]);
        else $_SESSION['cart'][$id]['qty'] = $qty;
    }
    header("Location: cart.php");
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>سلة المشتريات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">المتجر</a>
    <a href="cart.php" class="btn btn-outline-light">السلة (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
  </div>
</nav>

<div class="container my-4">
    <h2>سلة المشتريات</h2>
    <?php if(empty($_SESSION['cart'])): ?>
        <div class="alert alert-info">السلة فاضية</div>
        <a href="index.php" class="btn btn-primary">تسوق الآن</a>
    <?php else: ?>
    <form method="POST">
    <table class="table table-bordered bg-white">
        <thead class="table-dark">
            <tr><th>المنتج</th><th>السعر</th><th>الكمية</th><th>المجموع</th><th>حذف</th></tr>
        </thead>
        <tbody>
        <?php foreach($_SESSION['cart'] as $id => $item): 
            $subtotal = $item['price'] * $item['qty'];
            $total += $subtotal;
            $img = $item['image'] ? "uploads/{$item['image']}" : "https://via.placeholder.com/60";
        ?>
            <tr>
                <td><img src="<?php echo $img; ?>" width="50"> <?php echo $item['name']; ?></td>
                <td><?php echo $item['price']; ?> $</td>
                <td><input type="number" name="qty[<?php echo $id; ?>]" value="<?php echo $item['qty']; ?>" min="1" class="form-control" style="width:80px"></td>
                <td><?php echo $subtotal; ?> $</td>
                <td><a href="?remove=<?php echo $id; ?>" class="btn btn-danger btn-sm">X</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr><th colspan="3">الإجمالي</th><th colspan="2"><?php echo $total; ?> $</th></tr>
        </tfoot>
    </table>
    <button name="update_cart" class="btn btn-warning">تحديث السلة</button>
    <a href="checkout.php" class="btn btn-success">اتمام الطلب</a>
    </form>
    <?php endif; ?>
</div>
</body>
</html>
