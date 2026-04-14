<?php 
require_once 'config.php';
require_once 'header.php'; 
?>

<div class="hero-section">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="hero-title mb-4">Welcome to PCRS</h1>
            <p class="lead mb-5 text-light" style="opacity: 0.8;">The most advanced and intuitive Polytechnic Course Registration System. Manage your academic journey with seamless precision.</p>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php" class="btn btn-gradient btn-lg rounded-pill px-5 py-3 shadow-lg"><i class="bi bi-speedometer2 me-2"></i> Enter Dashboard</a>
            <?php else: ?>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="register.php" class="btn btn-gradient btn-lg rounded-pill px-5 shadow-lg">Get Started</a>
                    <a href="login.php" class="btn btn-outline-light btn-lg rounded-pill px-5">Login</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row g-4 mt-5">
    <div class="col-md-4">
        <div class="glass-card p-4 text-center h-100">
            <i class="bi bi-lightning-charge display-4 text-warning mb-3"></i>
            <h4 class="text-white">Real-Time AJAX</h4>
            <p class="text-muted text-sm">Experience zero page reloads with our advanced live search and dynamic dynamic filtering system.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="glass-card p-4 text-center h-100">
            <i class="bi bi-shield-lock display-4 text-success mb-3"></i>
            <h4 class="text-white">Secure Access</h4>
            <p class="text-muted text-sm">Role-based authentication, password hashing, and PDO prepared statements protect your data.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="glass-card p-4 text-center h-100">
            <i class="bi bi-palette display-4 text-info mb-3"></i>
            <h4 class="text-white">Modern UI/UX</h4>
            <p class="text-muted text-sm">Fully responsive, glassmorphic design powered by the latest Bootstrap 5 framework.</p>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>