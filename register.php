<?php
include 'db_conn.php';
if (isset($_POST['submit'])) {
    $sid = $_POST['sid']; 
    $name = $_POST['full_name']; 
    $gender = $_POST['gender'];
    $email = $_POST['email']; 
    $course = $_POST['course']; 
    $sem = $_POST['semester'];
    $year = $_POST['year']; 
    $addr = $_POST['address'];
    $skills = isset($_POST['skills']) ? implode(", ", $_POST['skills']) : "";

    // Calculation: Graduation Year (Criteria 2.4)
    $grad = $year + 3;

    $sql = "INSERT INTO students (student_id, full_name, gender, email, course, semester, enrollment_year, address, skills) 
            VALUES ('$sid', '$name', '$gender', '$email', '$course', '$sem', '$year', '$addr', '$skills')";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Student Added! Estimated Grad Year: $grad'); window.location='students.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Register Student</title></head>
<body style="font-family: Arial; background-color: skyblue; margin: 0;">

    <nav style="background: #333; padding: 12px; text-align: center;">
        <a href="home.php" style="color: white; margin: 15px; text-decoration: none;">Home</a>
        <a href="register.php" style="color: white; margin: 15px; text-decoration: none;">Register</a>
        <a href="students.php" style="color: white; margin: 15px; text-decoration: none;">View Students</a>
        <a href="index.php" style="color: white; margin: 15px; text-decoration: none;">Logout</a>
    </nav>

    <h2 style="text-align: center; margin-top: 20px;">Student Registration Form</h2>
    
    <div style="display: flex; justify-content: center; padding: 20px;">
        <form method="POST" style="background: white; padding: 30px; border-radius: 10px; width: 600px; box-shadow: 0px 4px 10px rgba(0,0,0,0.1);">
            <div style="margin-bottom: 10px;">
                Student ID: <input type="text" name="sid" required style="width:100%; padding: 8px;">
            </div>
            <div style="margin-bottom: 10px;">
                Full Name: <input type="text" name="full_name" required style="width:100%; padding: 8px;">
            </div>
            <div style="margin-bottom: 10px;">
                Gender: 
                <input type="radio" name="gender" value="Male" required> Male 
                <input type="radio" name="gender" value="Female"> Female
            </div>
            <div style="margin-bottom: 10px;">
                Email: <input type="email" name="email" style="width:100%; padding: 8px;">
            </div>
            <div style="margin-bottom: 10px;">
                Course: 
                <select name="course" style="width:100%; padding: 8px;">
                    <option>Software Engineering</option>
                    <option>Information Technology</option>
                    <option>Multimedia</option>
                </select>
            </div>
            <div style="margin-bottom: 10px;">
                Enrollment Year: <input type="number" name="year" required style="width:100%; padding: 8px;">
            </div>
            <div style="margin-bottom: 10px;">
                Semester: 
                <select name="semester" style="width:100%; padding: 8px;">
                    <option>1</option><option>2</option><option>3</option><option>4</option>
                </select>
            </div>
            <div style="margin-bottom: 10px;">
                Address: <textarea name="address" style="width:100%; padding: 8px;" rows="3"></textarea>
            </div>
            <div style="margin-bottom: 20px;">
                Skills: 
                <input type="checkbox" name="skills[]" value="HTML"> HTML 
                <input type="checkbox" name="skills[]" value="CSS"> CSS
                <input type="checkbox" name="skills[]" value="PHP"> PHP
            </div>
            <div style="text-align: center;">
                <button type="submit" name="submit" style="padding: 10px 25px; cursor: pointer;">Submit Registration</button>
            </div>
        </form>
    </div>
</body>
</html>