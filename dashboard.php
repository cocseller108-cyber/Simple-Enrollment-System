<?php
session_start();
include 'db.php';

if (!isset($_SESSION['phone'])) {
    header("Location: index.php");
    exit();
}

$phone = $_SESSION['phone'];
$query = mysqli_query($conn, "SELECT * FROM students WHERE phone='$phone'");
$student = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enrollment Successful</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">
    <h1>Enrollment Successful</h1>

    <p><strong>Name:</strong> <?php echo $student['fullname']; ?></p>
    <p><strong>Course:</strong> <?php echo $student['course']; ?></p>
    <p><strong>Year Level:</strong> <?php echo $student['year_level']; ?></p>
    <p><strong>Status:</strong> Verified</p>

    <a href="logout.php">Logout</a>
</div>

</body>
</html>