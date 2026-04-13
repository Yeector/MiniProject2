<?php
require_once 'config.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role']; 

    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    
    if ($stmt->rowCount() > 0) {
        $error = "Username is already taken. Please choose another.";
    } else {
        // Hash the password securely
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $hashed_password, $role])) {
            $success = "Account successfully created! You may now login.";
        } else {
            $error = "An error occurred during registration.";
        }
    }
}
require_once 'header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4 mt-5">
            <h3 class="text-center mb-4"><i class="bi bi-person-plus text-primary"></i> Create Account</h3>
            
            <?php if ($error): ?>
                <div class="alert alert-danger border-0"><i class="bi bi-exclamation-triangle"></i> <?= $error ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success border-0"><i class="bi bi-check-circle"></i> <?= $success ?> <a href="login.php" class="alert-link">Login here</a>.</div>
            <?php endif; ?>

            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label class="form-label">Desired Username</label>
                    <input type="text" name="username" class="form-control" placeholder="e.g. Ali123" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Secure Password" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">Register As</label>
                    <select name="role" class="form-select" required>
                        <option value="student">Student</option>
                        <option value="admin">Lecturer/Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Sign Up</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>