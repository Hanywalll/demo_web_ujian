<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 class="mb-1">
            <i class="bi bi-journal-text me-2"></i>Kelola Ujian
        </h2>
        <p class="text-muted mb-0">Kelola semua ujian yang tersedia di sistem</p>
    </div>
    <a href="<?= base_url('admin/exams/create') ?>" class="btn btn-primary btn-lg">
        <i class="bi bi-plus-circle me-2"></i>Buat Ujian Baru
    </a>
</div>

<?php if (empty($exams)): ?>
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <div class="mb-4">
            <i class="bi bi-journal-x" style="font-size: 5rem; color: var(--primary-light);"></i>
        </div>
        <h4 class="mb-3">Belum Ada Ujian</h4>
        <p class="text-muted mb-4">Mulai dengan membuat ujian pertama Anda</p>
        <a href="<?= base_url('admin/exams/create') ?>" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle me-2"></i>Buat Ujian Pertama
        </a>
    </div>
</div>
<?php else: ?>
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card border-0 h-100 stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stat-icon stat-icon-primary">
                            <i class="bi bi-journal-text"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Ujian</h6>
                        <h3 class="mb-0 fw-bold"><?= count($exams) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card border-0 h-100 stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stat-icon stat-icon-success">
                            <i class="bi bi-cloud-check"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Diterbitkan</h6>
                        <h3 class="mb-0 fw-bold"><?= count(array_filter($exams, fn($e) => $e['status'] === 'published')) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card border-0 h-100 stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stat-icon stat-icon-warning">
                            <i class="bi bi-file-earmark"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Draf</h6>
                        <h3 class="mb-0 fw-bold"><?= count(array_filter($exams, fn($e) => $e['status'] === 'draft')) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <?php foreach ($exams as $exam): ?>
    <div class="col-lg-6 mb-4">
        <div class="card border-0 h-100 exam-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1 text-white"><?= esc($exam['title']) ?></h5>
                    <small class="text-white-50">ID: #<?= $exam['id'] ?></small>
                </div>
                <div>
                    <?php if ($exam['status'] === 'published'): ?>
                        <span class="badge bg-light text-success">
                            <i class="bi bi-cloud-check me-1"></i>Diterbitkan
                        </span>
                    <?php else: ?>
                        <span class="badge bg-light text-warning">
                            <i class="bi bi-file-earmark me-1"></i>Draf
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card-body">
                <p class="text-muted mb-3"><?= esc($exam['description']) ?></p>
                
                <div class="row g-3">
                    <div class="col-6">
                        <div class="info-item">
                            <i class="bi bi-clock text-primary"></i>
                            <div>
                                <small class="text-muted d-block">Durasi</small>
                                <strong><?= $exam['duration_minutes'] ?> menit</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-item">
                            <i class="bi bi-question-circle text-primary"></i>
                            <div>
                                <small class="text-muted d-block">Jumlah Soal</small>
                                <strong><?= $exam['total_questions'] ?> soal</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-item">
                            <i class="bi bi-calendar text-primary"></i>
                            <div>
                                <small class="text-muted d-block">Dibuat</small>
                                <strong><?= date('d M Y', strtotime($exam['created_at'])) ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer bg-white border-top">
                <div class="d-flex gap-2 flex-wrap">
                    <?php if ($exam['status'] === 'draft'): ?>
                        <a href="<?= base_url('admin/exams/' . $exam['id'] . '/publish') ?>" 
                           class="btn btn-success btn-sm flex-grow-1" 
                           onclick="return confirm('Terbitkan ujian ini? Peserta akan bisa mengakses ujian ini.')"
                           title="Terbitkan Ujian">
                            <i class="bi bi-cloud-upload me-1"></i> Terbitkan
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('admin/exams/' . $exam['id'] . '/unpublish') ?>" 
                           class="btn btn-warning btn-sm flex-grow-1" 
                           onclick="return confirm('Batalkan terbit ujian ini? Peserta tidak bisa mengakses lagi.')"
                           title="Batalkan Terbit Ujian">
                            <i class="bi bi-cloud-slash me-1"></i> Batalkan Terbit
                        </a>
                    <?php endif; ?>
                    
                    <a href="<?= base_url('admin/exams/' . $exam['id'] . '/questions') ?>" 
                       class="btn btn-info btn-sm flex-grow-1" 
                       title="Kelola Soal">
                        <i class="bi bi-list-ul me-1"></i> Soal
                        <span class="badge bg-white text-info ms-1"><?= $exam['total_questions'] ?></span>
                    </a>
                    
                    <a href="<?= base_url('admin/exams/' . $exam['id'] . '/questions/add') ?>" 
                       class="btn btn-primary btn-sm" 
                       title="Tambah Soal">
                        <i class="bi bi-plus-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<style>
.stat-card {
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(30, 149, 224, 0.15) !important;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.stat-icon-primary {
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
}

.stat-icon-success {
    background: linear-gradient(135deg, #059669 0%, var(--success) 100%);
}

.stat-icon-warning {
    background: linear-gradient(135deg, #D97706 0%, var(--warning) 100%);
}

.exam-card {
    transition: all 0.3s ease;
}

.exam-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(30, 149, 224, 0.2) !important;
}

.exam-card .card-header {
    border-radius: 16px 16px 0 0 !important;
}

.exam-card .card-footer {
    border-radius: 0 0 16px 16px !important;
}

.info-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    background: var(--gray-50);
    border-radius: 10px;
    transition: all 0.2s ease;
}

.info-item:hover {
    background: var(--primary-lighter);
}

.info-item i {
    font-size: 1.5rem;
    margin-right: 0.75rem;
    flex-shrink: 0;
}

@media (max-width: 768px) {
    .exam-card .card-footer .btn {
        font-size: 0.875rem;
        padding: 0.4rem 0.8rem;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }
}
</style>
<?php endif; ?>
<?= $this->endSection() ?>