<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$id = (int) $_GET['id'];

mysqli_query($conn, "DELETE FROM students WHERE id=$id");

header("Location: admin_dashboard.php");
exit();
?>
