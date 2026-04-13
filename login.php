<?php
require_once 'config.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
require_once 'header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4 mt-5">
            <h3 class="text-center mb-4"><i class="bi bi-box-arrow-in-right text-primary"></i> System Login</h3>
            
            <?php if ($error): ?>
                <div class="alert alert-danger shadow-sm border-0"><i class="bi bi-exclamation-triangle"></i> <?= $error ?></div>
            <?php endif; ?>

            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter your username" required>
                    <div class="invalid-feedback">Please enter your username.</div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    <div class="invalid-feedback">Please enter your password.</div>
                </div>
                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Login to Account</button>
            </form>
            <div class="text-center mt-3">
                <small class="text-muted">Don't have an account? <a href="register.php" class="text-info text-decoration-none">Register here</a></small>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>