<?php
session_start();
include 'db.php';
include 'database_helpers.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$id = (int)$_GET['id'];

$student = get_student_profile_by_id($conn, $id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $strand = mysqli_real_escape_string($conn, $_POST['strand']);
    $grade_level = mysqli_real_escape_string($conn, $_POST['grade_level']);
    $guardian_phone = mysqli_real_escape_string($conn, $_POST['guardian_phone']);

    $grade_level_id = get_lookup_id($conn, 'grade_levels', 'level', $grade_level);
    $strand_id = get_lookup_id($conn, 'strands', 'name', $strand);

    mysqli_query($conn, "
        UPDATE students SET
        firstname='$firstname',
        lastname='$lastname'
        WHERE id=$id
    ");

    mysqli_query($conn, "
        UPDATE enrollments SET
        strand_id=$strand_id,
        grade_level_id=$grade_level_id
        WHERE student_id=$id
    ");

    mysqli_query($conn, "
        UPDATE guardians SET
        guardian_phone='$guardian_phone'
        WHERE student_id=$id
    ");

    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">

<h1>Edit Student</h1>

<form method="POST">

    <!-- NAME -->
    <input type="text" name="firstname"
           value="<?php echo $student['firstname']; ?>"
           placeholder="First Name"
           required>

    <input type="text" name="lastname"
           value="<?php echo $student['lastname']; ?>"
           placeholder="Last Name"
           required>

    <!-- STRAND (CONTROLLED) -->
    <select name="strand" required>
        <option value="HUMSS" <?php if($student['strand']=="HUMSS") echo "selected"; ?>>HUMSS</option>
        <option value="ABM" <?php if($student['strand']=="ABM") echo "selected"; ?>>ABM</option>
        <option value="STEM" <?php if($student['strand']=="STEM") echo "selected"; ?>>STEM</option>
        <option value="TVL (Programming)" <?php if($student['strand']=="TVL (Programming)") echo "selected"; ?>>
            TVL (Programming)
        </option>
        <option value="TVL (Cookery)" <?php if($student['strand']=="TVL (Cookery)") echo "selected"; ?>>
            TVL (Cookery)
        </option>
    </select>

    <!-- GRADE LEVEL (CONTROLLED) -->
    <select name="grade_level" required>
        <option value="11" <?php if($student['grade_level']=="11") echo "selected"; ?>>Grade 11</option>
        <option value="12" <?php if($student['grade_level']=="12") echo "selected"; ?>>Grade 12</option>
    </select>

    <!-- GUARDIAN PHONE -->
    <input type="text" name="guardian_phone"
           value="<?php echo $student['guardian_phone']; ?>"
           placeholder="Guardian Phone"
           required>

    <button type="submit">Update Student</button>

</form>

</div>

</body>
</html>
