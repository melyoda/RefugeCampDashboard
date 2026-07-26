<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم ملف العائلة التعريفية</title>
    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
            color: #334155;
        }
        .navbar-warm {
            background-color: #0f766e;
        }
        .dash-card {
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        .card-header-warm {
            background-color: #0f766e;
            color: #ffffff;
        }
        .card-header-slate {
            background-color: #334155;
            color: #ffffff;
        }
        .form-control, .form-select {
            border-color: #cbd5e1;
            border-radius: 0.5rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #14b8a6;
            box-shadow: 0 0 0 0.25rem rgba(20, 184, 166, 0.15);
        }
        .btn-warm {
            background-color: #0f766e;
            color: #ffffff;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        .btn-warm:hover {
            background-color: #115e59;
            color: #ffffff;
        }
        .btn-outline-warm {
            border-color: #cbd5e1;
            color: #0f766e;
            border-radius: 0.5rem;
        }
        .btn-outline-warm:hover {
            background-color: #f0fdfa;
            color: #0f766e;
        }
        .badge-spouse {
            background-color: #e0f2fe;
            color: #0369a1;
        }
        .badge-child {
            background-color: #f0fdf4;
            color: #15803d;
        }
        .badge-need {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark navbar-warm shadow-sm mb-4">
    <div class="container">
        <span class="navbar-brand fw-bold fs-5">⛺ بوابة العائلة</span>
        <div class="d-flex align-items-center">
            <span class="text-light small ms-3 d-none d-sm-inline">مرحباً بك، <strong><?= esc($family_head['full_name']) ?></strong></span>
            <a href="<?= base_url('household/logout') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3">تسجيل الخروج</a>
        </div>
    </div>
</nav>

<div class="container py-2">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm rounded-3 py-2 mb-4"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Head of Family Card -->
        <div class="col-lg-4">
            <div class="card dash-card mb-4">
                <div class="card-header card-header-warm fw-bold py-3 px-4">
                    ملف رب الأسرة
                </div>
                <div class="card-body p-4 small">
                    <div class="mb-2"><strong>رقم التسجيل:</strong> <span class="font-monospace fw-bold text-teal" style="color: #0f766e;"><?= esc($family_head['document_id']) ?></span></div>
                    <div class="mb-2"><strong>الاسم الكامل:</strong> <?= esc($family_head['full_name']) ?></div>
                    <div class="mb-2"><strong>تاريخ الميلاد:</strong> <?= esc($family_head['dob']) ?></div>
                    <div class="mb-2"><strong>الهاتف الأساسي:</strong> <?= esc($family_head['primary_phone']) ?></div>
                    <div class="mb-2"><strong>الهاتف الاحتياطي:</strong> <?= esc($family_head['backup_phone'] ?: '—') ?></div>
                    <div class="mb-2"><strong>الحالة الاجتماعية:</strong> <?= esc($family_head['marital_status']) ?></div>
                    <hr class="my-3 text-muted opacity-25">
                    <div class="mb-0">
                        <strong>الاحتياجات الطبية / تسهيل الوصول:</strong><br>
                        <?php if ($family_head['has_disability']): ?>
                            <span class="badge badge-need mt-2 px-2 py-1">بحاجة إلى مساعدة خاصة</span>
                            <p class="text-muted mt-2 mb-0 p-2 rounded-3 border" style="background-color: #f8fafc;"><?= esc($family_head['disability_details']) ?></p>
                        <?php else: ?>
                            <span class="text-muted d-block mt-1">لم يتم الإفصاح عن احتياجات خاصة.</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Family Members & Assistance History -->
        <div class="col-lg-8">

            <!-- Family Members Table -->
            <div class="card dash-card mb-4">
                <div class="card-header card-header-slate fw-bold py-3 px-4 d-flex justify-content-between align-items-center">
                    <span>سجل أفراد العائلة (الزوج/الزوجة والأبناء)</span>
                    <button type="button" class="btn btn-sm btn-outline-light fw-bold rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#addMemberModal">+ إضافة فرد</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>صلة القرابة</th>
                                <th>الاسم الكامل</th>
                                <th>الجنس</th>
                                <th>تاريخ الميلاد</th>
                                <th>الحالة الصحية</th>
                                <th class="text-end">الإجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($dependents)): foreach ($dependents as $dep): ?>
                                <tr>
                                    <td>
                                        <span class="badge <?= $dep['relationship_type'] === 'Spouse' ? 'badge-spouse' : 'badge-child' ?> rounded-pill px-2 py-1">
                                            <?= $dep['relationship_type'] === 'Spouse' ? 'زوج/زوجة' : 'ابن/ابنة' ?>
                                        </span>
                                    </td>
                                    <td class="fw-bold"><?= esc($dep['full_name']) ?></td>
                                    <td><?= $dep['gender'] === 'Female' ? 'أنثى' : 'ذكر' ?></td>
                                    <td><?= esc($dep['dob']) ?></td>
                                    <td>
                                        <?php if ($dep['has_disability']): ?>
                                            <span class="badge badge-need" title="<?= esc($dep['disability_details']) ?>">بحاجة لدعم خاص</span>
                                        <?php else: ?>
                                            <span class="text-muted">طبيعي</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <form action="<?= base_url('household/remove-member/' . $dep['id']) ?>" method="POST" onsubmit="return confirm('هل أنت متأكد من إزالة هذا الفرد من سجل العائلة النشط؟');" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2 rounded-2">إزالة</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">لا يوجد أفراد عائلة إضافيين مسجلين في هذا الملف حالياً.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Assistance Distribution History -->
            <div class="card dash-card">
                <div class="card-header card-header-slate fw-bold py-3 px-4 d-flex justify-content-between align-items-center">
                    <span>سجل الاستلام والمساعدات الخاص بك</span>
                    <span class="badge bg-light text-dark font-monospace small border">سجل المساعدات</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>تاريخ الاستلام</th>
                                <th>المادة / النشاط</th>
                                <th>الفئة</th>
                                <th>التفاصيل</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($history)): foreach ($history as $log): ?>
                                <tr>
                                    <td class="text-nowrap"><?= date('Y-m-d H:i', strtotime($log['created_at'])) ?></td>
                                    <td>
                                        <span class="fw-bold d-block"><?= esc($log['title']) ?></span>
                                        <?php if ($log['is_distributed_aid'] == 1): ?>
                                            <span class="badge badge-child py-0 px-2 mt-1" style="font-size: 0.725rem;">مساعدات موزعة</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="text-capitalize badge bg-light text-dark border"><?= esc($log['aid_category'] ?? 'عام') ?></span>
                                    </td>
                                    <td class="text-muted"><?= esc($log['description'] ?: 'لم يتم تقديم تفاصيل.') ?></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">لا توجد سجلات توزيع مساعدات أو أنشطة مسجلة لملفك الشخصي بعد.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Add Member Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header card-header-warm py-3 px-4 rounded-top-4">
                <h5 class="modal-title fw-bold fs-6">إضافة تابع للأسرة</h5>
                <button type="button" class="btn-close btn-close-white m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('household/add-member') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4 row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">صلة القرابة</label>
                        <select name="relationship_type" class="form-select form-select-sm" required>
                            <option value="Spouse">زوج / زوجة</option>
                            <option value="Child">ابن / ابنة</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">الجنس</label>
                        <select name="gender" class="form-select form-select-sm" required>
                            <option value="Female">أنثى</option>
                            <option value="Male">ذكر</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">الاسم الكامل</label>
                        <input type="text" name="full_name" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">تاريخ الميلاد</label>
                        <input type="date" name="dob" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded-3" style="background-color: #f8fafc; border: 1px dashed #cbd5e1;">
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" name="has_disability" value="1" id="depDisability" onchange="document.getElementById('depDisabilityDetails').classList.toggle('d-none', !this.checked)">
                                <label class="form-check-label small fw-bold" for="depDisability">يعاني من إعاقة أو يحتاج لرعاية صحية خاصة</label>
                            </div>
                            <textarea name="disability_details" id="depDisabilityDetails" rows="2" class="form-control form-control-sm mt-2 d-none" placeholder="ويرجى كتابة التفاصيل الصحية هنا..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 rounded-bottom-4 px-4">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 px-3" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-sm btn-warm rounded-3 px-4 fw-bold">حفظ الفرد</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>