<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوابة المخيم - التسجيل الذاتي</title>
    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
            color: #334155;
        }
        .form-card {
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        }
        .card-header-warm {
            background-color: #0f766e; /* Soft Deep Teal */
            color: #ffffff;
            border-top-left-radius: 1rem !important;
            border-top-right-radius: 1rem !important;
        }
        .card-header-subtle {
            background-color: #334155; /* Soft Slate Gray */
            color: #ffffff;
            border-top-left-radius: 1rem !important;
            border-top-right-radius: 1rem !important;
        }
        .form-control, .form-select {
            border-color: #cbd5e1;
            border-radius: 0.5rem;
            padding: 0.45rem 0.75rem;
        }
        .form-control:focus, .form-select:focus {
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
        .btn-outline-warm {
            border-color: #e2e8f0;
            background-color: #ffffff;
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
        .member-item {
            border-radius: 0.75rem !important;
            border: 1px solid #f1f5f9 !important;
            background-color: #fafafa;
            margin-bottom: 0.75rem;
        }
    </style>
</head>
<body class="py-4 py-md-5">

<div class="container py-2">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <!-- Header Section -->
            <div class="text-center mb-4">
                <h1 class="h3 fw-bold text-dark mb-2">تسجيل عائلات المخيم</h1>
                <p class="text-muted small mx-auto" style="max-width: 600px;">
                    أهلاً بكم. يرجى تزويدنا ببيانات عائلتكم بدقة لتسهيل عملية المتابعة وتقديم الدعم المناسب من قبل إدارة المخيم.
                </p>
            </div>

            <?php if (session()->getFlashdata('errors')) : ?>
                <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                    <div class="fw-bold mb-1 small">يرجى تصحيح الأخطاء التالية:</div>
                    <ul class="mb-0 small ps-3">
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('household/household-register/save') ?>" method="POST" id="registrationForm">
                <?= csrf_field() ?>

                <!-- 1. Head of Family Details -->
                <div class="card form-card mb-4">
                    <div class="card-header card-header-warm fw-bold py-3 px-4 d-flex align-items-center">
                        <span class="fs-5 me-2">1.</span> بيانات رب الأسرة
                    </div>
                    <div class="card-body p-4 row g-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">الاسم الأول <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control form-control-sm" value="<?= old('first_name') ?>" placeholder="الاسم الشخصي" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">اسم الأب <span class="text-danger">*</span></label>
                            <input type="text" name="father_name" class="form-control form-control-sm" value="<?= old('father_name') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">اسم الجد <span class="text-danger">*</span></label>
                            <input type="text" name="grandfather_name" class="form-control form-control-sm" value="<?= old('grandfather_name') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">اسم العائلة <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control form-control-sm" value="<?= old('last_name') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">رقم الهوية / الوثيقة <span class="text-danger">*</span></label>
                            <input type="text" name="document_id" class="form-control form-control-sm" value="<?= old('document_id') ?>" placeholder="رقم الهوية أو جواز السفر" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">تاريخ الميلاد <span class="text-danger">*</span></label>
                            <input type="date" name="dob" class="form-control form-control-sm" value="<?= old('dob') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">رقم الهاتف الأساسي <span class="text-danger">*</span></label>
                            <input type="text" name="primary_phone" class="form-control form-control-sm" value="<?= old('primary_phone') ?>" placeholder="05XXXXXXXX" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">رقم هاتف إضافي <span class="text-muted fw-normal">(اختياري)</span></label>
                            <input type="text" name="backup_phone" class="form-control form-control-sm" value="<?= old('backup_phone') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">الحالة الاجتماعية <span class="text-danger">*</span></label>
                            <select name="marital_status" class="form-select form-select-sm" required>
                                <option value="">اختر الحالة...</option>
                                <option value="Single" <?= old('marital_status') === 'Single' ? 'selected' : '' ?>>أعزب / عزباء</option>
                                <option value="Married" <?= old('marital_status') === 'Married' ? 'selected' : '' ?>>متزوج / متزوجة</option>
                                <option value="Widowed" <?= old('marital_status') === 'Widowed' ? 'selected' : '' ?>>أرمل / أرملة</option>
                                <option value="Divorced" <?= old('marital_status') === 'Divorced' ? 'selected' : '' ?>>مطلق / مطلقة</option>
                            </select>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="p-3 rounded-3" style="background-color: #f8fafc; border: 1px dashed #cbd5e1;">
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input" type="checkbox" name="has_disability" value="1" id="headDisability" <?= old('has_disability') ? 'checked' : '' ?> onchange="document.getElementById('headDisabilityDetails').classList.toggle('d-none', !this.checked)">
                                    <label class="form-check-label small fw-bold" for="headDisability">رب الأسرة يعاني من إعاقة أو يحتاج لرعاية صحية خاصة</label>
                                </div>
                                <textarea name="disability_details" id="headDisabilityDetails" rows="2" class="form-control form-control-sm mt-2 <?= old('has_disability') ? '' : 'd-none' ?>" placeholder="يرجى توضيح طبيعة الاحتياج الصحي أو الإعاقة لتوفير الدعم المناسب..."><?= old('disability_details') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Household Spouses & Children -->
                <div class="card form-card mb-4">
                    <div class="card-header card-header-subtle fw-bold py-3 px-4 d-flex justify-content-between align-items-center">
                        <div><span class="fs-5 me-2">2.</span> أفراد العائلة المضافين</div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-warm fw-bold px-3" onclick="addFamilyMember('Spouse', 'زوج/زوجة')">+ إضافة زوج/زوجة</button>
                            <button type="button" class="btn btn-sm btn-outline-warm fw-bold px-3" onclick="addFamilyMember('Child', 'ابن/ابنة')">+ إضافة ابن/ابنة</button>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div id="familyMembersContainer">
                            <div class="text-center text-muted py-4 px-3 rounded-3 <?= old('members') ? 'd-none' : '' ?>" id="emptyRowNotice" style="background-color: #f8fafc; border: 1px dashed #cbd5e1;">
                                <p class="mb-0 small">لم يتم إضافة أفراد حتى الآن. استخدم الأزرار أعلاه لإضافة الزوجة أو الأبناء.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-warm btn-lg fw-bold py-3 shadow-sm">حفظ وإرسال الطلب</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
let memberIndex = 0;

function addFamilyMember(type, arabicLabel, initialData = {}) {
    if (type === 'Child') {
        const container = document.getElementById('familsyMembersContainer');
        const currentChildrenCount = container.querySelectorAll('input[value="Child"]').length;

        if (currentChildrenCount >= 15) {
            alert('عذراً، الحد الأقصى المسموح به حالياً لإضافة الأبناء هو 15 لطفل.');
            return;
        }
    }
        if (type === 'Spouse') {
        const container = document.getElementById('familyMembersContainer');
        const currentSpousesCount = container.querySelectorAll('input[value="Spouse"]').length;

        if (currentSpousesCount >= 4) {
            alert('عذراً، الحد الأقصى المسموح به حالياً لإضافة الزوجات هو 4.');
            return;
        }
    }

    const notice = document.getElementById('emptyRowNotice');
    if (notice) notice.classList.add('d-none');

    const container = document.getElementById('familyMembersContainer');

    const firstName = initialData.first_name || initialData.full_name || '';
    const documentId = initialData.document_id || '';
    const dob = initialData.dob || '';
    const gender = initialData.gender || '';
    const hasDisability = initialData.has_disability ? 'checked' : '';
    const disabilityDetails = initialData.disability_details || '';
    const detailsDisplayClass = initialData.has_disability ? '' : 'd-none';

    const nameLabel = type === 'Spouse' ? 'الاسم الرباعي الكامل' : 'الاسم الأول';
    const namePlaceholder = type === 'Spouse' ? 'اسم الزوج/الزوجة' : 'اسم الطفل فقط';
    const badgeClass = type === 'Spouse' ? 'badge-spouse' : 'badge-child';

    const html = `
        <div class="member-item p-3 position-relative">
            <div class="row g-2 align-items-center">
                <div class="col-12 mb-2 d-flex justify-content-between align-items-center">
                    <span class="badge ${badgeClass} fw-bold px-3 py-2 rounded-pill">${arabicLabel}</span>
                    <button type="button" class="btn-close ms-0 me-auto small" style="font-size: 0.8rem;" onclick="removeFamilyMember(this)"></button>
                </div>
                <input type="hidden" name="members[${memberIndex}][relationship_type]" value="${type}">

                <div class="col-md-3">
                    <label class="form-label small mb-1 fw-bold">${nameLabel} <span class="text-danger">*</span></label>
                    <input type="text" name="members[${memberIndex}][name_input]" class="form-control form-control-sm" value="${firstName}" placeholder="${namePlaceholder}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1 fw-bold">وثيقة الهوية <span class="text-danger">*</span></label>
                    <input type="text" name="members[${memberIndex}][document_id]" class="form-control form-control-sm" value="${documentId}" placeholder="رقم الهوية" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1 fw-bold">تاريخ الميلاد <span class="text-danger">*</span></label>
                    <input type="date" name="members[${memberIndex}][dob]" class="form-control form-control-sm" value="${dob}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1 fw-bold">الجنس <span class="text-danger">*</span></label>
                    <select name="members[${memberIndex}][gender]" class="form-select form-select-sm" required>
                        <option value="">الجنس</option>
                        <option value="Male" ${gender === 'Male' ? 'selected' : ''}>ذكر</option>
                        <option value="Female" ${gender === 'Female' ? 'selected' : ''}>أنثى</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" name="members[${memberIndex}][has_disability]" value="1" id="dis_${memberIndex}" ${hasDisability} onchange="document.getElementById('details_${memberIndex}').classList.toggle('d-none', !this.checked)">
                        <label class="form-check-label small" for="dis_${memberIndex}">إعاقة؟</label>
                    </div>
                </div>
                <div class="col-12 ${detailsDisplayClass}" id="details_${memberIndex}">
                    <input type="text" name="members[${memberIndex}][disability_details]" class="form-control form-control-sm mt-1" value="${disabilityDetails}" placeholder="تفاصيل الإعاقة أو الاحتياج الطبي...">
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
    memberIndex++;
}

function removeFamilyMember(btn) {
    btn.closest('.member-item').remove();
    const container = document.getElementById('familyMembersContainer');
    const remainingMembers = container.querySelectorAll('.member-item');
    if (remainingMembers.length === 0) {
        document.getElementById('emptyRowNotice').classList.remove('d-none');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    <?php if (old('members')) : ?>
        <?php foreach (old('members') as $member) : ?>
            addFamilyMember(
                '<?= esc($member['relationship_type'] ?? 'Child') ?>',
                '<?= ($member['relationship_type'] ?? '') === 'Spouse' ? 'زوج/زوجة' : 'ابن/ابنة' ?>',
                <?= json_encode($member) ?>
            );
        <?php endforeach; ?>
    <?php endif; ?>
});
</script>

</body>
</html>