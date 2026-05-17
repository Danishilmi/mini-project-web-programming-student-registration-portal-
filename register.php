<?php
require_once "auth.php";
require_login();
require_once "config.php";

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $studentId = trim($_POST["student_id"] ?? "");
    $fullName = trim($_POST["full_name"] ?? "");
    $gender = $_POST["gender"] ?? "";
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $dob = $_POST["dob"] ?? "";
    $address = trim($_POST["address"] ?? "");
    $course = $_POST["course"] ?? "";
    $semester = (int) ($_POST["semester"] ?? 1);
    $enrollmentYear = (int) ($_POST["enrollment_year"] ?? date("Y"));
    $skills = implode(", ", $_POST["skills"] ?? []);
    $hostel = isset($_POST["hostel"]) ? 1 : 0;
    $age = calculate_age($dob);
    $totalFee = calculate_fee($course, $semester, $hostel);

    if ($studentId === "" || $fullName === "" || $email === "" || $course === "") {
        $error = "Please complete all required fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO students (student_id, full_name, gender, email, phone, dob, age, address, course, semester, enrollment_year, skills, hostel, total_fee) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssissiisid", $studentId, $fullName, $gender, $email, $phone, $dob, $age, $address, $course, $semester, $enrollmentYear, $skills, $hostel, $totalFee);

        if ($stmt->execute()) {
            $message = "Student registered successfully. Age and total fee were calculated automatically.";
        } else {
            $error = "Unable to save student. Student ID or email may already exist.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Student | Student Registration Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "nav.php"; ?>
    <main class="container">
        <section class="page-header">
            <div>
                <h1>Register Student</h1>
                <p class="muted">Submit student information into the database.</p>
            </div>
        </section>

        <?php if ($message): ?><div class="alert success"><?= e($message) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>

        <form method="post" class="content-panel form-grid">
            <fieldset>
                <legend>Personal Information</legend>
                <label>Student ID <input type="text" name="student_id" placeholder="SWE2026001" required></label>
                <label>Full Name <input type="text" name="full_name" required></label>
                <label>Email <input type="email" name="email" required></label>
                <label>Phone Number <input type="tel" name="phone" required></label>
                <label>Date of Birth <input type="date" name="dob" required></label>
                <label>Address <textarea name="address" rows="4" required></textarea></label>
                <div class="radio-row">
                    <span>Gender</span>
                    <label><input type="radio" name="gender" value="Male" required> Male</label>
                    <label><input type="radio" name="gender" value="Female"> Female</label>
                </div>
            </fieldset>

            <fieldset>
                <legend>Academic Information</legend>
                <label>Course
                    <select name="course" required>
                        <option value="">Choose course</option>
                        <option>Software Engineering</option>
                        <option>Network Security</option>
                        <option>Mechatronics</option>
                        <option>Automotive Technology</option>
                        <option>Electrical Engineering</option>
                    </select>
                </label>
                <label>Semester
                    <select name="semester" required>
                        <?php for ($i = 1; $i <= 6; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </label>
                <label>Enrollment Year <input type="number" name="enrollment_year" min="2020" max="2035" value="2026" required></label>
                <div class="checkbox-row">
                    <span>Skills</span>
                    <label><input type="checkbox" name="skills[]" value="HTML"> HTML</label>
                    <label><input type="checkbox" name="skills[]" value="PHP"> PHP</label>
                    <label><input type="checkbox" name="skills[]" value="MySQL"> MySQL</label>
                    <label><input type="checkbox" name="skills[]" value="Python"> Python</label>
                    <label><input type="checkbox" name="skills[]" value="Java"> Java</label>
                    <label><input type="checkbox" name="skills[]" value="SQL"> SQL</label>
                    <label><input type="checkbox" name="skills[]" value="CSS"> CSS</label>
                </div>
                <label class="check-single"><input type="checkbox" name="hostel" value="1"> Hostel required</label>
                <p class="hint">Calculation: total fee = course fee x semester + RM100 registration fee + RM450 hostel fee if selected.</p>
            </fieldset>

            <div class="form-actions">
                <button type="submit" class="primary-btn">Submit Value</button>
                <button type="reset" class="secondary-btn">Reset</button>
                <a class="secondary-btn" href="view.php">View Data</a>
            </div>
        </form>
    </main>
</body>
</html>
