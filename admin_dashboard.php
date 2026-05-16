<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// =========================
// SEARCH & FILTER
// =========================

$search = "";
$strand_filter = "";
$grade_filter = "";

$where = "WHERE 1=1";

// SEARCH
if (isset($_GET['search']) && !empty($_GET['search'])) {

    $search = mysqli_real_escape_string($conn, $_GET['search']);

    $where .= " AND (
        students.firstname LIKE '%$search%'
        OR students.lastname LIKE '%$search%'
        OR students.student_id LIKE '%$search%'
        OR students.phone LIKE '%$search%'
    )";
}

// STRAND FILTER
if (isset($_GET['strand']) && !empty($_GET['strand'])) {

    $strand_filter = mysqli_real_escape_string($conn, $_GET['strand']);

    $where .= " AND students.strand='$strand_filter'";
}

// GRADE FILTER
if (isset($_GET['grade_level']) && !empty($_GET['grade_level'])) {

    $grade_filter = mysqli_real_escape_string($conn, $_GET['grade_level']);

    $where .= " AND students.grade_level='$grade_filter'";
}

// =========================
// JOIN QUERY
// =========================

$students = mysqli_query($conn,
    "SELECT students.*
     FROM students
     $where
     ORDER BY students.id ASC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container dashboard-container">

    <h1>Admin Dashboard</h1>

    <!-- ========================= -->
    <!-- SEARCH & FILTER -->
    <!-- ========================= -->

    <form method="GET">

        <!-- SEARCH -->
        <input type="text"
               name="search"
               placeholder="Search student"
               value="<?php echo $search; ?>">

        <!-- STRAND FILTER -->
        <select name="strand">

            <option value="">All Strands</option>

            <option value="ABM"
                <?php if($strand_filter=="ABM") echo "selected"; ?>>
                ABM
            </option>

            <option value="STEM"
                <?php if($strand_filter=="STEM") echo "selected"; ?>>
                STEM
            </option>

            <option value="HUMSS"
                <?php if($strand_filter=="HUMSS") echo "selected"; ?>>
                HUMSS
            </option>

            <option value="TVL (Programming)"
                <?php if($strand_filter=="TVL (Programming)") echo "selected"; ?>>
                TVL (Programming)
            </option>

            <option value="TVL (Cookery)"
                <?php if($strand_filter=="TVL (Cookery)") echo "selected"; ?>>
                TVL (Cookery)
            </option>

        </select>

        <!-- GRADE FILTER -->
        <select name="grade_level">

            <option value="">All Grade Levels</option>

            <option value="11"
                <?php if($grade_filter=="11") echo "selected"; ?>>
                Grade 11
            </option>

            <option value="12"
                <?php if($grade_filter=="12") echo "selected"; ?>>
                Grade 12
            </option>

        </select>

        <button type="submit">
            Search / Filter
        </button>

    </form>

    <br>

    <a href="logout.php">Logout</a>

    <br><br>

    <!-- ========================= -->
    <!-- STUDENT TABLE -->
    <!-- ========================= -->

    <table border="1" width="100%" cellpadding="10">

        <tr>
            <th>ID</th>
            <th>Student ID</th>
            <th>Name</th>
            <th>Strand</th>
            <th>Grade</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($students)) { ?>

        <tr>

            <td><?php echo $row['id']; ?></td>

            <td><?php echo htmlspecialchars($row['student_id'] ?? 'Not issued'); ?></td>

            <td>
                <?php
                echo $row['firstname']
                . " " .
                $row['lastname'];
                ?>
            </td>

            <td>
                <?php echo $row['strand']; ?>
            </td>

            <td>
                <?php echo $row['grade_level']; ?>
            </td>

            <td>
                <?php
                echo $row['verified']
                ? 'Verified'
                : 'Pending';
                ?>
            </td>

            <td>

                <a href="edit_student.php?id=<?php echo $row['id']; ?>">
                    Edit
                </a>

                |

                <a href="delete_student.php?id=<?php echo $row['id']; ?>"
                   data-confirm-delete="Delete this student record?">

                    Delete

                </a>

            </td>

        </tr>

        <?php } ?>

    </table>

</div>

<script src="assets/js/admin-dashboard.js"></script>
</body>
</html>
