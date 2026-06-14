<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-1 fw-bold text-dark">
            <i class="bi bi-list-ul me-2 text-primary"></i>Daftar Soal
        </h2>
        <p class="text-muted mb-0">Ujian: <?= esc($exam['title']) ?></p>
    </div>
    <a href="<?= base_url('admin/exams/' . $exam['id'] . '/questions/add') ?>" class="btn btn-primary btn-lg">
        <i class="bi bi-plus-circle me-2"></i>Tambah Soal
    </a>
</div>

<?php if (empty($questions)): ?>
<div class="card border-0 shadow-sm text-center py-5">
    <div class="card-body">
        <i class="bi bi-journal-x mb-3" style="font-size: 4rem; color: var(--primary-light);"></i>
        <h4 class="mb-3">Belum Ada Soal</h4>
        <p class="text-muted mb-4">Mulai dengan menambahkan soal pertama untuk ujian ini</p>
        <a href="<?= base_url('admin/exams/' . $exam['id'] . '/questions/add') ?>" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle me-2"></i>Tambah Soal Pertama
        </a>
    </div>
</div>
<?php else: ?>

<!-- Stats Summary -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card border-0 h-100 stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stat-icon stat-icon-primary">
                            <i class="bi bi-list-ol"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Soal</h6>
                        <h3 class="mb-0 fw-bold"><?= count($questions) ?></h3>
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
                            <i class="bi bi-image"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Dengan Gambar</h6>
                        <h3 class="mb-0 fw-bold"><?= count(array_filter($questions, fn($q) => !empty($q['image_path']))) ?></h3>
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
                            <i class="bi bi-file-text"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Tanpa Gambar</h6>
                        <h3 class="mb-0 fw-bold"><?= count(array_filter($questions, fn($q) => empty($q['image_path']))) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Questions List -->
<div class="accordion" id="questionsAccordion">
    <?php foreach ($questions as $index => $question): 
        $options = json_decode($question['options'], true);
        if (!is_array($options)) $options = [];
    ?>
    <div class="card border-0 shadow-sm mb-3 question-card">
        <!-- ✅ HEADER CARD: Hanya menampilkan "Soal #X" -->
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 text-white fw-bold">Soal #<?= $question['order'] ?></h5>
            <button class="btn btn-light btn-sm" type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#question<?= $question['id'] ?>">
                <i class="bi bi-chevron-down"></i> Lihat Detail
            </button>
        </div>
        
        <div id="question<?= $question['id'] ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>">
            <div class="card-body p-4">
                <!-- Question Text -->
                <div class="mb-4">
                    <h6 class="fw-bold text-muted text-uppercase small mb-2">Pertanyaan</h6>
                    <div class="question-content p-3 bg-light rounded-3">
                        <?= $question['question_text'] ?>
                    </div>
                </div>
                
                <!-- Gambar Soal -->
                <?php if (!empty($question['image_path'])): ?>
                <div class="mb-4">
                    <h6 class="fw-bold text-muted text-uppercase small mb-2">Gambar Soal</h6>
                    <div class="text-center p-3 bg-light rounded-3">
                        <img src="<?= base_url($question['image_path']) ?>" 
                             class="img-fluid rounded-3 border shadow-sm" 
                             alt="Gambar Soal"
                             style="max-height: 400px;"
                             onerror="this.onerror=null; this.src=''; this.alt='Gambar gagal dimuat'; this.parentElement.innerHTML='<div class=\'text-danger\'><i class=\'bi bi-exclamation-triangle\'></i> Gambar tidak dapat dimuat</div>';">
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Options -->
                <div class="mb-3">
                    <h6 class="fw-bold text-muted text-uppercase small mb-2">Pilihan Jawaban</h6>
                    <div class="list-group">
                        <?php foreach ($options as $key => $value): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center <?= $key === $question['correct_answer'] ? 'list-group-item-success' : '' ?>">
                            <div>
                                <span class="fw-bold me-2 bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><?= $key ?></span>
                                <span><?= esc($value) ?></span>
                            </div>
                            <?php if ($key === $question['correct_answer']): ?>
                                <span class="badge bg-success rounded-pill">
                                    <i class="bi bi-check-circle-fill me-1"></i>Jawaban Benar
                                </span>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="mt-4">
    <a href="<?= base_url('admin/exams') ?>" class="btn btn-outline-secondary btn-lg">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Ujian
    </a>
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
.stat-icon-primary { background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%); }
.stat-icon-success { background: linear-gradient(135deg, #059669 0%, var(--success) 100%); }
.stat-icon-warning { background: linear-gradient(135deg, #D97706 0%, var(--warning) 100%); }

.question-card {
    transition: all 0.3s ease;
}
.question-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(30, 149, 224, 0.15) !important;
}
.question-card .card-header {
    border-radius: 16px 16px 0 0 !important;
}
</style>
<?php endif; ?>
<?= $this->endSection() ?>