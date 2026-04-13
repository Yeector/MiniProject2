<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCRS | Poly Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { 
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); 
            min-height: 100vh;
            color: #e0e0e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar { 
            background: rgba(15, 32, 39, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .navbar-brand { font-weight: 700; letter-spacing: 2px; color: #00d2ff !important; }
        .card { 
            background: rgba(255, 255, 255, 0.05); 
            backdrop-filter: blur(15px); 
            border: 1px solid rgba(255, 255, 255, 0.1); 
            border-radius: 16px; 
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        .form-control, .form-select {
            background-color: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }
        .form-control:focus, .form-select:focus {
            background-color: rgba(0, 0, 0, 0.4);
            border-color: #00d2ff;
            box-shadow: 0 0 10px rgba(0, 210, 255, 0.5);
            color: #fff;
        }
        .btn-primary { background: linear-gradient(45deg, #00d2ff, #3a7bd5); border: none; }
        .btn-primary:hover { background: linear-gradient(45deg, #3a7bd5, #00d2ff); box-shadow: 0 0 15px rgba(0, 210, 255, 0.6); }
        .list-group-item { background: transparent; border-color: rgba(255,255,255,0.1); color: #e0e0e0; }
        .text-success { color: #00f2fe !important; }
        .border-success { border-color: #00f2fe !important; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark mb-5 shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="bi bi-cpu-fill"></i> PCRS System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item text-light me-3"><i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['username']); ?></li>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="btn btn-outline-danger btn-sm rounded-pill ms-3" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-primary btn-sm rounded-pill ms-2 px-3" href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container"> ```

**`footer.php`**
```php
</div> <footer class="text-center mt-5 p-4" style="color: rgba(255,255,255,0.5);">
    <small>&copy; 2026 Polytechnic Course Registration System. Built for DFP40443.</small>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>
</body>
</html>