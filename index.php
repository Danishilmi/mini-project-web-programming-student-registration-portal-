<?php
include 'db_conn.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$user' AND password='$pass'");
    if (mysqli_num_rows($result) === 1) {
        $_SESSION['user'] = $user;
        header("Location: home.php");
    } else { echo "<script>alert('Invalid Login');</script>"; }
}
?>
<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body style="font-family: Arial; background-color: skyblue; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0;">
    <div style="background: white; width: 300px; padding: 25px; border-radius: 10px; text-align: center;">
        <h2>Student System</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required style="width: 90%; padding: 10px; margin: 8px;"><br>
            <input type="password" name="password" placeholder="Password" required style="width: 90%; padding: 10px; margin: 8px;"><br>
            <button type="submit" style="width: 95%; padding: 10px; margin: 8px;">Login</button>
        </form>
    </div>
</body>
</html>