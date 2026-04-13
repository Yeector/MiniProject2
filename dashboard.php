<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $role == 'admin' && isset($_POST['add_course'])) {
    $code = trim($_POST['course_code']);
    $name = trim($_POST['course_name']);
    $credits = (int)$_POST['credits'];
    
    $stmt = $pdo->prepare("INSERT INTO courses (course_code, course_name, credits) VALUES (?, ?, ?)");
    if($stmt->execute([$code, $name, $credits])) {
        $message = "<div class='alert alert-success'>Course Added.</div>";
    }
}

if (isset($_GET['register_course']) && $role == 'student') {
    $course_id = (int)$_GET['register_course'];
    try {
        $stmt = $pdo->prepare("INSERT INTO registrations (student_id, course_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $course_id]);
        $message = "<div class='alert alert-success'>Successfully registered for course!</div>";
    } catch(PDOException $e) {
        $message = "<div class='alert alert-warning'>You are already registered for this course.</div>";
    }
}

require_once 'header.php';
?>

<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Welcome, <?= htmlspecialchars($_SESSION['username']); ?>! <span class="badge bg-secondary"><?= ucfirst($role) ?></span></h2>
        <?= $message ?>
    </div>
</div>

<?php if ($role == 'admin'): ?>
<div class="row">
    <div class="col-md-4">
        <div class="card p-3">
            <h4>Add New Course</h4>
            <form method="POST">
                <input type="text" name="course_code" class="form-control mb-2" placeholder="Course Code (e.g. DFP40443)" required>
                <input type="text" name="course_name" class="form-control mb-2" placeholder="Course Name" required>
                <input type="number" name="credits" class="form-control mb-3" placeholder="Credits" required>
                <button type="submit" name="add_course" class="btn btn-success w-100">Add Course</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card p-3">
            <h4>Manage Courses</h4>
            <table class="table table-striped">
                <tr><th>Code</th><th>Name</th><th>Credits</th><th>Actions</th></tr>
                <?php
                $stmt = $pdo->query("SELECT * FROM courses");
                while ($row = $stmt->fetch()):
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['course_code']) ?></td>
                    <td><?= htmlspecialchars($row['course_name']) ?></td>
                    <td><?= $row['credits'] ?></td>
                    <td>
                        <a href="delete_course.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</div>

<?php else: ?>
<div class="row">
    <div class="col-md-12 mb-4">
        <input type="text" id="searchCourse" class="form-control form-control-lg" placeholder="Live Search Courses by Name or Code...">
    </div>
    
    <div class="col-md-6">
        <div class="card p-3">
            <h4>Available Courses</h4>
            <div id="courseList">
                <?php
                $stmt = $pdo->query("SELECT * FROM courses");
                echo "<ul class='list-group'>";
                while ($row = $stmt->fetch()) {
                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                    echo htmlspecialchars($row['course_code']) . " - " . htmlspecialchars($row['course_name']);
                    echo "<a href='dashboard.php?register_course={$row['id']}' class='btn btn-sm btn-primary'>Register</a>";
                    echo "</li>";
                }
                echo "</ul>";
                ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card p-3 border-success">
            <h4 class="text-success">My Registered Courses</h4>
            <ul class="list-group">
                <?php
                $stmt = $pdo->prepare("
                    SELECT c.id, c.course_code, c.course_name 
                    FROM courses c 
                    JOIN registrations r ON c.id = r.course_id 
                    WHERE r.student_id = ?
                ");
                $stmt->execute([$user_id]);
                while ($row = $stmt->fetch()) {
                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                    echo htmlspecialchars($row['course_code']) . " - " . htmlspecialchars($row['course_name']);
                    echo "<a href='drop_course.php?id={$row['id']}' class='btn btn-sm btn-outline-danger'>Drop</a>";
                    echo "</li>";
                }
                ?>
            </ul>
        </div>
    </div>
</div>

<script>
document.getElementById('searchCourse').addEventListener('keyup', function() {
    let query = this.value;
    fetch('ajax_search.php?q=' + encodeURIComponent(query))
        .then(response => response.text())
        .then(data => {
            document.getElementById('courseList').innerHTML = data;
        });
});
</script>
<?php endif; ?>
<?php require_once 'footer.php'; ?>