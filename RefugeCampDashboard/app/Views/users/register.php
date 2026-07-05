<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camp Portal - Self Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="text-center mb-4">
                <h1 class="h3 fw-bold text-dark">Camp Household Registration</h1>
                <p class="text-muted small">Please fill in your family records accurately. Your application will be processed by camp administration triage before system activation.</p>
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

            <form action="<?= base_url('household-register/save') ?>" method="POST" id="registrationForm">
                <?= csrf_field() ?>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white fw-bold py-3">1. Head of Family Details</div>
                    <div class="card-body row g-3 bg-white">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control form-control-sm" value="<?= old('first_name') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control form-control-sm" value="<?= old('last_name') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Registration / Document ID <span class="text-danger">*</span></label>
                            <input type="text" name="document_id" class="form-control form-control-sm" value="<?= old('document_id') ?>" placeholder="e.g., National ID or Passport Number" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="dob" class="form-control form-control-sm" value="<?= old('dob') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Primary Phone <span class="text-danger">*</span></label>
                            <input type="text" name="primary_phone" class="form-control form-control-sm" value="<?= old('primary_phone') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Secondary Backup Phone <span class="text-muted">(Optional)</span></label>
                            <input type="text" name="backup_phone" class="form-control form-control-sm" value="<?= old('backup_phone') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Marital Status <span class="text-danger">*</span></label>
                            <select name="marital_status" class="form-select form-select-sm" required>
                                <option value="">Select...</option>
                                <option value="Single" <?= old('marital_status') === 'Single' ? 'selected' : '' ?>>Single</option>
                                <option value="Married" <?= old('marital_status') === 'Married' ? 'selected' : '' ?>>Married</option>
                                <option value="Widowed" <?= old('marital_status') === 'Widowed' ? 'selected' : '' ?>>Widowed</option>
                                <option value="Divorced" <?= old('marital_status') === 'Divorced' ? 'selected' : '' ?>>Divorced</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch my-2">
                                <input class="form-check-input" type="checkbox" name="has_disability" value="1" id="headDisability" <?= old('has_disability') ? 'checked' : '' ?> onchange="document.getElementById('headDisabilityDetails').classList.toggle('d-none', !this.checked)">
                                <label class="form-check-label small fw-bold" for="headDisability">Head of family has a disability / requires specialized medical care</label>
                            </div>
                            <textarea name="disability_details" id="headDisabilityDetails" rows="2" class="form-control form-control-sm <?= old('has_disability') ? '' : 'd-none' ?>" placeholder="Provide accessibility or medical specification tracking notes..."><?= old('disability_details') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-secondary text-white fw-bold d-flex justify-content-between align-items-center py-2">
                        <span>2. Household Spouses & Children</span>
                        <div>
                            <button type="button" class="btn btn-sm btn-light fw-bold me-1 px-3" onclick="addFamilyMember('Spouse')">+ Add Spouse</button>
                            <button type="button" class="btn btn-sm btn-light fw-bold px-3" onclick="addFamilyMember('Child')">+ Add Child</button>
                        </div>
                    </div>
                    <div class="card-body p-0 bg-white">
                        <ul class="list-group list-group-flush" id="familyMembersContainer">
                            <li class="list-group-item text-center text-muted py-4" id="emptyRowNotice">
                                No spouses or children added yet. Click the buttons above if applicable.
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="d-grid shadow-sm">
                    <button type="submit" class="btn btn-dark btn-lg fw-bold">Submit Secure Registration</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
let memberIndex = 0;

function addFamilyMember(type) {
    const notice = document.getElementById('emptyRowNotice');
    if (notice) notice.remove();

    const container = document.getElementById('familyMembersContainer');

    const html = `
        <li class="list-group-item bg-white p-3 border-bottom position-relative member-item">
            <div class="row g-2 align-items-center">
                <div class="col-12 mb-1 d-flex justify-content-between align-items-center">
                    <span class="badge ${type === 'Spouse' ? 'bg-info text-dark' : 'bg-primary'} fw-bold">${type} Row</span>
                    <button type="button" class="btn-close" onclick="this.closest('.member-item').remove()"></button>
                </div>
                <input type="hidden" name="members[${memberIndex}][relationship_type]" value="${type}">

                <div class="col-md-4">
                    <input type="text" name="members[${memberIndex}][full_name]" class="form-control form-control-sm" placeholder="Full Name" required>
                </div>
                <div class="col-md-3">
                    <input type="date" name="members[${memberIndex}][dob]" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-2">
                    <select name="members[${memberIndex}][gender]" class="form-select form-select-sm" required>
                        <option value="">Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="form-check form-switch mt-1">
                        <input class="form-check-input" type="checkbox" name="members[${memberIndex}][has_disability]" value="1" id="dis_${memberIndex}" onchange="document.getElementById('details_${memberIndex}').classList.toggle('d-none', !this.checked)">
                        <label class="form-check-label small" for="dis_${memberIndex}">Disability?</label>
                    </div>
                </div>
                <div class="col-12 d-none" id="details_${memberIndex}">
                    <input type="text" name="members[${memberIndex}][disability_details]" class="form-control form-control-sm" placeholder="Specify health requirements/notes...">
                </div>
            </div>
        </li>
    `;

    container.insertAdjacentHTML('beforeend', html);
    memberIndex++;
}
</script>

</body>
</html>