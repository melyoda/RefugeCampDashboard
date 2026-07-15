<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم تقديم طلب التسجيل</title>
    <!-- Loaded Bootstrap RTL for proper Right-to-Left alignment -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow border-0 p-4">
                <div class="text-success fs-1 mb-2">🎉</div>
                <h3 class="fw-bold text-success">تم استلام طلبكم بنجاح</h3>
                <p class="text-muted small">تم تقديم ملفكم العائلي إلى إدارة المخيم للمراجعة والتحقق واعتماد التفعيل.</p>

                <div class="alert alert-dark font-monospace my-3 p-3">
                    <div class="mb-2">
                        <small class="text-muted d-block text-uppercase">رقم التسجيل (Registration ID)</small>
                        <strong class="text-white fs-5"><?= esc($id) ?></strong>
                    </div>
                    <hr class="border-secondary">
                    <div>
                        <small class="text-warning d-block text-uppercase">رمز الدخول الآمن الخاص بك (Access Code)</small>
                        <strong class="text-warning fs-3" style="letter-spacing: 1px;"><?= esc($code) ?></strong>
                    </div>
                </div>

                <div class="bg-light p-3 rounded border text-start mb-4 small text-danger fw-bold">
                    ⚠️ هام للغاية: يرجى التقاط لقطة شاشة (Screenshot) لهذه الصفحة أو نسخ رمز الدخول الآن. ستحتاج إلى كل من رقم التسجيل ورمز الدخول لمتابعة حالة طلبك في البوابة لاحقاً!
                </div>

                 <a href="<?= base_url('household/login') ?>" class="btn btn-success btn-lg w-100 fw-bold shadow-sm">الانتقال إلى بوابة تسجيل الدخول</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>