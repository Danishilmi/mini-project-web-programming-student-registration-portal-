<?php include 'db_conn.php'; ?>
<!DOCTYPE html>
<html>
<head><title>View Students</title></head>
<body style="font-family: Arial; background-color: skyblue; margin: 0;">

    <nav style="background: #333; padding: 12px; text-align: center;">
        <a href="home.php" style="color: white; margin: 15px; text-decoration: none;">Home</a>
        <a href="register.php" style="color: white; margin: 15px; text-decoration: none;">Register</a>
        <a href="students.php" style="color: white; margin: 15px; text-decoration: none;">View Students</a>
        <a href="index.php" style="color: white; margin: 15px; text-decoration: none;">Logout</a>
    </nav>

    <h2 style="text-align: center; margin-top: 20px;">Registered Students List</h2>

    <div style="display: flex; justify-content: center; margin-bottom: 20px;">
        <form method="GET" style="background: white; padding: 15px; border-radius: 5px;">
            <input type="text" name="search" placeholder="Search ID or Name..." style="padding: 8px; width: 250px;">
            <button type="submit" style="padding: 8px;">Search</button>
            <a href="students.php"><button type="button" style="padding: 8px;">View All</button></a>
        </form>
    </div>

    <div style="display: flex; justify-content: center;">
        <table border="1" style="background: white; width: 90%; border-collapse: collapse; text-align: center; box-shadow: 0px 4px 10px rgba(0,0,0,0.1);">
            <tr style="background: #444; color: white;">
                <th style="padding: 12px;">Student ID</th>
                <th>Full Name</th>
                <th>Course</th>
                <th>Semester</th>
                <th>Actions</th>
            </tr>
            <?php
            $q = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
            $sql = "SELECT * FROM students WHERE full_name LIKE '%$q%' OR student_id LIKE '%$q%'";
            $res = mysqli_query($conn, $sql);
            
            if(mysqli_num_rows($res) > 0) {
                while($row = mysqli_fetch_assoc($res)) {
                    echo "<tr style='border-bottom: 1px solid #ddd;'>
                        <td style='padding: 10px;'>{$row['student_id']}</td>
                        <td>{$row['full_name']}</td>
                        <td>{$row['course']}</td>
                        <td>{$row['semester']}</td>
                        <td>
                            <a href='edit.php?id={$row['id']}' style='text-decoration:none; color: blue;'>Edit</a> | 
                            <a href='delete.php?id={$row['id']}' onclick='return confirm(\"Delete this student?\")' style='text-decoration:none; color: red;'>Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='padding: 20px;'>No students found.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>