<?php
session_start();
include '../db.php';

// حماية: بس الادمن
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$msg = "";

// حذف منتج
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id=$id");
    $msg = "<div class='alert alert-danger'>تم حذف المنتج</div>";
}

// اضافة / تعديل منتج
if(isset($_POST['save_product'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    
    // رفع الصورة
    $image = "";
    if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
        $target_dir = "../uploads/";
        if(!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $image = time() . "_" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image);
    }

    if($id == ""){ // اضافة جديد
        $sql = "INSERT INTO products (name, description, price, category_id, image) 
                VALUES ('$name', '$description', '$price', '$category_id', '$image')";
        $msg = "<div class='alert alert-success'>تم اضافة المنتج</div>";
    } else { // تعديل
        if($image != "") $img_sql = ", image='$image'";
        else $img_sql = "";
        $sql = "UPDATE products SET name='$name', description='$description', price='$price', 
                category_id='$category_id' $img_sql WHERE id=$id";
        $msg = "<div class='alert alert-success'>تم تعديل المنتج</div>";
    }
    $conn->query($sql);
}

// جلب بيانات للتعديل
$edit_data = ['id'=>'','name'=>'','description'=>'','price'=>'','category_id'=>'','image'=>''];
if(isset($_GET['edit'])){
    $edit_id = $_GET['edit'];
    $res = $conn->query("SELECT * FROM products WHERE id=$edit_id");
    $edit_data = $res->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ادارة المنتجات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">لوحة التحكم</a>
    <a href="../index.php" class="btn btn-outline-light btn-sm">عرض المتجر</a>
  </div>
</nav>

<div class="container my-4">
    <h2 class="mb-4">ادارة المنتجات</h2>
    <?php echo $msg; ?>

    <!-- نموذج الاضافة والتعديل -->
    <div class="card mb-4">
        <div class="card-header"><?php echo $edit_data['id'] ? 'تعديل منتج' : 'اضافة منتج جديد'; ?></div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>اسم المنتج</label>
                        <input type="text" name="name" value="<?php echo $edit_data['name']; ?>" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>السعر $</label>
                        <input type="number" step="0.01" name="price" value="<?php echo $edit_data['price']; ?>" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>التصنيف</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">اختر تصنيف</option>
                            <?php
                            $cats = $conn->query("SELECT * FROM categories");
                            while($c = $cats->fetch_assoc()){
                                $sel = ($c['id'] == $edit_data['category_id']) ? 'selected' : '';
                                echo "<option value='{$c['id']}' $sel>{$c['name']}</option>";
                            }