<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">

    <div class="mb-4">
        <a href="<?= base_url('activities') ?>" class="text-decoration-none text-muted small">← Back to Activities Log</a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success shadow-sm"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="row g-4">

        <div class="col-md-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 h6 fw-bold">Logistics & Procurement Profile</h5>
                </div>
                <div class="card-body bg-light">
                    <div class="mb-3">
                        <label class="text-muted small fw-bold text-uppercase d-block">Item / Purchase Title</label>
                        <span class="fs-5 fw-bold text-dark"><?= esc($activity['title']) ?></span>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="text-muted small fw-bold text-uppercase d-block">Cost Allocation</label>
                            <span class="fs-5 fw-bold text-dark"><?= number_format($activity['cost'], 2) ?> CUR</span>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small fw-bold text-uppercase d-block">Classification</label>
                            <span class="badge <?= $activity['is_distributed_aid'] ? 'bg-info text-dark' : 'bg-secondary' ?> mt-1">
                                <?= $activity['is_distributed_aid'] ? '📦 Distributed Aid Material' : '⚙️ Site Operational Cost' ?>
                            </span>
                        </div>
                    </div>

                    <?php if ($activity['aid_category']): ?>
                        <div class="mb-3">
                            <label class="text-muted small fw-bold text-uppercase d-block">Material Category</label>
                            <span class="fw-bold text-secondary"><?= esc($activity['aid_category']) ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <label class="text-muted small fw-bold text-uppercase d-block">Operational Description</label>
                        <p class="text-dark bg-white p-3 rounded border text-muted small" style="white-space: pre-line;"><?= esc($activity['description'] ?: 'No expanded description or notes attached to this item record.') ?></p>
                    </div>

                    <?php if ($activity['receipt_path']): ?>
                        <div class="d-grid">
                            <a href="<?= base_url($activity['receipt_path']) ?>" target="_blank" class="btn btn-outline-dark btn-sm">
                                📎 View Attached Audit Receipt / Invoice Document
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-muted small text-center p-2 border border-dashed rounded bg-white">No proof-of-purchase document attached.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 h6 fw-bold text-dark">Field Handout & Recipient Tracking</h5>
                </div>
                <div class="card-body">

                    <?php if ($activity['is_distributed_aid'] == 1): ?>
                        <p class="text-muted small mb-3">
                            Check off families or individual residents as they arrive to receive their allocated share of this resource lot.
                        </p>

                        <div class="mb-3">
                            <div class="input-group input-group-sm shadow-sm">
                                <span class="input-group-text bg-white text-muted border-end-0">🔍</span>
                                <input type="text" id="residentSearch" class="form-control border-start-0 ps-1" placeholder="Type name or family tag to filter roster instantly...">
                            </div>
                        </div>

                        <form action="<?= base_url('activities/save-distribution/' . $activity['id']) ?>" method="POST">
                            <?= csrf_field() ?>

                            <div style="max-height: 440px; overflow-y: auto;" class="border rounded shadow-sm bg-white mb-3">
                                <div class="list-group list-group-flush" id="distributionList">
                                    <?php if (!empty($residents)): foreach ($residents as $res): ?>

                                        <label class="list-group-item list-group-item-action d-flex align-items-center justify-content-between px-3 py-2.5 ms-0 resident-item" 
                                            style="cursor: pointer;"
                                            for="res_<?= $res['id'] ?>"
                                            data-name="<?= strtolower(esc($res['full_name'] . ' ' . $res['last_name'] . ' ' . ($res['document_id'] ?? ''))) ?>"> <div class="d-flex align-items-center">
                                                <div class="me-3 d-flex align-items-center">
                                                    <input class="form-check-input my-0" type="checkbox" name="resident_ids[]" value="<?= $res['id'] ?>" id="res_<?= $res['id'] ?>"
                                                        <?= (isset($linkedResidentIds) && in_array($res['id'], $linkedResidentIds)) ? 'checked' : '' ?>>
                                                </div>

                                                <div>
                                                    <span class="d-block fw-bold text-dark small mb-0"><?= esc($res['full_name']) ?></span>
                                                    <span class="text-muted text-uppercase font-monospace" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                                                        ID: <?= esc($res['document_id'] ?: 'N/A') ?> • <?= esc($res['last_name']) ?>
                                                    </span>
                                                </div>
                                            </div>

                                            <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1.5 font-monospace small">
                                                <?= $res['children_count'] ?> Dep
                                            </span>
                                        </label>

                                    <?php endforeach; else: ?>
                                        <div class="text-muted small p-4 text-center">No active site rosters available.</div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary btn-sm px-4">Update Distribution Manifest</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="fs-1 text-muted mb-2">⚙️</div>
                            <h6 class="text-secondary fw-bold">General Site Operating Expense</h6>
                            <p class="text-muted small max-width-300 mx-auto">
                                This item was logged as a general camp operational cost (not marked as direct material aid distribution). Individual client tracking is disabled.
                            </p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('residentSearch');
    const items = document.querySelectorAll('.resident-item');

    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            const query = e.target.value.toLowerCase().trim();

            items.forEach(function (item) {
                const nameData = item.getAttribute('data-name');

                if (nameData.includes(query)) {
                    item.style.setProperty('display', 'flex', 'important');
                } else {
                    item.style.setProperty('display', 'none', 'important');
                }
            });
        });
    }
});
</script>
<?= $this->endSection() ?>