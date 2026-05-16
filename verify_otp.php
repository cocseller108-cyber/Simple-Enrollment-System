<?php
session_start();
include 'db.php';
include 'database_helpers.php';

$otp_expiry_seconds = 60;

$credentials = $_SESSION['new_student_credentials'] ?? null;

// get phone safely
$phone = $_SESSION['register_phone'] ?? $_SESSION['login_phone'] ?? "";

// redirect safety
if (empty($phone) && empty($credentials)) {
    header("Location: index.php");
    exit();
}

// detect mode
if (!empty($credentials)) {
    $mode = "credentials";
} elseif (isset($_SESSION['register_phone'])) {
    $mode = "register";
} else {
    $mode = "login";
}

// init timer
if (!isset($_SESSION['otp_time'])) {
    $_SESSION['otp_time'] = time();
}

/* =========================
   VERIFY OTP ONLY
========================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ((time() - $_SESSION['otp_time']) > $otp_expiry_seconds) {
        $error = "OTP expired. Please request a new OTP.";
    } else {

        $otp = mysqli_real_escape_string($conn, $_POST['otp']);

        $student = get_student_profile_by_phone_and_otp($conn, $phone, $otp);

        if (!empty($student)) {
            $student_db_id = (int) $student['id'];
            mysqli_query($conn,
                "DELETE FROM otp_codes
                 WHERE student_id=$student_db_id
                 AND phone='$phone'
                 AND code='$otp'"
            );

            // REGISTER FLOW
            if ($mode == "register") {

                $student_id = $student['student_id'];
                $temporary_password = "";

                if (empty($student_id)) {

                    do {
                        $student_id = "STUD" . rand(1000, 9999);

                        $check = mysqli_query($conn,
                            "SELECT id FROM student_accounts WHERE student_number='$student_id'"
                        );

                    } while (mysqli_num_rows($check) > 0);
                }

                if (empty($student['password_hash'])) {

                    $temporary_password = substr(str_shuffle(
                        "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789"
                    ), 0, 10);

                    $hash = password_hash($temporary_password, PASSWORD_DEFAULT);

                    mysqli_query($conn,
                        "UPDATE student_accounts
                         SET student_number='$student_id',
                             password_hash='$hash'
                         WHERE student_id=$student_db_id"
                    );

                } else {

                    mysqli_query($conn,
                        "UPDATE student_accounts
                         SET student_number='$student_id'
                         WHERE student_id=$student_db_id"
                    );
                }

                mysqli_query($conn,
                    "UPDATE enrollments
                     SET status='Verified'
                     WHERE student_id=$student_db_id"
                );

                $_SESSION['new_student_credentials'] = [
                    'student_id' => $student_id,
                    'password' => $temporary_password
                ];

                unset($_SESSION['register_phone']);

                header("Location: verify_otp.php");
                exit();
            }

            // LOGIN FLOW
            if ($mode == "login") {

                $_SESSION['student_id'] = $student['student_id'];

                unset($_SESSION['login_phone']);

                header("Location: student_dashboard.php");
                exit();
            }

        } else {
            $error = "Invalid OTP";
        }
    }
}

/* =========================
   TIMER
========================= */
$remaining_time = $otp_expiry_seconds - (time() - $_SESSION['otp_time']);
if ($remaining_time < 0) $remaining_time = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">

<?php if (!empty($credentials)) { ?>

    <h1>Enrollment Verified</h1>

    <div class="credential-box">
        <p><b>Student ID:</b> <?= htmlspecialchars($credentials['student_id']) ?></p>
        <p><b>Password:</b> <?= htmlspecialchars($credentials['password']) ?></p>
    </div>

    <a href="student_login.php">Go to Login</a>

    <?php unset($_SESSION['new_student_credentials']); ?>

<?php } else { ?>

    <h1>Verify OTP</h1>

    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <button type="submit">Verify</button>
    </form>

    <p id="timer"></p>

<?php } ?>

</div>

<script>
    const remainingTime = <?= $remaining_time ?>;
</script>

<script src="assets/js/otp_timer.js"></script>

</body>
</html>
