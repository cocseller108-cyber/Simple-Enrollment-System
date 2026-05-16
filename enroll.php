<?php
session_start();
include 'db.php';
include 'common.php';
include 'database_helpers.php';

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

    mysqli_begin_transaction($conn);

    try {
        $grade_level_id = get_lookup_id($conn, 'grade_levels', 'level', $grade_level);
        $strand_id = get_lookup_id($conn, 'strands', 'name', $strand);
        $school_year_id = get_lookup_id($conn, 'school_years', 'name', $school_year);

        mysqli_query($conn, "INSERT INTO students(
            firstname,
            middlename,
            lastname,
            birthdate,
            age,
            gender,
            nationality,
            address
        ) VALUES (
            '$firstname',
            '$middlename',
            '$lastname',
            '$birthdate',
            '$age',
            '$gender',
            '$nationality',
            '$address'
        )");

        $student_db_id = mysqli_insert_id($conn);

        mysqli_query($conn, "INSERT INTO student_contacts(student_id, phone, email)
            VALUES ($student_db_id, '$phone', '$email')");

        mysqli_query($conn, "INSERT INTO guardians(student_id, guardian_phone)
            VALUES ($student_db_id, '$guardian_phone')");

        mysqli_query($conn, "INSERT INTO student_accounts(student_id)
            VALUES ($student_db_id)");

        mysqli_query($conn, "INSERT INTO enrollments(
            student_id,
            grade_level_id,
            strand_id,
            school_year_id,
            previous_school,
            status
        ) VALUES (
            $student_db_id,
            $grade_level_id,
            $strand_id,
            $school_year_id,
            '$previous_school',
            'Pending'
        )");

        mysqli_query($conn, "INSERT INTO otp_codes(student_id, phone, code, purpose)
            VALUES ($student_db_id, '$phone', '$otp', 'register')");

        mysqli_commit($conn);

        $_SESSION['register_phone'] = $phone;

        sendSMS($phone, "Your OTP Code is: $otp");

        header("Location: verify_otp.php");
        exit();

    } catch (Throwable $error) {
        mysqli_rollback($conn);
        echo "Error: " . mysqli_error($conn);
    }
}
?>
