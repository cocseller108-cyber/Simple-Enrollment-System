<?php
session_start();
include 'db.php';

if (!isset($_SESSION['phone'])) {
    header("Location: index.php");
    exit();
}

$phone = $_SESSION['phone'];
$message = "";

if (isset($_POST['verify'])) {

    $entered_otp = $_POST['otp'];

    $query = "SELECT * FROM students WHERE phone='$phone' AND otp='$entered_otp'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {

        mysqli_query($conn, "UPDATE students SET verified=1 WHERE phone='$phone'");

        header("Location: dashboard.php");
        exit();

    } else {
        $message = "Invalid OTP Code";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">
    <h2>OTP Verification</h2>

    <p><?php echo $message; ?></p>

    <form method="POST">
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <button type="submit" name="verify">Verify OTP</button>
    </form>
</div>

</body>
</html>