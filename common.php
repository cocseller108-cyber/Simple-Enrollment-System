<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('USERS_FILE', __DIR__ . '/data/users.csv');
define('ATTENDANCE_FILE', __DIR__ . '/data/attendance.csv');
define('OTP_LIFETIME', 120);
define('SMSGATE_USERNAME', getenv('SMSGATE_USERNAME') ?: 'RVKKSI');
define('SMSGATE_PASSWORD', getenv('SMSGATE_PASSWORD') ?: 'hwnglypp0x0ksf');
define('SMSGATE_API_URL', getenv('SMSGATE_API_URL') ?: 'https://api.sms-gate.app/3rdparty/v1/messages?skipPhoneValidation=true&deviceActiveWithin=12');
define('SMSGATE_SIM_NUMBER', (int) (getenv('SMSGATE_SIM_NUMBER') ?: 1));

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function ensure_data_file($path)
{
    if (!file_exists($path)) {
        $handle = fopen($path, 'w');
        if ($handle) {
            fclose($handle);
        }
    }
}

function read_csv_rows($path)
{
    ensure_data_file($path);
    $rows = [];
    $handle = fopen($path, 'r');

    if (!$handle) {
        return $rows;
    }

    while (($row = fgetcsv($handle)) !== false) {
        if ($row === [null] || $row === false) {
            continue;
        }

        if (count(array_filter($row, static fn($value) => trim((string) $value) !== '')) === 0) {
            continue;
        }

        $rows[] = $row;
    }

    fclose($handle);

    return $rows;
}

function append_csv_row($path, array $row)
{
    ensure_data_file($path);
    $handle = fopen($path, 'a');

    if ($handle) {
        fputcsv($handle, $row);
        fclose($handle);
    }
}

function is_users_header(array $row)
{
    return isset($row[0]) && strtolower(trim((string) $row[0])) === 'name';
}

function is_attendance_header(array $row)
{
    return isset($row[0]) && strtolower(trim((string) $row[0])) === 'student_id';
}

function normalize_user_row(array $row)
{
    if (count($row) >= 5) {
        $parentContact = trim((string) $row[2]);

        return [
            'name' => trim((string) $row[0]),
            'student_id' => trim((string) $row[1]),
            'parent_phone' => $parentContact,
            'parent_email' => $parentContact,
            'username' => trim((string) $row[3]),
            'password' => (string) $row[4],
        ];
    }

    if (count($row) >= 3) {
        return [
            'name' => trim((string) $row[0]),
            'student_id' => '',
            'parent_phone' => '',
            'parent_email' => '',
            'username' => trim((string) $row[1]),
            'password' => (string) $row[2],
        ];
    }

    return null;
}

function get_all_users()
{
    $users = [];

    foreach (read_csv_rows(USERS_FILE) as $row) {
        if (is_users_header($row)) {
            continue;
        }

        $user = normalize_user_row($row);
        if ($user !== null) {
            $users[] = $user;
        }
    }

    return $users;
}

function find_user_by_username($username)
{
    foreach (get_all_users() as $user) {
        if (strcasecmp($user['username'], trim((string) $username)) === 0) {
            return $user;
        }
    }

    return null;
}

function find_user_by_student_id($studentId)
{
    foreach (get_all_users() as $user) {
        if ($user['student_id'] !== '' && strcasecmp($user['student_id'], trim((string) $studentId)) === 0) {
            return $user;
        }
    }

    return null;
}

function get_attendance_records($studentId = null)
{
    $records = [];

    foreach (read_csv_rows(ATTENDANCE_FILE) as $row) {
        if (is_attendance_header($row)) {
            continue;
        }

        if (count($row) < 4) {
            continue;
        }

        $record = [
            'student_id' => trim((string) $row[0]),
            'name' => trim((string) $row[1]),
            'status' => trim((string) $row[2]),
            'date' => trim((string) $row[3]),
        ];

        if ($studentId !== null && strcasecmp($record['student_id'], trim((string) $studentId)) !== 0) {
            continue;
        }

        $records[] = $record;
    }

    return $records;
}

function require_student_login()
{
    if (empty($_SESSION['student'])) {
        header('Location: index.php');
        exit;
    }
}

function set_flash($message, $type = 'error')
{
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type,
    ];
}

function get_flash()
{
    if (empty($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function mask_email($email)
{
    $email = trim((string) $email);
    if ($email === '' || strpos($email, '@') === false) {
        return $email;
    }

    [$local, $domain] = explode('@', $email, 2);
    if (strlen($local) <= 2) {
        $local = substr($local, 0, 1) . '*';
    } else {
        $local = substr($local, 0, 2) . str_repeat('*', max(1, strlen($local) - 2));
    }

    return $local . '@' . $domain;
}

function normalize_phone($phone)
{
    $phone = trim((string) $phone);
    $phone = preg_replace('/[^\d+]/', '', $phone);

    if (preg_match('/^09\d{9}$/', $phone)) {
        return '+63' . substr($phone, 1);
    }

    if (preg_match('/^639\d{9}$/', $phone)) {
        return '+' . $phone;
    }

    return $phone;
}

function is_valid_phone($phone)
{
    $phone = normalize_phone($phone);

    return (bool) preg_match('/^\+\d{10,15}$/', $phone);
}

function mask_phone($phone)
{
    $phone = normalize_phone($phone);
    $length = strlen($phone);

    if ($length <= 4) {
        return $phone;
    }

    return substr($phone, 0, 3) . str_repeat('*', max(1, $length - 7)) . substr($phone, -4);
}
function sendSMS($phone, $message)
{
    $phone = normalize_phone($phone);

    $data = [
        "message" => $message,
        "phoneNumbers" => [$phone],
        "simNumber" => SMSGATE_SIM_NUMBER,
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, SMSGATE_API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    curl_setopt($ch, CURLOPT_USERPWD, SMSGATE_USERNAME . ":" . SMSGATE_PASSWORD);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return false;
    }

    curl_close($ch);

    return $response;
}
?>
