<?php
session_start();
include 'db.php';
include 'common.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // PERSONAL INFO
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $middlename = mysqli_real_escape_string($conn, $_POST['middlename']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);

    $birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);

    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $nationality = mysqli_real_escape_string($conn, $_POST['nationality']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $guardian_phone = mysqli_real_escape_string($conn, $_POST['guardian_phone']);

    // ACADEMIC INFO
    $grade_level = mysqli_real_escape_string($conn, $_POST['grade_level']);
    $strand = mysqli_real_escape_string($conn, $_POST['strand']);

    $previous_school = mysqli_real_escape_string($conn, $_POST['previous_school']);
    $school_year = mysqli_real_escape_string($conn, $_POST['school_year']);

    // OTP
    $otp = rand(100000, 999999);

    // INSERT
    $query = "INSERT INTO students(
        firstname,
        middlename,
        lastname,
        birthdate,
        age,
        gender,
        nationality,
        address,
        phone,
        email,
        guardian_phone,
        grade_level,
        strand,
        previous_school,
        school_year,
        otp,
        verified
    ) VALUES (
        '$firstname',
        '$middlename',
        '$lastname',
        '$birthdate',
        '$age',
        '$gender',
        '$nationality',
        '$address',
        '$phone',
        '$email',
        '$guardian_phone',
        '$grade_level',
        '$strand',
        '$previous_school',
        '$school_year',
        '$otp',
        0
    )";

    if (mysqli_query($conn, $query)) {

        $_SESSION['register_phone'] = $phone;

        sendSMS($phone, "Your OTP Code is: $otp");

        header("Location: verify_otp.php");
        exit();

    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>