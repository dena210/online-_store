<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username']; 
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', 'user')";
    
    if ($conn->query($sql)) {
        echo "تم التسجيل بنجاح. <a href='login.php'>سجل دخول</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل حساب</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="col-md-6 mx-auto bg-white p-4 rounded shadow">
        <h2 class="text-center">تسجيل حساب جديد</h2>
        <form method="POST">
            <div class="mb-3">
                <label>اسم المستخدم</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>الإيميل</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>كلمة المرور</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">تسجيل</button>
        </form>
        <p class="mt-3 text-center">عندك حساب؟ <a href="login.php">سجل دخول</a></p>
    </div>
</div>
</body>
</html>