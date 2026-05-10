<?php
require_once "auth.php";
require_login();
require_once "config.php";

$id = (int) ($_GET["id"] ?? 0);
$message = "";
$error = "";

$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if (!$student) {
    header("Location: students.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullName = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $course = $_POST["course"] ?? "";
    $semester = (int) ($_POST["semester"] ?? 1);
    $hostel = isset($_POST["hostel"]) ? 1 : 0;
    $totalFee = calculate_fee($course, $semester, $hostel);

    $update = $conn->prepare("UPDATE students SET full_name = ?, email = ?, phone = ?, course = ?, semester = ?, hostel = ?, total_fee = ? WHERE id = ?");
    $update->bind_param("ssssiidi", $fullName, $email, $phone, $course, $semester, $hostel, $totalFee, $id);

    if ($update->execute()) {
        $message = "Student record updated successfully.";
        $stmt->execute();
        $student = $stmt->get_result()->fetch_assoc();
    } else {
        $error = "Unable to update student record.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student | Student Registration Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "nav.php"; ?>
    <main class="container">
        <section class="page-header">
            <div>
                <h1>Edit Student</h1>
                <p class="muted"><?= e($student["student_id"]) ?></p>
            </div>
        </section>

        <?php if ($message): ?><div class="alert success"><?= e($message) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>

        <form method="post" class="content-panel form-stack">
            <label>Full Name <input type="text" name="full_name" value="<?= e($student["full_name"]) ?>" required></label>
            <label>Email <input type="email" name="email" value="<?= e($student["email"]) ?>" required></label>
            <label>Phone <input type="tel" name="phone" value="<?= e($student["phone"]) ?>" required></label>
            <label>Course
                <select name="course" required>
                    <?php foreach (["Software Engineering", "Network Security", "Mechatronics", "Automotive Technology", "Electrical Engineering"] as $course): ?>
                        <option <?= $student["course"] === $course ? "selected" : "" ?>><?= e($course) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Semester <input type="number" name="semester" min="1" max="6" value="<?= e($student["semester"]) ?>" required></label>
            <label class="check-single"><input type="checkbox" name="hostel" value="1" <?= $student["hostel"] ? "checked" : "" ?>> Hostel required</label>
            <div class="form-actions">
                <button type="submit" class="primary-btn">Edit Data</button>
                <a class="secondary-btn" href="view.php">Back</a>
            </div>
        </form>
    </main>
</body>
</html>
