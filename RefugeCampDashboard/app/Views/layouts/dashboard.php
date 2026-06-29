<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $this->renderSection('title') ?> - Refuge Camp Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; background-color: #f8f9fa; }
        .sidebar { width: 250px; background: #212529; color: white; }
        .main-content { flex: 1; display: flex; flex-direction: column; }
        .top-bar { background: white; border-bottom: 1px solid #dee2e6; padding: 15px; }
    </style>
</head>
<body>

    <div class="sidebar p-3 d-flex flex-column">
        <h4>Camp Dashboard</h4>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="<?= base_url('dashboard') ?>" class="nav-link text-white">Dashboard Home</a>
            </li>
            <li>
                <a href="<?= base_url('activities') ?>" class="nav-link text-white">Activities Log</a>
            </li>
            <li>
                <a href="<?= base_url('residents') ?>" class="nav-link text-white">Residents Database</a>
            </li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="<?= base_url('logout') ?>" class="btn btn-danger btn-sm w-100">Sign Out</a>
        </div>
    </div>

    <div class="main-content">
        <div class="top-bar d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $this->renderSection('page_title') ?></h5>
            <span class="badge bg-secondary">User: <?= auth()->user()->username ?></span>
        </div>

        <div class="container-fluid p-4">
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>