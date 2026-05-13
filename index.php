<!DOCTYPE html>
<html>
<head>
    <title>Student Enrollment System</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">
    <h1>Student Enrollment System</h1>

    <form action="enroll.php" method="POST">
        <input type="text" name="fullname" placeholder="Full Name" required>

        <input type="email" name="email" placeholder="Email Address" required>

        <input type="text" name="phone" placeholder="Phone Number" required>

        <select name="course" required>
            <option value="">Select Course</option>
            <option>BSIT</option>
            <option>BSCS</option>
            <option>BSBA</option>
            <option>BSED</option>
        </select>

        <select name="year_level" required>
            <option value="">Year Level</option>
            <option>1st Year</option>
            <option>2nd Year</option>
            <option>3rd Year</option>
            <option>4th Year</option>
        </select>

        <button type="submit">Enroll Now</button>
    </form>
</div>

</body>
</html>