<?php
session_start();
include 'db.php';
include 'common.php'; // YOUR SMS FILE

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $year_level = mysqli_real_escape_string($conn, $_POST['year_level']);

    $otp = rand(100000, 999999);

    $query = "INSERT INTO students(fullname,email,phone,course,year_level,otp)
              VALUES('$fullname','$email','$phone','$course','$year_level','$otp')";

    if(mysqli_query($conn, $query)) {

        $_SESSION['phone'] = $phone;

        // ==========================
        // SMS OTP SEND
        // ==========================

        $message = "Your Enrollment OTP Code is: $otp";

        // EXAMPLE ONLY
        // REPLACE THIS WITH YOUR common.php FUNCTION

        sendSMS($phone, $message);

        header("Location: verify_otp.php");
        exit();

    } else {
        echo "Enrollment Failed";
    }
}
?>