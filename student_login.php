<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $phone = $_POST['phone'];

    $query = mysqli_query($conn,
        "SELECT * FROM students
         WHERE phone='$phone'"
    );

    if (mysqli_num_rows($query) > 0) {

        $_SESSION['phone'] = $phone;

        header("Location: student_dashboard.php");
        exit();

    } else {
        echo "Student Not Found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">

    <h1>Student Login</h1>

    <form method="POST">

        <input type="text"
               name="phone"
               placeholder="Phone Number"
               required>

        <button type="submit">Login</button>

    </form>

</div>

</body>
</html>
