<?php
require_once "auth.php";
require_login();
require_once "config.php";

$totalStudents = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()["total"];
$totalFees = $conn->query("SELECT COALESCE(SUM(total_fee), 0) AS total FROM students")->fetch_assoc()["total"];
$latest = $conn->query("SELECT student_id, full_name, course, semester FROM students ORDER BY id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Student Registration Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "nav.php"; ?>
    <main class="container">
        <section class="page-header">
            <div>
                <h1>Dashboard</h1>
                <p class="muted">Welcome, <?= e($_SESSION["full_name"]) ?>.</p>
            </div>
            <a class="primary-btn" href="register.php">Register Student</a>
        </section>

        <section class="stats-grid">
            <article class="stat-card">
                <span>Total Students</span>
                <strong><?= e($totalStudents) ?></strong>
            </article>
            <article class="stat-card">
                <span>Total Calculated Fees</span>
                <strong>RM <?= number_format((float) $totalFees, 2) ?></strong>
            </article>
            <article class="stat-card">
                <span>Available Actions</span>
                <strong>Submit, View, Search, Edit, Delete</strong>
            </article>
        </section>

        <section class="content-panel">
            <h2>Latest Registrations</h2>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Semester</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $latest->fetch_assoc()): ?>
                            <tr>
                                <td><?= e($row["student_id"]) ?></td>
                                <td><?= e($row["full_name"]) ?></td>
                                <td><?= e($row["course"]) ?></td>
                                <td><?= e($row["semester"]) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
			<div class="form-actions">
                <a class="primary-btn" href="view.php">View Students</a>
            </div>
        </section>
    </main>
</body>
</html>
