<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول بوابة السكان</title>
    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
            color: #334155;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        }
        .form-control {
            border-color: #cbd5e1;
            border-radius: 0.5rem;
            padding: 0.6rem 0.85rem;
        }
        .form-control:focus {
            border-color: #14b8a6;
            box-shadow: 0 0 0 0.25rem rgba(20, 184, 166, 0.15);
        }
        .btn-warm {
            background-color: #0f766e;
            color: #ffffff;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
        }
        .btn-warm:hover {
            background-color: #115e59;
            color: #ffffff;
        }
        .link-warm {
            color: #0f766e;
            transition: color 0.2s ease;
        }
        .link-warm:hover {
            color: #115e59;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <!-- Header Section -->
            <div class="text-center mb-4">
                <h2 class="fw-bold text-dark mb-2">⛺ بوابة السكان</h2>
                <p class="text-muted small mx-auto" style="max-width: 380px;">
                    أدخل بيانات تسجيل العائلة الخاصة بك للوصول الآمن إلى لوحة تحكم ملف العائلة.
                </p>
            </div>

            <!-- Card -->
            <div class="card login-card p-4 p-md-5">
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 small py-2 mb-3">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/auth') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">رقم التسجيل</label>
                        <input type="text" name="document_id" class="form-control" placeholder="مثال: UN-492-XP" value="<?= old('document_id') ?>" required autocomplete="off">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">رمز الدخول الآمن</label>
                        <input type="password" name="access_code" class="form-control font-monospace" placeholder="XXX-XXX" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warm fw-bold py-2 shadow-sm">الوصول إلى ملف العائلة</button>
                    </div>
                </form>
            </div>

            <!-- Footer link -->
            <div class="text-center mt-4">
                <a href="<?= base_url('household/household-register') ?>" class="small link-warm text-decoration-none fw-bold">← هل تريد تسجيل عائلة جديدة؟</a>
            </div>

        </div>
    </div>
</div>
</body>
</html>