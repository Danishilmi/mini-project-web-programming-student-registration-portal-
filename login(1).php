<?php
session_start();
require_once "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $conn->prepare("SELECT id, username, password_hash, full_name FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (hash("sha256", $password) === $user["password_hash"]) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["full_name"] = $user["full_name"];
            header("Location: dashboard.php");
            exit();
        }
    }

    $error = "Invalid username or password.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Student Registration Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
    <main class="login-shell">
        <section class="login-panel">
            <h1>Student Registration Portal</h1>
            <p class="muted">German-Malaysian Institute</p>

            <?php if ($error): ?>
                <div class="alert error"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="post" class="form-stack">
                <label>
                    Username
                    <input type="text" name="username" required autofocus>
                </label>
                <label>
                    Password
                    <input type="password" name="password" required>
                </label>
                <button type="submit" class="primary-btn">Login</button>
            </form>

            <p class="hint">Demo login: <strong>admin</strong> / <strong>admin123</strong></p>
        </section>
    </main>
</body>
</html>
