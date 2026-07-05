<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-9 mx-auto">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 text-dark mb-0">Direct Administrative Enrollment</h2>
                <a href="<?= base_url('residents') ?>" class="btn btn-outline-secondary btn-sm">← Return to Registry</a>
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

            <form action="<?= base_url('residents/store') ?>" method="POST" id="enrollmentForm">
                <?= csrf_field() ?>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white py-3">
                        <h5 class="mb-0 h6 fw-bold">1. Primary Family Head Demographics</h5>
                    </div>
                    <div class="card-body bg-white row g-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label small fw-bold">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" id="first_name" class="form-control form-control-sm" value="<?= old('first_name') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label small fw-bold">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" id="last_name" class="form-control form-control-sm" value="<?= old('last_name') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="document_id" class="form-label small fw-bold">Registration / Document ID <span class="text-danger">*</span></label>
                            <input type="text" name="document_id" id="document_id" class="form-control form-control-sm" value="<?= old('document_id') ?>" placeholder="e.g., National ID or Passport Number" required>
                        </div>
                        <div class="col-md-6">
                            <label for="dob" class="form-label small fw-bold">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="dob" id="dob" class="form-control form-control-sm" value="<?= old('dob') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="primary_phone" class="form-label small fw-bold">Primary Phone <span class="text-danger">*</span></label>
                            <input type="text" name="primary_phone" id="primary_phone" class="form-control form-control-sm" value="<?= old('primary_phone') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="backup_phone" class="form-label small fw-bold">Secondary Backup Phone <span class="text-muted">(Optional)</span></label>
                            <input type="text" name="backup_phone" id="backup_phone" class="form-control form-control-sm" value="<?= old('backup_phone') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="marital_status" class="form-label small fw-bold">Marital Status <span class="text-danger">*</span></label>
                            <select name="marital_status" id="marital_status" class="form-select form-select-sm" required>
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
                                <label class="form-check-label small fw-bold" for="headDisability">Head of family has a disability / requires medical care</label>
                            </div>
                            <textarea name="disability_details" id="headDisabilityDetails" rows="2" class="form-control form-control-sm <?= old('has_disability') ? '' : 'd-none' ?>" placeholder="Provide accessibility or medical specification tracking notes..."><?= old('disability_details') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-secondary text-white fw-bold d-flex justify-content-between align-items-center py-2">
                        <span class="h6 mb-0 text-white">2. Attachable Spouses & Children Roster</span>
                        <div>
                            <button type="button" class="btn btn-sm btn-light fw-bold px-3 me-1" onclick="addFamilyMember('Spouse')">+ Add Spouse</button>
                            <button type="button" class="btn btn-sm btn-light fw-bold px-3" onclick="addFamilyMember('Child')">+ Add Child</button>
                        </div>
                    </div>
                    <div class="card-body p-0 bg-white">
                        <ul class="list-group list-group-flush" id="familyMembersContainer">
                            <li class="list-group-item text-center text-muted py-4" id="emptyRowNotice">
                                No additional household dependents are currently attached to this entry layout profile.
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="<?= base_url('residents') ?>" class="btn btn-sm btn-outline-secondary px-4">Discard changes</a>
                    <button type="submit" class="btn btn-sm btn-primary px-4 fw-bold">Complete Direct Enrollment</button>
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
<?= $this->endSection() ?>