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
                        <button type="submit" class="btn btn-success btn-sm px-4">Save Entry</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>