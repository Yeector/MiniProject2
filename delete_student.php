<?php
require_once 'config.php';

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $student_id = (int)$_GET['id'];
    
    try {
        // First, delete all course registrations for this student to prevent orphan records
        $stmt = $pdo->prepare("DELETE FROM registrations WHERE student_id = ?");
        $stmt->execute([$student_id]);
        
        // Next, delete the actual user account (ensuring we only delete if they are a student)
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'student'");
        $stmt->execute([$student_id]);
        
    } catch(PDOException $e) {
        // Handle potential DB errors silently on the frontend or log them
    }
}

// Redirect back to dashboard
header("Location: dashboard.php");
exit;
?>