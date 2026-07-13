<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Portal Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="text-center mb-4">
                <h2 class="fw-bold text-dark">⛺ Resident Portal</h2>
                <p class="text-muted small">Enter your family registration credentials to access your secure household profile dashboard.</p>
            </div>

            <div class="card shadow border-0 p-4">
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger small py-2"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('household/auth') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase">Registration ID</label>
                        <input type="text" name="document_id" class="form-control" placeholder="e.g., UN-492-XP" value="<?= old('document_id') ?>" required autocomplete="off">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-uppercase">Secure Access Code</label>
                        <input type="password" name="access_code" class="form-control font-monospace" placeholder="XXX-XXX" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-dark fw-bold">Access Family Profile</button>
                    </div>
                </form>
            </div>

            <div class="text-center mt-3">
                <a href="<?= base_url('household-register') ?>" class="small text-muted text-decoration-none">← Need to register a new household?</a>
            </div>

        </div>
    </div>
</div>
</body>
</html>