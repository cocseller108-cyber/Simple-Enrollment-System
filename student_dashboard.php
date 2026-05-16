<?php
session_start();
include 'db.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

$student_id = mysqli_real_escape_string($conn, $_SESSION['student_id']);

$query = mysqli_query($conn,
    "SELECT * FROM students WHERE student_id='$student_id'"
);

$student = mysqli_fetch_assoc($query);

$strand = $student['strand'];
$full_name = trim($student['firstname'] . " " . $student['lastname']);
$status_label = $student['verified'] ? 'Verified' : 'Pending Review';
$status_class = $student['verified'] ? 'verified' : 'pending';

// =========================
// GENERAL SUBJECTS (ALL)
// =========================
$general_subjects = [
    ["code" => "CORE-101", "title" => "Oral Communication in Context", "area" => "Communication", "term" => "Core"],
    ["code" => "CORE-102", "title" => "Reading and Writing Skills", "area" => "Communication", "term" => "Core"],
    ["code" => "CORE-103", "title" => "Komunikasyon at Pananaliksik sa Wika at Kulturang Pilipino", "area" => "Language", "term" => "Core"],
    ["code" => "CORE-104", "title" => "21st Century Literature from the Philippines and the World", "area" => "Humanities", "term" => "Core"],
    ["code" => "CORE-105", "title" => "Understanding Culture, Society and Politics", "area" => "Social Science", "term" => "Core"],
    ["code" => "CORE-106", "title" => "General Mathematics", "area" => "Mathematics", "term" => "Core"],
    ["code" => "CORE-107", "title" => "Statistics and Probability", "area" => "Mathematics", "term" => "Core"],
    ["code" => "CORE-108", "title" => "Physical Education and Health", "area" => "Wellness", "term" => "Core"]
];

// =========================
// STRAND SUBJECTS
// =========================
$strand_subjects = [];

if ($strand == "ABM") {
    $strand_subjects = [
        ["code" => "ABM-201", "title" => "Fundamentals of Accountancy, Business and Management 1", "area" => "Accounting", "term" => "Specialized"],
        ["code" => "ABM-202", "title" => "Fundamentals of Accountancy, Business and Management 2", "area" => "Accounting", "term" => "Specialized"],
        ["code" => "ABM-203", "title" => "Business Math", "area" => "Business Analytics", "term" => "Specialized"],
        ["code" => "ABM-204", "title" => "Business Finance", "area" => "Finance", "term" => "Specialized"],
        ["code" => "ABM-205", "title" => "Principles of Marketing", "area" => "Marketing", "term" => "Specialized"],
        ["code" => "ABM-206", "title" => "Organization and Management", "area" => "Management", "term" => "Specialized"]
    ];
}

elseif ($strand == "STEM") {
    $strand_subjects = [
        ["code" => "STEM-201", "title" => "Pre-Calculus", "area" => "Mathematics", "term" => "Specialized"],
        ["code" => "STEM-202", "title" => "Basic Calculus", "area" => "Mathematics", "term" => "Specialized"],
        ["code" => "STEM-203", "title" => "General Biology 1", "area" => "Life Science", "term" => "Specialized"],
        ["code" => "STEM-204", "title" => "General Biology 2", "area" => "Life Science", "term" => "Specialized"],
        ["code" => "STEM-205", "title" => "General Physics 1", "area" => "Physical Science", "term" => "Specialized"],
        ["code" => "STEM-206", "title" => "General Physics 2", "area" => "Physical Science", "term" => "Specialized"],
        ["code" => "STEM-207", "title" => "General Chemistry 1", "area" => "Chemistry", "term" => "Specialized"],
        ["code" => "STEM-208", "title" => "General Chemistry 2", "area" => "Chemistry", "term" => "Specialized"],
        ["code" => "STEM-209", "title" => "Research in Daily Life", "area" => "Research", "term" => "Specialized"]
    ];
}

elseif ($strand == "HUMSS") {
    $strand_subjects = [
        ["code" => "HUMSS-201", "title" => "Creative Writing", "area" => "Writing", "term" => "Specialized"],
        ["code" => "HUMSS-202", "title" => "Disciplines and Ideas in Social Sciences", "area" => "Social Science", "term" => "Specialized"],
        ["code" => "HUMSS-203", "title" => "Disciplines and Ideas in Applied Social Sciences", "area" => "Applied Social Science", "term" => "Specialized"],
        ["code" => "HUMSS-204", "title" => "Philippine Politics and Governance", "area" => "Government", "term" => "Specialized"],
        ["code" => "HUMSS-205", "title" => "Community Engagement, Solidarity and Citizenship", "area" => "Civic Studies", "term" => "Specialized"],
        ["code" => "HUMSS-206", "title" => "Research in Daily Life", "area" => "Research", "term" => "Specialized"]
    ];
}

