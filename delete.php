<?php
require_once "auth.php";
require_login();
require_once "config.php";

$id = (int) ($_GET["id"] ?? 0);
$stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: view.php");
exit();
?>
