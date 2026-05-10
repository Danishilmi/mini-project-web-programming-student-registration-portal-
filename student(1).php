<?php
require_once "auth.php";
require_login();
require_once "config.php";

$search = trim($_GET["search"] ?? "");

if ($search !== "") {
    $term = "%" . $search . "%";
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id LIKE ? OR full_name LIKE ? OR course LIKE ? ORDER BY id DESC");
    $stmt->bind_param("sss", $term, $term, $term);
    $stmt->execute();
    $students = $stmt->get_result();
} else {
    $students = $conn->query("SELECT * FROM students ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List | Student Registration Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "nav.php"; ?>
    <main class="container">
        <section class="page-header">
            <div>
                <h1>Student List</h1>
                <p class="muted">View, search, edit, and delete student records.</p>
            </div>
            <a class="primary-btn" href="register.php">Add Student</a>
        </section>

        <form method="get" class="search-bar">
            <input type="search" name="search" value="<?= e($search) ?>" placeholder="Search by ID, name, or course">
            <button type="submit" class="primary-btn">Search Data</button>
            <a class="secondary-btn" href="students.php">Clear</a>
        </form>

        <section class="content-panel">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Semester</th>
                            <th>Age</th>
                            <th>Total Fee</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($students->num_rows === 0): ?>
                            <tr><td colspan="7" class="empty">No results found.</td></tr>
                        <?php endif; ?>
                        <?php while ($row = $students->fetch_assoc()): ?>
                            <tr>
                                <td><?= e($row["student_id"]) ?></td>
                                <td><?= e($row["full_name"]) ?></td>
                                <td><?= e($row["course"]) ?></td>
                                <td><?= e($row["semester"]) ?></td>
                                <td><?= e($row["age"]) ?></td>
                                <td>RM <?= number_format((float) $row["total_fee"], 2) ?></td>
                                <td class="action-links">
                                    <a href="view.php">View</a>
                                    <a href="edit.php?id=<?= e($row["id"]) ?>">Edit</a>
                                    <a href="delete.php?id=<?= e($row["id"]) ?>" onclick="return confirm('Delete this student record?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
