<?php

// Load environment variables (Render provides these automatically)
$host = getenv("DB_HOST");
$user = getenv("DB_USER");
$pass = getenv("DB_PASS");
$db   = getenv("DB_NAME");

// Fallback (optional for local XAMPP testing)
if (!$host) $host = "localhost";
if (!$user) $user = "root";
if (!$pass) $pass = "";
if (!$db)   $db   = "enrollment";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
