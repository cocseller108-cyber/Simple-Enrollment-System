<?php
function get_lookup_id($conn, $table, $column, $value)
{
    $allowed = [
        'grade_levels' => 'level',
        'strands' => 'name',
        'school_years' => 'name',
    ];

    if (!isset($allowed[$table]) || $allowed[$table] !== $column) {
        die("Invalid lookup table.");
    }

    $safe_value = mysqli_real_escape_string($conn, $value);
    mysqli_query($conn, "INSERT IGNORE INTO $table ($column) VALUES ('$safe_value')");

    $result = mysqli_query($conn, "SELECT id FROM $table WHERE $column='$safe_value' LIMIT 1");
    $row = mysqli_fetch_assoc($result);

    return (int) $row['id'];
}

function student_profile_sql()
{
    return "
        SELECT
            s.id,
            sa.student_number AS student_id,
            sa.password_hash,
            s.firstname,
            s.middlename,
            s.lastname,
            s.birthdate,
            s.age,
            s.gender,
            s.nationality,
            s.address,
            sc.phone,
            sc.email,
            g.guardian_phone,
            gl.level AS grade_level,
            st.name AS strand,
            e.previous_school,
            sy.name AS school_year,
            CASE WHEN e.status = 'Verified' THEN 1 ELSE 0 END AS verified,
            e.status AS enrollment_status,
            e.id AS enrollment_id
        FROM students s
        LEFT JOIN student_accounts sa ON sa.student_id = s.id
        LEFT JOIN student_contacts sc ON sc.student_id = s.id
        LEFT JOIN guardians g ON g.student_id = s.id
        LEFT JOIN enrollments e ON e.student_id = s.id
        LEFT JOIN grade_levels gl ON gl.id = e.grade_level_id
        LEFT JOIN strands st ON st.id = e.strand_id
        LEFT JOIN school_years sy ON sy.id = e.school_year_id
    ";
}

function get_student_profile_by_id($conn, $id)
{
    $id = (int) $id;
    $query = mysqli_query($conn, student_profile_sql() . " WHERE s.id=$id LIMIT 1");

    return mysqli_fetch_assoc($query);
}

function get_student_profile_by_number($conn, $student_number)
{
    $student_number = mysqli_real_escape_string($conn, $student_number);
    $query = mysqli_query($conn, student_profile_sql() . " WHERE sa.student_number='$student_number' LIMIT 1");

    return mysqli_fetch_assoc($query);
}

function get_student_profile_by_phone_and_otp($conn, $phone, $otp)
{
    $phone = mysqli_real_escape_string($conn, $phone);
    $otp = mysqli_real_escape_string($conn, $otp);

    $query = mysqli_query($conn, student_profile_sql() . "
        INNER JOIN otp_codes oc ON oc.student_id = s.id
        WHERE oc.phone='$phone'
        AND oc.code='$otp'
        LIMIT 1
    ");

    return mysqli_fetch_assoc($query);
}
?>
