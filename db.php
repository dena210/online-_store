<?php
$host = "localhost";
$user = "root"; 
$pass = "";
$dbname = "online _store";
$conn = new mysqli($host, $user, $pass, $dbname, 3306);
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}
?>