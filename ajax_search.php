<?php
require_once 'config.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

$sql = "SELECT * FROM courses WHERE course_code LIKE :q OR course_name LIKE :q";
$stmt = $pdo->prepare($sql);
$search = "%" . $q . "%";
$stmt->bindParam(':q', $search);
$stmt->execute();

echo "<ul class='list-group list-group-flush gap-2'>";

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li class='list-group-item d-flex justify-content-between align-items-center rounded bg-dark border-0 shadow-sm'>";
        echo "<div><span class='fw-bold text-info'>" . htmlspecialchars($row['course_code']) . "</span><br><small class='text-muted'>" . htmlspecialchars($row['course_name']) . "</small></div>";
        echo "<a href='dashboard.php?register_course={$row['id']}' class='btn btn-sm btn-primary rounded-pill px-3'>Register</a>";
        echo "</li>";
    }
} else {
    echo "<div class='text-center text-muted mt-3 py-4 bg-dark rounded border border-secondary border-opacity-25'><i class='bi bi-search'></i> No courses found matching your search.</div>";
}

echo "</ul>";
?>