elseif (strpos($strand, "TVL") !== false) {
    $strand_subjects = [
        ["code" => "TVL-201", "title" => "TESDA Competency-Based Training", "area" => "Technical Skills", "term" => "Specialized"],
        ["code" => "TVL-202", "title" => "Work Immersion Preparation", "area" => "Workplace Readiness", "term" => "Specialized"],
        ["code" => "TVL-203", "title" => "Technical Drafting / Specialized Skills", "area" => "Applied Skills", "term" => "Specialized"]
    ];
}

$total_subjects = count($general_subjects) + count($strand_subjects);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="student-dashboard-page">

<main class="student-dashboard">
    <section class="student-hero-card">
        <div>
            <p class="eyebrow">Student Portal</p>
            <h1>Welcome, <?php echo htmlspecialchars($student['firstname']); ?>.</h1>
            <p class="lead">Review your enrollment profile, strand information, and assigned subject areas for the current school year.</p>
        </div>

        <div class="student-status-card">
            <span>Enrollment Status</span>
            <strong class="status-badge <?php echo $status_class; ?>"><?php echo $status_label; ?></strong>
            <a class="button secondary" href="logout.php">Logout</a>
        </div>
    </section>

    <section class="student-summary-grid" aria-label="Student enrollment summary">
        <article>
            <span>Student ID</span>
            <strong><?php echo htmlspecialchars($student['student_id']); ?></strong>
        </article>
        <article>
            <span>Student Name</span>
            <strong><?php echo htmlspecialchars($full_name); ?></strong>
        </article>
        <article>
            <span>Grade Level</span>
            <strong>Grade <?php echo htmlspecialchars($student['grade_level']); ?></strong>
        </article>
        <article>
            <span>Strand</span>
            <strong><?php echo htmlspecialchars($strand); ?></strong>
        </article>
    </section>

    <section class="curriculum-layout">
        <aside class="curriculum-overview">
            <div class="overview-card">
                <p class="eyebrow">Curriculum Overview</p>
                <h2><?php echo htmlspecialchars($strand); ?> Learning Plan</h2>
                <p>Your subjects are grouped into core senior high school requirements and specialized strand courses.</p>

                <div class="overview-stats">
                    <article>
                        <span>Total Subjects</span>
                        <strong><?php echo $total_subjects; ?></strong>
                    </article>
                    <article>
                        <span>Core Subjects</span>
                        <strong><?php echo count($general_subjects); ?></strong>
                    </article>
                    <article>
                        <span>Strand Subjects</span>
                        <strong><?php echo count($strand_subjects); ?></strong>
                    </article>
                </div>
            </div>

            <div class="profile-card">
                <h2>Student Details</h2>
                <dl>
                    <div>
                        <dt>School Year</dt>
                        <dd><?php echo htmlspecialchars($student['school_year']); ?></dd>
                    </div>
                    <div>
                        <dt>Phone</dt>
                        <dd><?php echo htmlspecialchars($student['phone']); ?></dd>
                    </div>
                    <div>
                        <dt>Email</dt>
                        <dd><?php echo htmlspecialchars($student['email']); ?></dd>
                    </div>
                    <div>
                        <dt>Previous School</dt>
                        <dd><?php echo htmlspecialchars($student['previous_school']); ?></dd>
                    </div>
                </dl>
            </div>
        </aside>

        <section class="subjects-panel">
            <div class="subjects-header">
                <div>
                    <p class="eyebrow">Subject Areas</p>
                    <h2>Core Senior High Subjects</h2>
                </div>
                <span><?php echo count($general_subjects); ?> subjects</span>
            </div>

            <div class="subject-grid">
                <?php foreach ($general_subjects as $subject) { ?>
                    <article class="subject-card">
                        <div>
                            <span class="subject-code"><?php echo htmlspecialchars($subject['code']); ?></span>
                            <strong><?php echo htmlspecialchars($subject['title']); ?></strong>
                        </div>
                        <p><?php echo htmlspecialchars($subject['area']); ?></p>
                        <em><?php echo htmlspecialchars($subject['term']); ?></em>
                    </article>
                <?php } ?>
            </div>

            <div class="subjects-header strand-header">
                <div>
                    <p class="eyebrow">Specialized Track</p>
                    <h2><?php echo htmlspecialchars($strand); ?> Subjects</h2>
                </div>
                <span><?php echo count($strand_subjects); ?> subjects</span>
            </div>

            <div class="subject-grid">
                <?php foreach ($strand_subjects as $subject) { ?>
                    <article class="subject-card emphasized">
                        <div>
                            <span class="subject-code"><?php echo htmlspecialchars($subject['code']); ?></span>
                            <strong><?php echo htmlspecialchars($subject['title']); ?></strong>
                        </div>
                        <p><?php echo htmlspecialchars($subject['area']); ?></p>
                        <em><?php echo htmlspecialchars($subject['term']); ?></em>
                    </article>
                <?php } ?>
            </div>
        </section>
    </section>
</main>

</body>
</html>
