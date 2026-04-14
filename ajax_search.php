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
        echo "<li class='list-group-item d-flex justify-content-between align-items-center p-3'>";
        echo "<div><span class='fw-bold text-info fs-5'>" . htmlspecialchars($row['course_code']) . "</span><br><span class='text-light opacity-75'>" . htmlspecialchars($row['course_name']) . "</span></div>";
        echo "<a href='dashboard.php?register_course={$row['id']}' class='btn btn-sm btn-gradient rounded-pill px-4'>Enroll</a>";
        echo "</li>";
    }
} else {
    echo "<div class='text-center text-muted p-5 glass-card'><i class='bi bi-search display-4 d-block mb-3'></i> No courses found.</div>";
}
echo "</ul>";
?>