<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$id = (int) $_GET['id'];

mysqli_query($conn, "DELETE FROM students WHERE id=$id");

// Re-number student IDs after a deletion so the admin table remains sequential.
// This is only safe if no other tables reference students.id as a foreign key.
mysqli_query($conn, "SET @new_id = 0");
mysqli_query($conn, "UPDATE students SET id = (@new_id := @new_id + 1) ORDER BY id");

$result = mysqli_query($conn, "SELECT COALESCE(MAX(id), 0) + 1 AS next_id FROM students");
$row = mysqli_fetch_assoc($result);
$next_id = (int) $row['next_id'];

mysqli_query($conn, "ALTER TABLE students AUTO_INCREMENT = $next_id");

header("Location: admin_dashboard.php");
exit();
?>
