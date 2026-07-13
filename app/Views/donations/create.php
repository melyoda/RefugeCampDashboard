<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Record Incoming Funding Transaction</h5>
                </div>
                <div class="card-body bg-light">

                    <?php if (session()->getFlashdata('errors')) : ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('donations/store') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="donor_name" class="form-label fw-bold">Donor Entity / Source Name <span class="text-danger">*</span></label>
                            <input type="text" name="donor_name" id="donor_name" class="form-control" value="<?= old('donor_name') ?>" placeholder="e.g. Individual, UNHCR, Red Cross, Anonymous" required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="amount" class="form-label fw-bold">Amount Received (USD) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0.01" name="amount" id="amount" class="form-control" value="<?= old('amount') ?>" placeholder="0.00" required>
                            </div>
                            <div class="col-md-6">
                                <label for="donation_date" class="form-label fw-bold">Date Received <span class="text-danger">*</span></label>
                                <input type="date" name="donation_date" id="donation_date" class="form-control" value="<?= old('donation_date', date('Y-m-d')) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Transaction Details / Allocations</label>
                            <textarea name="notes" id="notes" class="form-control" rows="4" placeholder="Optional earmarks or reference numbers..."><?= old('notes') ?></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="<?= base_url('donations') ?>" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success px-4">Log Financial Entry</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>