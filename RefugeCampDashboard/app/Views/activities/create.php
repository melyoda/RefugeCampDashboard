<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>
Log New Activity
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Log a New Camp Activity
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8 max-width-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h6 class="mb-0">Activity details & Expenses</h6>
            </div>
            <div class="card-body">

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger py-2">
                        <ul class="mb-0 small">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <form action="<?= base_url('activities/store') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                        <div class="row g-3 mb-3">
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="is_distributed_aid" value="1" id="isAid" <?= old('is_distributed_aid', $activity['is_distributed_aid'] ?? 0) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold" for="isAid">This is a distributed aid item / resource</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="aid_category" class="form-label fw-bold small">Aid Category <span class="text-muted">(If applicable)</span></label>
                            <select name="aid_category" id="aid_category" class="form-select">
                                <option value="">-- General / Operational Expense --</option>
                                <option value="Water Supply" <?= old('aid_category', $activity['aid_category'] ?? '') === 'Water Supply' ? 'selected' : '' ?>>🚰 Water Supply (Trucks, Maintenance)</option>
                                <option value="Food Basket" <?= old('aid_category', $activity['aid_category'] ?? '') === 'Food Basket' ? 'selected' : '' ?>>📦 Food Basket / Crates</option>
                                <option value="Hygiene Kit" <?= old('aid_category', $activity['aid_category'] ?? '') === 'Hygiene Kit' ? 'selected' : '' ?>>🧼 Cleaning Supplies / Hygiene Kits</option>
                                <option value="Medical Supplies" <?= old('aid_category', $activity['aid_category'] ?? '') === 'Medical Supplies' ? 'selected' : '' ?>>🩺 Medical Equipment & Care</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold small">Activity / Purchase Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" value="<?= old('title') ?>" placeholder="e.g., Medical Supply Shipment, Water Tank Repair">
                    </div>

                    <div class="mb-3">
                        <label for="cost" class="form-label fw-bold small">Cost (USD) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="cost" id="cost" class="form-control" value="<?= old('cost') ?>" placeholder="0.00">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold small">Operational Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control" placeholder="Provide background details, quantities, or tracking notes..."><?= old('description') ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="receipt" class="form-label fw-bold small">Upload Receipt (Image or PDF)</label>
                        <input type="file" name="receipt" id="receipt" class="form-control">
                        <div class="form-text text-muted extra-small">Max size allowed is defined by XAMPP's php.ini configuration.</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="<?= base_url('activities') ?>" class="btn btn-light border btn-sm">Cancel & Return</a>
                        <div class="mb-3 card p-3 border-0 shadow-sm">
                            <label class="form-label fw-bold mb-1">Benefiting Residents <span class="text-muted small fw-normal">(Optional — check off individuals who directly received items/care)</span></label>
                            <small class="text-muted d-block mb-2">Leave blank if this is a general camp expense (e.g., site repairs, common water tank refill).</small>

                            <div style="max-height: 200px; overflow-y: auto;" class="border rounded p-2 bg-white">
                                <?php if (!empty($residents)): foreach ($residents as $res): ?>
                                    <div class="form-check py-1 border-bottom border-light">
                                        <input class="form-check-input" type="checkbox" name="resident_ids[]" value="<?= $res['id'] ?>" id="res_<?= $res['id'] ?>"
                                            <?= (isset($activity) && !empty($db->table('activity_residents')->where(['activity_id' => $activity['id'], 'resident_id' => $res['id']])->get()->getRow())) ? 'checked' : '' ?>>
                                        <label class="form-check-label small" for="res_<?= $res['id'] ?>">
                                            <strong><?= esc($res['full_name']) ?></strong> (<?= esc($res['last_name']) ?>, <?= esc($res['first_name']) ?>)
                                        </label>
                                    </div>
                                <?php endforeach; else: ?>
                                    <span class="text-muted small p-2 d-block">No active residents found to link.</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm px-4">Save Entry</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>