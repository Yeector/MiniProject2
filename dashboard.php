<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$message = '';

// Handle Admin Adding Course
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $role == 'admin' && isset($_POST['add_course'])) {
    $code = trim($_POST['course_code']);
    $name = trim($_POST['course_name']);
    $credits = (int)$_POST['credits'];
    
    $stmt = $pdo->prepare("INSERT INTO courses (course_code, course_name, credits) VALUES (?, ?, ?)");
    if($stmt->execute([$code, $name, $credits])) {
        $message = "<div class='alert alert-success border-0 shadow-sm rounded-pill'><i class='bi bi-check-circle-fill me-2'></i> Course Added Successfully.</div>";
    }
}

// Handle Admin Updating Course
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $role == 'admin' && isset($_POST['update_course'])) {
    $id = (int)$_POST['course_id'];
    $code = trim($_POST['course_code']);
    $name = trim($_POST['course_name']);
    $credits = (int)$_POST['credits'];
    
    $stmt = $pdo->prepare("UPDATE courses SET course_code = ?, course_name = ?, credits = ? WHERE id = ?");
    if($stmt->execute([$code, $name, $credits, $id])) {
        $message = "<div class='alert alert-info border-0 shadow-sm rounded-pill'><i class='bi bi-info-circle-fill me-2'></i> Course Updated Successfully.</div>";
    }
}

