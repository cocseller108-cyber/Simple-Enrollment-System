<?php
session_start();

include 'db.php';

$phone = "";

// =========================
// DETECT FLOW
// =========================

if (isset($_SESSION['register_phone'])) {

    $phone = $_SESSION['register_phone'];
    $mode = "register";

}
elseif (isset($_SESSION['login_phone'])) {

    $phone = $_SESSION['login_phone'];
    $mode = "login";

}
else {

    header("Location: index.php");
    exit();

}

// =========================
// VERIFY OTP
// =========================

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $otp = mysqli_real_escape_string($conn, $_POST['otp']);

    $query = mysqli_query($conn,
        "SELECT * FROM students
         WHERE phone='$phone'
         AND otp='$otp'"
    );

    if (mysqli_num_rows($query) > 0) {

        // =========================
        // CLEAR OTP
        // =========================

        mysqli_query($conn,
            "UPDATE students
             SET otp=NULL
             WHERE phone='$phone'"
        );

        // =========================
        // REGISTER FLOW
        // =========================

        if ($mode == "register") {

            // VERIFY STUDENT
            mysqli_query($conn,
                "UPDATE students
                 SET verified=1
                 WHERE phone='$phone'"
            );

            $_SESSION['phone'] = $phone;

            unset($_SESSION['register_phone']);

            header("Location: index.php");
            exit();

        }

        // =========================
        // LOGIN FLOW
        // =========================

        elseif ($mode == "login") {

            $_SESSION['phone'] = $phone;

            unset($_SESSION['login_phone']);

            header("Location: student_dashboard.php");
            exit();

        }

    } else {

        $error = "Invalid OTP";

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">

    <h1>Verify OTP</h1>

    <?php
    if (!empty($error)) {
        echo "<p class='error'>$error</p>";
    }
    ?>

    <form method="POST">

        <input type="text"
               name="otp"
               placeholder="Enter OTP"
               required>

        <button type="submit">
            Verify
        </button>

    </form>

</div>

</body>
</html>
