<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم ملف العائلة التعريفية</title>
    <!-- Loaded Bootstrap RTL for proper Right-to-Left alignment -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container">
        <span class="navbar-brand fw-bold">⛺ بوابة العائلة</span>
        <div class="d-flex align-items-center">
            <span class="text-light small ms-3 d-none d-sm-inline">مرحباً بك، <strong><?= esc($family_head['full_name']) ?></strong></span>
            <a href="<?= base_url('household/logout') ?>" class="btn btn-sm btn-outline-light">تسجيل الخروج</a>
        </div>
    </div>
</nav>

<div class="container py-4">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success shadow-sm py-2"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white fw-bold">ملف رب الأسرة</div>
                <div class="card-body small">
                    <div class="mb-2"><strong>رقم التسجيل:</strong> <span class="font-monospace fw-bold text-primary"><?= esc($family_head['document_id']) ?></span></div>
                    <div class="mb-2"><strong>الاسم الكامل:</strong> <?= esc($family_head['full_name']) ?></div>
                    <div class="mb-2"><strong>تاريخ الميلاد:</strong> <?= esc($family_head['dob']) ?></div>
                    <div class="mb-2"><strong>الهاتف الأساسي:</strong> <?= esc($family_head['primary_phone']) ?></div>
                    <div class="mb-2"><strong>الهاتف الاحتياطي:</strong> <?= esc($family_head['backup_phone'] ?: '—') ?></div>
                    <div class="mb-2"><strong>الحالة الاجتماعية:</strong> <?= esc($family_head['marital_status']) ?></div>
                    <hr>
                    <div class="mb-0">
                        <strong>الاحتياجات الطبية / تسهيل الوصول:</strong><br>
                        <?php if ($family_head['has_disability']): ?>
                            <span class="badge bg-danger mt-1">بحاجة إلى مساعدة خاصة</span>
                            <p class="text-muted mt-1 mb-0 bg-light p-2 rounded border"><?= esc($family_head['disability_details']) ?></p>
                        <?php else: ?>
                            <span class="text-muted">لم يتم الإفصاح عن احتياجات خاصة.</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                    <span>سجل أفراد العائلة (الزوج/الزوجة والأبناء)</span>
                    <button type="button" class="btn btn-sm btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#addMemberModal">+ إضافة فرد للعائلة</button>
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
                                        <span class="badge <?= $dep['relationship_type'] === 'Spouse' ? 'bg-info text-dark' : 'bg-secondary' ?>">
                                            <?= $dep['relationship_type'] === 'Spouse' ? 'زوج/زوجة' : 'ابن/ابنة' ?>
                                        </span>
                                    </td>
                                    <td class="fw-bold"><?= esc($dep['full_name']) ?></td>
                                    <td><?= $dep['gender'] === 'Female' ? 'أنثى' : 'ذكر' ?></td>
                                    <td><?= esc($dep['dob']) ?></td>
                                    <td>
                                        <?php if ($dep['has_disability']): ?>
                                            <span class="text-danger border border-danger rounded px-1 d-inline-block small" title="<?= esc($dep['disability_details']) ?>">بحاجة لدعم خاص</span>
                                        <?php else: ?>
                                            <span class="text-muted">طبيعي</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <form action="<?= base_url('household/remove-member/' . $dep['id']) ?>" method="POST" onsubmit="return confirm('هل أنت متأكد من إزالة هذا الفرد من سجل العائلة النشط؟');" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">إزالة</button>
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

            <div class="w-100 mt-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                        <span>سجل الاستلام والمساعدات الخاص بك</span>
                        <span class="badge bg-secondary font-monospace small">سجل المساعدات</span>
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
                                                <span class="badge bg-success py-0 px-2 mt-1" style="font-size: 0.75rem;">مساعدات موزعة</span>
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
</div>

<div class="modal fade" id="addMemberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">إضافة تابع للأسرة</h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('household/add-member') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body row g-3">
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
                        <div class="form-check form-switch my-2 p-0 pe-5">
                            <input class="form-check-input float-end me-0 ms-2" type="checkbox" name="has_disability" value="1" id="depDisability" onchange="document.getElementById('depDisabilityDetails').classList.toggle('d-none', !this.checked)">
                            <label class="form-check-label small me-4" for="depDisability">هذا الشخص يحتاج إلى رعاية طبية خاصة أو دعم لتسهيل الحركة والوصول</label>
                        </div>
                        <textarea name="disability_details" id="depDisabilityDetails" rows="2" class="form-control form-control-sm d-none" placeholder="يرجى كتابة التفاصيل الصحية..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-sm btn-success px-3 fw-bold">إضافة الفرد</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>