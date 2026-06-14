<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="mb-4">
            <a href="<?= base_url('admin/exams') ?>" class="text-decoration-none text-muted d-inline-flex align-items-center">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Ujian
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header border-0">
                <div class="d-flex align-items-center">
                    <div class="header-icon-wrapper me-3">
                        <i class="bi bi-plus-circle"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 text-white fw-bold">Create New Exam</h4>
                        <small class="text-white-50">Isi detail di bawah untuk membuat ujian baru</small>
                    </div>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                <form action="<?= base_url('admin/exams/create') ?>" method="POST" id="createExamForm">
                    <?= csrf_field() ?>
                    
                    <div class="mb-4">
                        <label for="title" class="form-label fw-bold">
                            <i class="bi bi-journal-text me-1 text-primary"></i> Exam Title <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg" id="title" name="title" 
                                   placeholder="Contoh: Ujian Akhir Semester Matematika" 
                                   value="<?= old('title') ?>" required>
                        </div>
                        <div class="form-text">Berikan nama yang jelas dan mudah dikenali oleh siswa.</div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">
                            <i class="bi bi-card-text me-1 text-primary"></i> Description <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="4" required placeholder="Jelaskan tujuan, materi, atau instruksi umum ujian ini..."><?= old('description') ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="duration_minutes" class="form-label fw-bold">
                                <i class="bi bi-clock me-1 text-primary"></i> Duration <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control form-control-lg" id="duration_minutes" 
                                       name="duration_minutes" min="1" max="300" 
                                       value="<?= old('duration_minutes', 60) ?>" required>
                                <span class="input-group-text">minutes</span>
                            </div>
                            <div class="form-text">Waktu pengerjaan dalam menit (1 - 300).</div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label for="status" class="form-label fw-bold">
                                <i class="bi bi-toggle-on me-1 text-primary"></i> Status
                            </label>
                            <select class="form-select form-select-lg" id="status" name="status">
                                <option value="draft" <?= old('status') === 'draft' ? 'selected' : '' ?>>📝 Draft</option>
                                <option value="published" <?= old('status') === 'published' ? 'selected' : '' ?>>🚀 Published</option>
                            </select>
                            <div class="form-text">
                                <strong>Draft:</strong> Hanya terlihat oleh admin.<br>
                                <strong>Published:</strong> Langsung terlihat oleh siswa.
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-primary d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                        <div>
                            <strong>Tips:</strong> Anda dapat menyimpan ujian sebagai <em>Draft</em> terlebih dahulu untuk menambahkan soal, lalu mengubah statusnya menjadi <em>Published</em> saat siap.
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3 flex-wrap">
                        <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                            <i class="bi bi-save me-2"></i> Create Exam
                        </button>
                        <a href="<?= base_url('admin/exams') ?>" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-x-lg me-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .card-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
        padding: 2rem;
        border-radius: 16px 16px 0 0 !important;
    }

    .header-icon-wrapper {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        backdrop-filter: blur(5px);
    }

    .form-label {
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 2px solid var(--gray-200);
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(30, 149, 224, 0.15);
    }

    .input-group-text {
        background-color: var(--gray-100);
        border: 2px solid var(--gray-200);
        border-left: none;
        color: var(--gray-600);
        font-weight: 600;
    }

    .form-control:focus + .input-group-text,
    .form-control:focus ~ .input-group-text {
        border-color: var(--primary);
    }

    .form-text {
        font-size: 0.85rem;
        color: var(--gray-600);
        margin-top: 0.4rem;
    }

    .alert-primary {
        background: linear-gradient(135deg, rgba(30, 149, 224, 0.1) 0%, rgba(90, 179, 247, 0.1) 100%);
        border: 1px solid rgba(30, 149, 224, 0.2);
        color: var(--primary-dark);
        border-radius: 12px;
    }

    .btn-outline-secondary {
        border: 2px solid var(--gray-200);
        color: var(--gray-600);
    }

    .btn-outline-secondary:hover {
        background: var(--gray-100);
        border-color: var(--gray-600);
        color: var(--gray-800);
    }

    @media (max-width: 768px) {
        .card-header {
            padding: 1.5rem;
        }
        .card-body {
            padding: 1.5rem !important;
        }
        .header-icon-wrapper {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }
    }
</style>
<?= $this->endSection() ?>