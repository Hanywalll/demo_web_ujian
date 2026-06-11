<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    <i class="bi bi-plus-circle"></i> Add Question to: <?= esc($exam['title']) ?>
                </h4>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/exams/' . $exam['id'] . '/questions/add') ?>" 
                      method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="question_text" class="form-label">Question Text *</label>
                        <textarea class="form-control" id="question_text" name="question_text" 
                                  rows="4" required><?= old('question_text') ?></textarea>
                        <small class="text-muted">
                            You can use LaTeX for math formulas: $x^2 + y^2 = z^2$
                        </small>
                        <div class="mt-2">
                            <strong>Preview:</strong>
                            <div id="mathPreview" class="border p-2 mt-1"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="question_image" class="form-label">Question Image (Optional)</label>
                        <input type="file" class="form-control" id="question_image" name="question_image" 
                               accept="image/jpeg,image/png,image/jpg">
                        <small class="text-muted">Max size: 2MB. Supported: JPG, PNG</small>
                    </div>
                    
                    <hr>
                    <h5>Options</h5>
                    
                    <?php foreach (range('A', 'D') as $option): ?>
                    <div class="mb-3">
                        <label for="option_<?= $option ?>" class="form-label">
                            Option <?= $option ?> *
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><strong><?= $option ?></strong></span>
                            <input type="text" class="form-control" id="option_<?= $option ?>" 
                                   name="option_<?= $option ?>" value="<?= old('option_' . $option) ?>" required>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <div class="mb-3">
                        <label for="correct_answer" class="form-label">Correct Answer *</label>
                        <select class="form-control" id="correct_answer" name="correct_answer" required>
                            <option value="">Select correct answer</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-save"></i> Add Question
                        </button>
                        <a href="<?= base_url('admin/exams/' . $exam['id'] . '/questions') ?>" 
                           class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('question_text').addEventListener('input', function() {
    const preview = document.getElementById('mathPreview');
    preview.innerHTML = this.value;
    
    if (window.MathJax) {
        MathJax.typesetPromise([preview]).catch((err) => console.log(err));
    }
});
</script>
<?= $this->endSection() ?>