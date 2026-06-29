
<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('title') ?>
Dashboard Home
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Camp Overview
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12 mb-4">
        <div class="card bg-primary text-white shadow-sm">
            <div class="card-body p-4">
                <h3>Welcome Back, <?= auth()->user()->username ?>!</h3>
                <p class="mb-0">You are securely logged into the single-camp operational dashboard environment.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card border-start border-success border-4 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small">Active Residents</h6>
                <h2 class="fw-bold mb-0">--</h2>
                <small class="text-muted">Managed via Phase 4</small>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card border-start border-warning border-4 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small">Total Activities logged</h6>
                <h2 class="fw-bold mb-0">--</h2>
                <small class="text-muted">Managed via Phase 2</small>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>