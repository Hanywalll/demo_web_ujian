<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Create New Exam</h4>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/exams/create') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Exam Title *</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               placeholder="e.g., Mathematics Final Exam" value="<?= old('title') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="3" required><?= old('description') ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="duration_minutes" class="form-label">Duration (minutes) *</label>
                        <input type="number" class="form-control" id="duration_minutes" 
                               name="duration_minutes" min="1" value="<?= old('duration_minutes', 60) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                        <small class="text-muted">Published exams will be visible to students</small>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-save"></i> Create Exam
                        </button>
                        <a href="<?= base_url('admin/exams') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>