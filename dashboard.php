<?php
session_start();
include 'db.php';
include 'database_helpers.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

$student_id = mysqli_real_escape_string($conn, $_SESSION['student_id']);

$student = get_student_profile_by_number($conn, $student_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Successful</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container dashboard-container">

    <h1>Enrollment Successful</h1>

    <h2>Student Information</h2>

    <p><strong>Student ID:</strong> <?php echo $student['student_id']; ?></p>

    <p>
        <strong>Full Name:</strong>
        <?php
        echo $student['firstname'] . " " .
             $student['middlename'] . " " .
             $student['lastname'];
        ?>
    </p>

    <p><strong>Birthdate:</strong> <?php echo $student['birthdate']; ?></p>

    <p><strong>Gender:</strong> <?php echo $student['gender']; ?></p>

    <p><strong>Nationality:</strong> <?php echo $student['nationality']; ?></p>

    <p><strong>Address:</strong> <?php echo $student['address']; ?></p>

    <p><strong>Phone Number:</strong> <?php echo $student['phone']; ?></p>

    <p><strong>Email:</strong> <?php echo $student['email']; ?></p>

    <p><strong>Guardian Contact:</strong> <?php echo $student['guardian_phone']; ?></p>


    <h2>Academic Information</h2>

    <p><strong>Grade Level:</strong> <?php echo $student['grade_level']; ?></p>

    <p><strong>Strand:</strong> <?php echo $student['strand']; ?></p>

    <p><strong>Previous School:</strong> <?php echo $student['previous_school']; ?></p>

    <p><strong>School Year:</strong> <?php echo $student['school_year']; ?></p>

    <p><strong>Status:</strong> Verified</p>

    <br>

    <a href="logout.php">Logout</a>

</div>

</body>
</html>
