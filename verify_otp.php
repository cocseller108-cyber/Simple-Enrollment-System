<?php
session_start();
include 'db.php';

$phone = "";
$credentials = $_SESSION['new_student_credentials'] ?? null;

$otp_expiry_seconds = 60;

function generateStudentPassword($length = 10)
{
    $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
    $password = '';

    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[random_int(0, strlen($characters) - 1)];
    }

    return $password;
}

// =========================
// DETECT FLOW
// =========================

if (!empty($credentials)) {

    $mode = "credentials";

} elseif (isset($_SESSION['register_phone'])) {

    $phone = $_SESSION['register_phone'];
    $mode = "register";

} elseif (isset($_SESSION['login_phone'])) {

    $phone = $_SESSION['login_phone'];
    $mode = "login";

} else {
    header("Location: index.php");
    exit();
}

// =========================
// OTP TIMER INIT
// =========================

if (!isset($_SESSION['otp_time'])) {
    $_SESSION['otp_time'] = time();
}

// =========================
// RESEND OTP
// =========================

if (isset($_POST['resend_otp'])) {

    $elapsed = time() - $_SESSION['otp_time'];

    if ($elapsed >= $otp_expiry_seconds) {

        $new_otp = rand(100000, 999999);

        mysqli_query($conn,
            "UPDATE students
             SET otp='$new_otp'
             WHERE phone='$phone'"
        );

        // SEND SMS HERE (SMSGateway)
        // send_sms($phone, "Your OTP is: $new_otp");

        $_SESSION['otp_time'] = time();
        $success = "A new OTP has been sent.";

    } else {
        $remaining = $otp_expiry_seconds - $elapsed;
        $error = "Please wait {$remaining} seconds before resending.";
    }
}

// =========================
// VERIFY OTP
// =========================

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['resend_otp'])) {

    if ((time() - $_SESSION['otp_time']) > $otp_expiry_seconds) {

        $error = "OTP expired. Please resend a new code.";

    } else {

        $otp = mysqli_real_escape_string($conn, $_POST['otp']);

        $query = mysqli_query($conn,
            "SELECT * FROM students
             WHERE phone='$phone'
             AND otp='$otp'"
        );

        if (mysqli_num_rows($query) > 0) {

            $student = mysqli_fetch_assoc($query);

            mysqli_query($conn,
                "UPDATE students
                 SET otp=NULL
                 WHERE phone='$phone'"
            );

            // =========================
            // REGISTER FLOW
            // =========================

            if ($mode == "register") {

                $student_id = $student['student_id'];
                $temporary_password = "";

                if (empty($student_id)) {

                    do {
                        $student_id = "STUD" . rand(1000, 9999);

                        $check = mysqli_query($conn,
                            "SELECT id FROM students
                             WHERE student_id='$student_id'"
                        );

                    } while (mysqli_num_rows($check) > 0);
                }

                if (empty($student['password_hash'])) {

                    $temporary_password = generateStudentPassword();

                    $password_hash = mysqli_real_escape_string(
                        $conn,
                        password_hash($temporary_password, PASSWORD_DEFAULT)
                    );

                    mysqli_query($conn,
                        "UPDATE students
                         SET student_id='$student_id',
                             password_hash='$password_hash',
                             verified=1
                         WHERE phone='$phone'"
                    );

                } else {

                    mysqli_query($conn,
                        "UPDATE students
                         SET student_id='$student_id',
                             verified=1
                         WHERE phone='$phone'"
                    );
                }

                $_SESSION['new_student_credentials'] = [
                    'student_id' => $student_id,
                    'password' => $temporary_password
                ];

                unset($_SESSION['register_phone']);

                header("Location: verify_otp.php");
                exit();
            }

            // =========================
            // LOGIN FLOW
            // =========================

            elseif ($mode == "login") {

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

// =========================
// TIMER
// =========================

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
        <div>
            <span>Student ID</span>
            <strong><?= htmlspecialchars($credentials['student_id']) ?></strong>
        </div>

        <div>
            <span>Temporary Password</span>
            <strong><?= htmlspecialchars($credentials['password']) ?></strong>
        </div>
    </div>

    <a class="button primary" href="student_login.php">Go to Login</a>

    <?php unset($_SESSION['new_student_credentials']); ?>

<?php } else { ?>

    <h1>Verify OTP</h1>

    <?php
    if (!empty($error)) echo "<p class='error'>$error</p>";
    if (!empty($success)) echo "<p class='success'>$success</p>";
    ?>

    <form method="POST">
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <button type="submit">Verify</button>
    </form>

    <p id="timer"></p>

    <form method="POST">
        <button type="submit"
                name="resend_otp"
                id="resendBtn"
                <?php if ($remaining_time > 0) echo "disabled"; ?>>
            Resend Code
        </button>
    </form>

<?php } ?>

</div>

<script>
    const remainingTime = <?= $remaining_time ?>;
</script>

<script src="assets/js/otp_timer.js"></script>

</body>
</html>
