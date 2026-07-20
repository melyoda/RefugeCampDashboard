<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوابة المخيم - التسجيل الذاتي</title>
    <!-- Loaded Bootstrap RTL for proper Right-to-Left alignment -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="text-center mb-4">
                <h1 class="h3 fw-bold text-dark">تسجيل عائلات المخيم</h1>
                <p class="text-muted small">يرجى ملء بيانات عائلتكم بدقة. سيتم معالجة طلبكم وفحصه من قبل إدارة المخيم قبل تفعيل الحساب في النظام.</p>
            </div>

            <?php if (session()->getFlashdata('errors')) : ?>
                <div class="alert alert-danger shadow-sm">
                    <ul class="mb-0 small">
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('household/household-register/save') ?>" method="POST" id="registrationForm">
                <?= csrf_field() ?>

                <!-- 1. Head of Family Details / تفاصيل رب الأسرة -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white fw-bold py-3">١. تفاصيل رب الأسرة</div>
                    <div class="card-body row g-3 bg-white">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">الاسم الأول <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control form-control-sm" value="<?= old('first_name') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">اسم الأب <span class="text-danger">*</span></label>
                            <input type="text" name="father_name" class="form-control form-control-sm" value="<?= old('father_name') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">اسم الجد <span class="text-danger">*</span></label>
                            <input type="text" name="grandfather_name" class="form-control form-control-sm" value="<?= old('grandfather_name') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">اسم العائلة <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control form-control-sm" value="<?= old('last_name') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">رقم التسجيل / وثيقة الهوية <span class="text-danger">*</span></label>
                            <input type="text" name="document_id" class="form-control form-control-sm" value="<?= old('document_id') ?>" placeholder="الهوية أو رقم جواز السفر" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">تاريخ الميلاد <span class="text-danger">*</span></label>
                            <input type="date" name="dob" class="form-control form-control-sm" value="<?= old('dob') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">رقم الهاتف الأساسي <span class="text-danger">*</span></label>
                            <input type="text" name="primary_phone" class="form-control form-control-sm" value="<?= old('primary_phone') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">رقم هاتف احتياطي ثانٍ <span class="text-muted">(اختياري)</span></label>
                            <input type="text" name="backup_phone" class="form-control form-control-sm" value="<?= old('backup_phone') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">الحالة الاجتماعية <span class="text-danger">*</span></label>
                            <select name="marital_status" class="form-select form-select-sm" required>
                                <option value="">اختر...</option>
                                <option value="Single" <?= old('marital_status') === 'Single' ? 'selected' : '' ?>>أعزب / عزباء</option>
                                <option value="Married" <?= old('marital_status') === 'Married' ? 'selected' : '' ?>>متزوج / متزوجة</option>
                                <option value="Widowed" <?= old('marital_status') === 'Widowed' ? 'selected' : '' ?>>أرمل / أرملة</option>
                                <option value="Divorced" <?= old('marital_status') === 'Divorced' ? 'selected' : '' ?>>مطلق / مطلقة</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch my-2">
                                <input class="form-check-input" type="checkbox" name="has_disability" value="1" id="headDisability" <?= old('has_disability') ? 'checked' : '' ?> onchange="document.getElementById('headDisabilityDetails').classList.toggle('d-none', !this.checked)">
                                <label class="form-check-label small fw-bold" for="headDisability">رب الأسرة يعاني من إعاقة / يحتاج إلى رعاية طبية متخصصة</label>
                            </div>
                            <textarea name="disability_details" id="headDisabilityDetails" rows="2" class="form-control form-control-sm <?= old('has_disability') ? '' : 'd-none' ?>" placeholder="يرجى كتابة تفاصيل الإعاقة أو الاحتياجات الطبية المطلوبة للرعاية المتابعة..."><?= old('disability_details') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. Household Spouses & Children / الزوجات والأبناء -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-secondary text-white fw-bold d-flex justify-content-between align-items-center py-2">
                        <span>٢. أفراد العائلة (الزوج/الزوجة والأبناء)</span>
                        <div>
                            <button type="button" class="btn btn-sm btn-light fw-bold me-1 px-3" onclick="addFamilyMember('Spouse', 'زوج/زوجة')">+ إضافة زوج/زوجة</button>
                            <button type="button" class="btn btn-sm btn-light fw-bold px-3" onclick="addFamilyMember('Child', 'ابن/ابنة')">+ إضافة ابن/ابنة</button>
                        </div>
                    </div>
                    <div class="card-body p-0 bg-white">
                        <ul class="list-group list-group-flush" id="familyMembersContainer">
                            <li class="list-group-item text-center text-muted py-4 <?= old('members') ? 'd-none' : '' ?>" id="emptyRowNotice">
                                لم يتم إضافة زوجة أو أبناء بعد. اضغط على الأزرار أعلاه للمتابعة إذا كان ذلك ينطبق على عائلتك.
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="d-grid shadow-sm">
                    <button type="submit" class="btn btn-dark btn-lg fw-bold">إرسال طلب التسجيل الآمن</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
let memberIndex = 0;

function addFamilyMember(type, arabicLabel, initialData = {}) {
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

    // Dynamic field label based on whether it's a Spouse or Child
    const nameLabel = type === 'Spouse' ? 'الاسم الرباعي الكامل' : 'الاسم الأول';
    const namePlaceholder = type === 'Spouse' ? 'اسم الزوج/الزوجة الكامل' : 'اسم الطفل فقط';

    const html = `
        <li class="list-group-item bg-white p-3 border-bottom position-relative member-item">
            <div class="row g-2 align-items-center">
                <div class="col-12 mb-1 d-flex justify-content-between align-items-center">
                    <span class="badge ${type === 'Spouse' ? 'bg-info text-dark' : 'bg-primary'} fw-bold">${arabicLabel}</span>
                    <button type="button" class="btn-close ms-0 me-auto" onclick="this.closest('.member-item').remove()"></button>
                </div>
                <input type="hidden" name="members[${memberIndex}][relationship_type]" value="${type}">

                <div class="col-md-3">
                    <label class="form-label small mb-1 fw-bold">${nameLabel} <span class="text-danger">*</span></label>
                    <input type="text" name="members[${memberIndex}][name_input]" class="form-control form-control-sm" value="${firstName}" placeholder="${namePlaceholder}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1 fw-bold">وثيقة الهوية / ID <span class="text-danger">*</span></label>
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
                    <input type="text" name="members[${memberIndex}][disability_details]" class="form-control form-control-sm" value="${disabilityDetails}" placeholder="حدد تفاصيل الاحتياجات الصحية أو الطبية المحددة...">
                </div>
            </div>
        </li>
    `;

    container.insertAdjacentHTML('beforeend', html);
    memberIndex++;
    container.insertAdjacentHTML('beforeend', html);
    memberIndex++;
}

// Restore dynamic family members if validation fails and page redirects back with old input
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