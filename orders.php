<?php
session_start();
include '../db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$msg = "";

if(isset($_POST['update_status'])){
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $conn->query("UPDATE orders SET status='$status' WHERE id=$order_id");
    $msg = "<div class='alert alert-success'>تم تحديث حالة الطلب</div>";
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ادارة الطلبات</title>
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
    <h2 class="mb-4">ادارة الطلبات</h2>
    <?php echo $msg; ?>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>رقم الطلب</th>
                        <th>المستخدم</th>
                        <th>التاريخ</th>
                        <th>الإجمالي</th>
                        <th>الحالة</th>
                        <th>التفاصيل</th>
                        <th>تحديث</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id=u.id ORDER BY o.id DESC";
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()){
                    echo "<tr>
                        <td>#{$row['id']}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['order_date']}</td>
                        <td>{$row['total']} $</td>
                        <td>
                            <span class='badge bg-".($row['status']=='completed'?'success':($row['status']=='pending'?'warning':'danger'))."'>
                            {$row['status']}
                            </span>
                        </td>
                        <td>
                            <button class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#order{$row['id']}'>عرض</button>
                        </td>
                        <td>
                            <form method='POST' class='d-flex'>
                                <input type='hidden' name='order_id' value='{$row['id']}'>
                                <select name='status' class='form-select form-select-sm'>
                                    <option value='pending' ".($row['status']=='pending'?'selected':'').">pending</option>
                                    <option value='completed' ".($row['status']=='completed'?'selected':'').">completed</option>
                                    <option value='cancelled' ".($row['status']=='cancelled'?'selected':'').">cancelled</option>
                                </select>
                                <button name='update_status' class='btn btn-primary btn-sm ms-1'>حفظ</button>
                            </form>
                        </td>
                    </tr>";

                    // مودال تفاصيل الطلب
                    echo "<div class='modal fade' id='order{$row['id']}'>
                        <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'><h5>تفاصيل الطلب #{$row['id']}</h5></div>
                            <div class='modal-body'>";
                    
                    $items = $conn->query("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id={$row['id']}");
                    while($it = $items->fetch_assoc()){
                        echo "<p>{$it['name']} x {$it['quantity']} = ".($it['quantity']*$it['price'])." $</p>";
                    }
                    echo "</div></div></div></div>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>