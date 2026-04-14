<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCRS | Polytechnic Course Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { 
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%); 
            min-height: 100vh;
            color: #e0e0e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
        }
        main { flex: 1; }
        .navbar { 
            background: rgba(15, 32, 39, 0.7) !important;
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .navbar-brand { font-weight: 800; letter-spacing: 1.5px; color: #00f2fe !important; }
        .glass-card { 
            background: rgba(255, 255, 255, 0.03); 
            backdrop-filter: blur(20px); 
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1); 
            border-radius: 20px; 
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
        }
        .form-control, .form-select {
            background-color: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }
        .form-control:focus, .form-select:focus {
            background-color: rgba(0, 0, 0, 0.5);
            border-color: #00f2fe;
            box-shadow: 0 0 15px rgba(0, 242, 254, 0.3);
            color: #fff;
        }
        .btn-gradient { 
            background: linear-gradient(45deg, #00c6ff, #0072ff); 
            border: none; 
            color: white;
            font-weight: 600;
        }
        .btn-gradient:hover { 
            background: linear-gradient(45deg, #0072ff, #00c6ff); 
            box-shadow: 0 0 20px rgba(0, 114, 255, 0.5); 
            color: white;
        }
        .list-group-item { background: transparent; border-color: rgba(255,255,255,0.05); color: #e0e0e0; margin-bottom: 8px; border-radius: 12px !important; background: rgba(0,0,0,0.2); }
        .hero-section { padding: 100px 0; text-align: center; }
        .hero-title { font-size: 3.5rem; font-weight: 800; background: -webkit-linear-gradient(#00c6ff, #0072ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="bi bi-layers-half me-2"></i>PCRS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item text-light me-4"><i class="bi bi-person-circle text-info"></i> Hi, <?= htmlspecialchars($_SESSION['username']); ?></li>
                    <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="btn btn-outline-danger btn-sm rounded-pill ms-3 px-3" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '' ?>" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-gradient btn-sm rounded-pill ms-3 px-4" href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="container py-5">