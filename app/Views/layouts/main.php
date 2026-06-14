<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Online Exam') ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- MathJax -->
    <script>
        MathJax = {
            tex: {
                inlineMath: [['$', '$'], ['\\(', '\\)']],
                displayMath: [['$$', '$$'], ['\\[', '\\]']]
            }
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    
    <style>
        :root {
            --primary-dark: #3264B9;
            --primary: #1E95E0;
            --primary-light: #5AB3F7;
            --primary-lighter: #C9E4F7;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-600: #4B5563;
            --gray-800: #1F2937;
        }
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-lighter) 0%, #ffffff 100%);
            min-height: 100vh;
            color: var(--gray-800);
        }
        
        /* Modern Navbar */
        .navbar {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%) !important;
            box-shadow: 0 4px 20px rgba(30, 149, 224, 0.3);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
        }
        
        .nav-link {
            font-weight: 500;
            margin: 0 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }
        
        /* Modern Cards */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            background: white;
        }
        
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(30, 149, 224, 0.15);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            color: white;
            font-weight: 600;
            border: none;
            padding: 1.25rem 1.5rem;
        }
        
        .card-header.bg-success {
            background: linear-gradient(135deg, #059669 0%, var(--success) 100%) !important;
        }
        
        .card-header.bg-warning {
            background: linear-gradient(135deg, #D97706 0%, var(--warning) 100%) !important;
            color: white;
        }
        
        .card-header.bg-info {
            background: linear-gradient(135deg, #0891B2 0%, #06B6D4 100%) !important;
            color: white;
        }
        
        .card-header.bg-dark {
            background: linear-gradient(135deg, var(--gray-800) 0%, var(--gray-600) 100%) !important;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .card-footer {
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
            padding: 1rem 1.5rem;
        }
        
        /* Modern Buttons */
        .btn {
            border-radius: 10px;
            font-weight: 600;
            padding: 0.6rem 1.25rem;
            transition: all 0.3s ease;
            border: none;
            letter-spacing: -0.2px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            box-shadow: 0 4px 15px rgba(30, 149, 224, 0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 149, 224, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #059669 0%, var(--success) 100%);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, var(--success) 0%, #34D399 100%);
            transform: translateY(-2px);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #D97706 0%, var(--warning) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        }
        
        .btn-warning:hover {
            background: linear-gradient(135deg, var(--warning) 0%, #FBBF24 100%);
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #DC2626 0%, var(--danger) 100%);
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }
        
        .btn-danger:hover {
            background: linear-gradient(135deg, var(--danger) 0%, #F87171 100%);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, var(--gray-600) 0%, var(--gray-800) 100%);
        }
        
        .btn-info {
            background: linear-gradient(135deg, #0891B2 0%, #06B6D4 100%);
            color: white;
        }
        
        /* Badges */
        .badge {
            padding: 0.5em 0.8em;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        
        .bg-success { background: linear-gradient(135deg, #059669 0%, var(--success) 100%) !important; }
        .bg-warning { background: linear-gradient(135deg, #D97706 0%, var(--warning) 100%) !important; }
        .bg-danger { background: linear-gradient(135deg, #DC2626 0%, var(--danger) 100%) !important; }
        .bg-primary { background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%) !important; }
        .bg-info { background: linear-gradient(135deg, #0891B2 0%, #06B6D4 100%) !important; }
        .bg-secondary { background: linear-gradient(135deg, var(--gray-600) 0%, var(--gray-800) 100%) !important; }
        
        /* Tables */
        .table {
            border-radius: 12px;
            overflow: hidden;
        }
        
        .table thead {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            color: white;
        }
        
        .table thead th {
            border: none;
            font-weight: 600;
            padding: 1rem;
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            background: var(--primary-lighter);
            transform: scale(1.01);
        }
        
        /* Forms */
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid var(--gray-200);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(30, 149, 224, 0.1);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
        }
        
        /* Alerts */
        .alert {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .alert-success {
            background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%);
            color: #065F46;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%);
            color: #991B1B;
        }
        
        .alert-warning {
            background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
            color: #92400E;
        }
        
        .alert-info {
            background: linear-gradient(135deg, #D1FAE5 0%, var(--primary-lighter) 100%);
            color: var(--primary-dark);
        }
        
        /* Modern Typography */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        
        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--gray-800) 0%, var(--gray-600) 100%);
            color: white;
            margin-top: auto;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card {
            animation: fadeInUp 0.5s ease;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.2rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            h2 {
                font-size: 1.5rem;
            }
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--gray-100);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
    </style>
    
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-name" content="<?= csrf_token() ?>">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="bi bi-mortarboard-fill me-2"></i>Online Exam System
            </a>
            
            <?php if (session()->get('isLoggedIn')): ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <span class="nav-link text-light">
                            <i class="bi bi-person-circle me-1"></i> 
                            <?= esc(session()->get('name')) ?> 
                            <span class="badge bg-light text-primary ms-1"><?= ucfirst(session()->get('role')) ?></span>
                        </span>
                    </li>
                    
                    <?php if (session()->get('role') === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('admin') ?>">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('admin/exams') ?>">
                            <i class="bi bi-journal-text me-1"></i> Exams
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('admin/add-extra-time') ?>">
                            <i class="bi bi-clock-history me-1"></i> Extra Time
                        </a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('exam') ?>">
                            <i class="bi bi-list-check me-1"></i> My Exams
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <li class="nav-item ms-2">
                        <a class="nav-link text-danger" href="<?= base_url('logout') ?>">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </nav>

    <main class="py-5">
        <div class="container">
            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('warning')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashdata('warning') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if (isset($validation) && $validation->getErrors()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h6 class="mb-2"><i class="bi bi-exclamation-circle-fill me-2"></i>Please fix the following errors:</h6>
                <ul class="mb-0">
                    <?php foreach ($validation->getErrors() as $error): ?>
                    <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <footer class="py-4 mt-auto">
        <div class="container text-center">
            <p class="mb-0">
                <i class="bi bi-mortarboard-fill me-2"></i>&copy; <?= date('Y') ?> Online Exam System. All rights reserved.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>