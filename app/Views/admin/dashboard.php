<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">
            <i class="bi bi-speedometer2"></i> Admin Dashboard
        </h2>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Exams</h5>
                        <h2 class="mb-0"><?= $totalExams ?? 0 ?></h2>
                    </div>
                    <i class="bi bi-journal-text" style="font-size: 3rem;"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <a href="<?= base_url('admin/exams') ?>" class="text-white text-decoration-none">
                    Manage Exams <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Users</h5>
                        <h2 class="mb-0"><?= $totalUsers ?? 0 ?></h2>
                    </div>
                    <i class="bi bi-people" style="font-size: 3rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Questions</h5>
                        <h2 class="mb-0"><?= $totalQuestions ?? 0 ?></h2>
                    </div>
                    <i class="bi bi-question-circle" style="font-size: 3rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-rocket"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="<?= base_url('admin/exams/create') ?>" class="list-group-item list-group-item-action">
                        <i class="bi bi-plus-circle"></i> Create New Exam
                    </a>
                    <a href="<?= base_url('admin/exams') ?>" class="list-group-item list-group-item-action">
                        <i class="bi bi-list"></i> View All Exams
                    </a>
                    <a href="<?= base_url('admin/add-extra-time') ?>" class="list-group-item list-group-item-action">
                        <i class="bi bi-clock"></i> Add Extra Time
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-info-circle"></i> System Information</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><strong>PHP Version:</strong> <?= phpversion() ?></li>
                    <li><strong>CodeIgniter Version:</strong> <?= \CodeIgniter\CodeIgniter::CI_VERSION ?></li>
                    <li><strong>Server Time:</strong> <?= date('Y-m-d H:i:s') ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>