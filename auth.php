<?php
session_start();

function require_login()
{
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit();
    }
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

function calculate_age($dob)
{
    if (!$dob) {
        return 0;
    }

    $birthDate = new DateTime($dob);
    $today = new DateTime();
    return $today->diff($birthDate)->y;
}

function calculate_fee($course, $semester, $has_hostel)
{
    $courseFees = [
        "Software Engineering" => 1250,
        "Network Security" => 1180,
        "Mechatronics" => 1320,
        "Automotive Technology" => 1150,
        "Electrical Engineering" => 1220
    ];

    $baseFee = $courseFees[$course] ?? 1000;
    $semesterFee = $baseFee * max(1, (int) $semester);
    $hostelFee = $has_hostel ? 450 : 0;

    return $semesterFee + $hostelFee + 100;
}
?>
