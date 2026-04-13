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
        $message = "<div class='alert alert-success border-0 shadow-sm'><i class='bi bi-check-circle'></i> Course Added Successfully.</div>";
    }
}

if (isset($_GET['register_course']) && $role == 'student') {
    $course_id = (int)$_GET['register_course'];
    try {
        $stmt = $pdo->prepare("INSERT INTO registrations (student_id, course_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $course_id]);
        $message = "<div class='alert alert-success border-0 shadow-sm'><i class='bi bi-check-circle'></i> Successfully registered for course!</div>";
    } catch(PDOException $e) {
        $message = "<div class='alert alert-warning border-0 shadow-sm'><i class='bi bi-exclamation-circle'></i> You are already registered for this course.</div>";
    }
}

require_once 'header.php';
?>

<div class="row">
    <div class="col-12">
        <h2 class="mb-4 fw-light">Welcome back, <span class="fw-bold text-info"><?= htmlspecialchars($_SESSION['username']); ?></span>! 
        <span class="badge bg-secondary fs-6 align-middle px-3 py-2 rounded-pill"><?= ucfirst($role) ?></span></h2>
        <?= $message ?>
    </div>
</div>

<?php if ($role == 'admin'): ?>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card p-4 h-100">
            <h4 class="mb-4 text-primary"><i class="bi bi-plus-square"></i> Add New Course</h4>
            <form method="POST">
                <input type="text" name="course_code" class="form-control mb-3" placeholder="Course Code (e.g. DFP40443)" required>
                <input type="text" name="course_name" class="form-control mb-3" placeholder="Course Name" required>
                <input type="number" name="credits" class="form-control mb-4" placeholder="Credits" required>
                <button type="submit" name="add_course" class="btn btn-primary w-100 rounded-pill"><i class="bi bi-plus-circle"></i> Create Course</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card p-4 h-100">
            <h4 class="mb-4 text-info"><i class="bi bi-list-columns-reverse"></i> Manage Courses</h4>
            <div class="table-responsive">
                <table class="table table-hover table-dark table-borderless align-middle rounded overflow-hidden">
                    <thead class="table-active text-info">
                        <tr><th>Code</th><th>Name</th><th>Credits</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM courses");
                        while ($row = $stmt->fetch()):
                        ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($row['course_code']) ?></td>
                            <td><?= htmlspecialchars($row['course_name']) ?></td>
                            <td><span class="badge bg-dark border"><?= $row['credits'] ?> CR</span></td>
                            <td>
                                <a href="delete_course.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3"><i class="bi bi-trash"></i> Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<div class="row g-4">
    <div class="col-md-12">
        <div class="position-relative">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted fs-5"></i>
            <input type="text" id="searchCourse" class="form-control form-control-lg ps-5 rounded-pill shadow-sm bg-dark text-light border-secondary" placeholder="Live Search Courses by Name or Code...">
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card p-4 h-100">
            <h4 class="mb-4 text-primary"><i class="bi bi-journal-bookmark"></i> Available Courses</h4>
            <div id="courseList">
                <?php
                $stmt = $pdo->query("SELECT * FROM courses");
                echo "<ul class='list-group list-group-flush gap-2'>";
                while ($row = $stmt->fetch()) {
                    echo "<li class='list-group-item d-flex justify-content-between align-items-center rounded bg-dark border-0 shadow-sm'>";
                    echo "<div><span class='fw-bold text-info'>" . htmlspecialchars($row['course_code']) . "</span><br><small class='text-muted'>" . htmlspecialchars($row['course_name']) . "</small></div>";
                    echo "<a href='dashboard.php?register_course={$row['id']}' class='btn btn-sm btn-primary rounded-pill px-3'>Register</a>";
                    echo "</li>";
                }
                echo "</ul>";
                ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card p-4 h-100 border-success">
            <h4 class="text-success mb-4"><i class="bi bi-journal-check"></i> My Registered Courses</h4>
            <ul class="list-group list-group-flush gap-2">
                <?php
                $stmt = $pdo->prepare("
                    SELECT c.id, c.course_code, c.course_name 
                    FROM courses c 
                    JOIN registrations r ON c.id = r.course_id 
                    WHERE r.student_id = ?
                ");
                $stmt->execute([$user_id]);
                if($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch()) {
                        echo "<li class='list-group-item d-flex justify-content-between align-items-center rounded bg-dark border-0 shadow-sm'>";
                        echo "<div><span class='fw-bold text-success'>" . htmlspecialchars($row['course_code']) . "</span><br><small class='text-muted'>" . htmlspecialchars($row['course_name']) . "</small></div>";
                        echo "<a href='drop_course.php?id={$row['id']}' class='btn btn-sm btn-outline-danger rounded-pill px-3'>Drop</a>";
                        echo "</li>";
                    }
                } else {
                    echo "<div class='text-center text-muted mt-3 py-4 bg-dark rounded border border-secondary border-opacity-25'>You haven't registered for any courses yet.</div>";
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