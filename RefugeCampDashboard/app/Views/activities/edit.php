<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>
Edit Activity
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Modify Activity Record
<?= $this->endSection() ?>
0592659636
<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h6 class="mb-0">Update Details</h6>
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

                <form action="<?= base_url('activities/update/' . $activity['id']) ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold small">Activity / Purchase Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" value="<?= old('title', $activity['title']) ?>">
                    </div>

                    <div class="mb-3">
                        <label for="cost" class="form-label fw-bold small">Cost (USD) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="cost" id="cost" class="form-control" value="<?= old('cost', $activity['cost']) ?>">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold small">Operational Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control"><?= old('description', $activity['description']) ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="receipt" class="form-label fw-bold small">Replace Receipt (Leave blank to keep existing)</label>
                        <input type="file" name="receipt" id="receipt" class="form-control">
                        <?php if ($activity['receipt_path']): ?>
                            <div class="form-text text-info small">📎 A receipt is currently attached to this log.</div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="<?= base_url('activities') ?>" class="btn btn-light border btn-sm">Cancel</a>
                        <div class="mb-3 card p-3 border-0 shadow-sm">
                        <button type="submit" class="btn btn-primary btn-sm px-4">Update Entry</button>
                    </div>
                </form>
            </div>

                <label class="form-label fw-bold mb-1">Benefiting Residents <span class="text-muted small fw-normal">(Optional — check off individuals who directly received items/care)</span></label>
                <small class="text-muted d-block mb-2">Leave blank if this is a general camp expense (e.g., site repairs, common water tank refill).</small>
                <div style="max-height: 200px; overflow-y: auto;" class="border rounded p-2 bg-white">
                    <?php if (!empty($residents)): foreach ($residents as $res): ?>
                    <div class="form-check py-1 border-bottom border-light">
                        <input class="form-check-input" type="checkbox" name="resident_ids[]" value="<?= $res['id'] ?>" id="res_<?= $res['id'] ?>"
                            <?= (isset($linkedResidentIds) && in_array($res['id'], $linkedResidentIds)) ? 'checked' : '' ?>>
                        <label class="form-check-label small" for="res_<?= $res['id'] ?>">
                        <strong><?= esc($res['full_name']) ?></strong> (<?= esc($res['last_name']) ?>, <?= esc($res['first_name']) ?>)
                        </label>
                    </div>
                        <?php endforeach; else: ?>
                            <span class="text-muted small p-2 d-block">No active residents found to link.</span>
                        <?php endif; ?>
                    </div>
                </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>