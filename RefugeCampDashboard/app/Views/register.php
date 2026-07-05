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
                <p class="text-muted">Please fill in your family records accurately. Your application will be sent to triage for admin approval.</p>
            </div>

            <form action="<?= base_url('household-register/save') ?>" method="POST" id="registrationForm">
                <?= csrf_field() ?>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white fw-bold">1. Head of Family Details</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Registration / Document ID <span class="text-danger">*</span></label>
                            <input type="text" name="document_id" class="form-control" placeholder="e.g., National ID or Passport" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="dob" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Primary Phone <span class="text-danger">*</span></label>
                            <input type="text" name="primary_phone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Secondary Backup Phone <span class="text-muted">(Optional)</span></label>
                            <input type="text" name="backup_phone" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Marital Status <span class="text-danger">*</span></label>
                            <select name="marital_status" class="form-select" required>
                                <option value="">Select...</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Widowed">Widowed</option>
                                <option value="Divorced">Divorced</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch my-2">
                                <input class="form-check-input" type="checkbox" name="has_disability" value="1" id="headDisability" onchange="document.getElementById('headDisabilityDetails').classList.toggle('d-none', !this.checked)">
                                <label class="form-check-label fw-bold" for="headDisability">Head of family has a disability / requires medical care</label>
                            </div>
                            <textarea name="disability_details" id="headDisabilityDetails" rows="2" class="form-control d-none" placeholder="Provide accessibility or medical specification tracking notes..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-secondary text-white fw-bold d-flex justify-content-between align-items-center">
                        <span>2. Household Spouses & Children</span>
                        <div>
                            <button type="button" class="btn btn-sm btn-light fw-bold me-1" onclick="addFamilyMember('Spouse')">+ Add Spouse</button>
                            <button type="button" class="btn btn-sm btn-light fw-bold" onclick="addFamilyMember('Child')">+ Add Child</button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush" id="familyMembersContainer">
                            <li class="list-group-item text-center text-muted py-4" id="emptyRowNotice">
                                No spouses or children added yet. Click the buttons above if applicable.
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-dark btn-lg fw-bold">Submit Secure Registration</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
let memberIndex = 0;

function addFamilyMember(type) {
    // Hide the empty notice row if it's there
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