<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>المتجر الالكتروني</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">المتجر الالكتروني</a>
    <div>
        <a href="cart.php" class="btn btn-outline-light">
            السلة (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)
        </a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="btn btn-outline-danger">تسجيل خروج</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-outline-success">تسجيل دخول</a>
        <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container my-4">
    <h2 class="mb-4">كل المنتجات</h2>
    
    <div class="row">
    <?php
    $sql = "SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id=c.id ORDER BY p.id DESC";
    $result = $conn->query($sql);
    
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $img = $row['image'] ? "uploads/{$row['image']}" : "https://via.placeholder.com/300x200";
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo $img; ?>" class="card-img-top" style="height:200px; object-fit:cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['name']; ?></h5>
                        <p class="text-muted"><?php echo $row['cat_name']; ?></p>
                        <p class="card-text"><?php echo substr($row['description'], 0, 100); ?>...</p>
                        <h4 class="text-primary"><?php echo $row['price']; ?> $</h4>
                    
                        <a href="cart.php?add=<?php echo $row['id']; ?>" class="btn btn-primary w-100">اضافة للسلة 🛒</a>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<div class='col-12'><div class='alert alert-warning'>لا توجد منتجات حاليا. اضف منتجات من لوحة التحكم</div></div>";
    }
    ?>
    </div>
</div>

</body>
</html>
