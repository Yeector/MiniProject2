<?php
require_once 'config.php';

/*
// Role 3: Security, Auth Specialist, Server-side Validation & Password Hashing
*/
//testetststststst

require_once 'header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4">
            <h3 class="mb-4 text-center">Student Registration</h3>
            <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <?php if($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            
            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                    <div class="invalid-feedback">Please enter a username.</div>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?>