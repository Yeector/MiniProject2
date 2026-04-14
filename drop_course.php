<?php
require_once 'config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}
if (isset($_GET['id'])) {
    $course_id = (int)$_GET['id'];
    $student_id = $_SESSION['user_id'];
    
    // Only delete where student_id matches session to prevent URL manipulation
    $stmt = $pdo->prepare("DELETE FROM registrations WHERE course_id = ? AND student_id = ?");
    $stmt->execute([$course_id, $student_id]);
}
header("Location: dashboard.php");
exit;
?>