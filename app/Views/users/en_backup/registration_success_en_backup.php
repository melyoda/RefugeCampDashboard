<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Submitted</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow border-0 p-4">
                <div class="text-success fs-1 mb-2">🎉</div>
                <h3 class="fw-bold text-success">Application Received Successfully</h3>
                <p class="text-muted small">Your profile has been submitted to the camp leadership team for verification and triage approval.</p>

                <div class="alert alert-dark font-monospace my-3 p-3">
                    <div class="mb-2">
                        <small class="text-muted d-block text-uppercase">Registration ID</small>
                        <strong class="text-white fs-5"><?= esc($id) ?></strong>
                    </div>
                    <hr class="border-secondary">
                    <div>
                        <small class="text-warning d-block text-uppercase">Your Secure Access Code</small>
                        <strong class="text-warning fs-3" style="letter-spacing: 1px;"><?= esc($code) ?></strong>
                    </div>
                </div>

                <div class="bg-light p-3 rounded border text-start mb-4 small text-danger fw-bold">
                    ⚠️ CRITICAL: Take a screenshot of this page or copy your Access Code right now. You will need both your Registration ID and Access Code to check your portal status later!
                </div>

                 <a href="<?= base_url('household/login') ?>" class="btn btn-success btn-lg w-100 fw-bold shadow-sm">Proceed to Login Portal</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>