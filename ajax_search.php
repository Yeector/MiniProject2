<?php
require_once 'config.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

$sql = "SELECT * FROM courses 
        WHERE course_code LIKE :q OR course_name LIKE :q";

$stmt = $pdo->prepare($sql);
$search = "%" . $q . "%";
$stmt->bindParam(':q', $search);
$stmt->execute();

echo "<ul class='list-group'>";

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
        
        echo htmlspecialchars($row['course_code']) . " - " . htmlspecialchars($row['course_name']);
        
        // 🔥 IMPORTANT: keep same button as dashboard
        echo "<a href='dashboard.php?register_course={$row['id']}' class='btn btn-sm btn-primary'>Register</a>";
        
        echo "</li>";
    }
} else {
    echo "<li class='list-group-item text-muted'>No courses found</li>";
}

echo "</ul>";
?>