// Handle Student Registering Course
if (isset($_GET['register_course']) && $role == 'student') {
    $course_id = (int)$_GET['register_course'];
    try {
        $stmt = $pdo->prepare("INSERT INTO registrations (student_id, course_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $course_id]);
        $message = "<div class='alert alert-success border-0 shadow-sm rounded-pill'><i class='bi bi-check-circle-fill me-2'></i> Successfully registered for course!</div>";
    } catch(PDOException $e) {
        $message = "<div class='alert alert-warning border-0 shadow-sm rounded-pill'><i class='bi bi-exclamation-triangle-fill me-2'></i> You are already registered for this course.</div>";
    }
}

require_once 'header.php';
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h2 class="fw-light mb-0">Welcome back, <span class="fw-bold text-info"><?= htmlspecialchars($_SESSION['username']); ?></span></h2>
        <p class="text-muted">Manage your academic profile below.</p>
    </div>
    <div class="col-md-4 text-md-end">
        <span class="badge bg-primary text-uppercase px-4 py-2 rounded-pill shadow"><i class="bi bi-person-badge me-1"></i> <?= htmlspecialchars($role) ?> Account</span>
    </div>
</div>

<div class="row">
    <div class="col-12"><?= $message ?></div>
</div>

<?php if ($role == 'admin'): ?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="glass-card p-4 h-100">
            <h4 class="mb-4 text-info fw-bold"><i class="bi bi-plus-square-dotted me-2"></i> Add Course</h4>
            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label class="form-label text-muted small">Course Code</label>
                    <input type="text" name="course_code" class="form-control" placeholder="e.g. DFP40443" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small">Course Name</label>
                    <input type="text" name="course_name" class="form-control" placeholder="Full Stack Web Dev" required>
                </div>
                <div class="mb-4">
                    <label class="form-label text-muted small">Credits</label>
                    <input type="number" name="credits" class="form-control" placeholder="3" required min="1" max="10">
                </div>
                <button type="submit" name="add_course" class="btn btn-gradient w-100 rounded-pill"><i class="bi bi-upload me-2"></i> Publish Course</button>
            </form>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="glass-card p-4 h-100">
            <h4 class="mb-4 text-white fw-bold"><i class="bi bi-table me-2"></i> Course Directory</h4>
            <div class="table-responsive">
                <table class="table table-dark table-hover table-borderless align-middle mb-0">
                    <thead style="border-bottom: 2px solid rgba(255,255,255,0.1);">
                        <tr>
                            <th class="text-muted pb-3">Code</th>
                            <th class="text-muted pb-3">Name</th>
                            <th class="text-muted pb-3">Credits</th>
                            <th class="text-muted pb-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // 1. Fetch all courses into an array first
                        $courses = $pdo->query("SELECT * FROM courses ORDER BY id DESC")->fetchAll();
                        
                        // 2. Loop only the table rows here
                        foreach ($courses as $row):
                        ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td class="fw-bold text-info py-3"><?= htmlspecialchars($row['course_code']) ?></td>
                            <td class="py-3"><?= htmlspecialchars($row['course_name']) ?></td>
                            <td class="py-3"><span class="badge bg-secondary rounded-pill px-3"><?= $row['credits'] ?> CR</span></td>
                            <td class="text-end py-3">
                                <button class="btn btn-sm btn-outline-info rounded-pill px-3 me-2" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>"><i class="bi bi-pencil"></i></button>
                                <a href="delete_course.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Are you sure?');"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> <?php foreach ($courses as $row): ?>
<div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card">
            <div class="modal-header border-0">
                <h5 class="modal-title">Edit Course</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="course_id" value="<?= $row['id'] ?>">
                    <input type="text" name="course_code" class="form-control mb-3" value="<?= htmlspecialchars($row['course_code']) ?>" required>
                    <input type="text" name="course_name" class="form-control mb-3" value="<?= htmlspecialchars($row['course_name']) ?>" required>
                    <input type="number" name="credits" class="form-control mb-3" value="<?= $row['credits'] ?>" required>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" name="update_course" class="btn btn-gradient rounded-pill w-100">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php else: // Student View ?>
<div class="row g-4">
    <div class="col-12">
        <div class="position-relative">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-4 text-info"></i>
            <input type="text" id="searchCourse" class="form-control form-control-lg ps-5 rounded-pill glass-card border-info" placeholder="Live search courses by name or code..." style="background: rgba(0,0,0,0.4);">
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="glass-card p-4 h-100 border-top border-info border-3">
            <h4 class="mb-4 text-white"><i class="bi bi-journal-plus me-2 text-info"></i> Browse Courses</h4>
            <div id="courseList">
                <ul class='list-group list-group-flush gap-2'>
                <?php
                $stmt = $pdo->query("SELECT * FROM courses");
                while ($row = $stmt->fetch()) {
                    echo "<li class='list-group-item d-flex justify-content-between align-items-center p-3'>";
                    echo "<div><span class='fw-bold text-info fs-5'>" . htmlspecialchars($row['course_code']) . "</span><br><span class='text-light opacity-75'>" . htmlspecialchars($row['course_name']) . "</span></div>";
                    echo "<a href='dashboard.php?register_course={$row['id']}' class='btn btn-sm btn-gradient rounded-pill px-4'>Enroll</a>";
                    echo "</li>";
                }
                ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="glass-card p-4 h-100 border-top border-success border-3">
            <h4 class="mb-4 text-white"><i class="bi bi-check-square me-2 text-success"></i> My Schedule</h4>
            <ul class="list-group list-group-flush gap-2">
                <?php
                $stmt = $pdo->prepare("SELECT c.id, c.course_code, c.course_name FROM courses c JOIN registrations r ON c.id = r.course_id WHERE r.student_id = ?");
                $stmt->execute([$user_id]);
                if($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch()) {
                        echo "<li class='list-group-item d-flex justify-content-between align-items-center p-3'>";
                        echo "<div><span class='fw-bold text-success fs-5'>" . htmlspecialchars($row['course_code']) . "</span><br><span class='text-light opacity-75'>" . htmlspecialchars($row['course_name']) . "</span></div>";
                        echo "<a href='drop_course.php?id={$row['id']}' class='btn btn-sm btn-outline-danger rounded-pill px-4'>Drop</a>";
                        echo "</li>";
                    }
                } else {
                    echo "<div class='text-center p-5 text-muted'><i class='bi bi-inbox display-4 d-block mb-3'></i> No registered courses.</div>";
                }
                ?>
            </ul>
        </div>
    </div>
</div>

<script>
// AJAX Implementation
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