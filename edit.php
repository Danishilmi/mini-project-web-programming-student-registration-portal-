<?php
include 'db_conn.php';
$id = $_GET['id'];
if (isset($_POST['update'])) {
    $course = $_POST['course'];
    mysqli_query($conn, "UPDATE students SET course='$course' WHERE id=$id");
    header("Location: students.php");
}
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM students WHERE id=$id"));
?>
<!DOCTYPE html>
<html>
<body style="font-family: Arial; background-color: skyblue; text-align: center;">
    <h2>Edit Course for <?php echo $data['full_name']; ?></h2>
    <form method="POST">
        <input type="text" name="course" value="<?php echo $data['course']; ?>">
        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>