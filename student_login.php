<?php
session_start();
include 'db.php';
include 'database_helpers.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $password = $_POST['password'];

    $student = get_student_profile_by_number($conn, $student_id);

    if (!empty($student) && (int) $student['verified'] === 1) {
        if (password_verify($password, $student['password_hash'])) {
            $_SESSION['student_id'] = $student['student_id'];

            header("Location: student_dashboard.php");
            exit();
        }
    }

    $error = "Invalid Student ID or password.";
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
    <p>Use the Student ID and temporary password issued after OTP verification.</p>

    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">

        <input type="text"
               name="student_id"
               placeholder="Student ID"
               required>

        <input type="password"
               name="password"
               placeholder="Password"
               required>

        <button type="submit">Login</button>

    </form>

</div>

</body>
</html>
