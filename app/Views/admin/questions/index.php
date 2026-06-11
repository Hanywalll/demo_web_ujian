<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-list-ul"></i> Questions</h2>
        <h5 class="text-muted">Exam: <?= esc($exam['title']) ?></h5>
    </div>
    <a href="<?= base_url('admin/exams/' . $exam['id'] . '/questions/add') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Question
    </a>
</div>

<?php if (empty($questions)): ?>
<div class="alert alert-info text-center">
    <i class="bi bi-info-circle"></i> No questions added yet. 
    <a href="<?= base_url('admin/exams/' . $exam['id'] . '/questions/add') ?>" class="alert-link">Add first question</a>
</div>
<?php else: ?>
<div class="accordion" id="questionsAccordion">
    <?php foreach ($questions as $index => $question): ?>
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" 
                    data-bs-toggle="collapse" data-bs-target="#question<?= $question['id'] ?>">
                <strong>Q<?= $question['order'] ?>:</strong> 
                <?= substr(strip_tags($question['question_text']), 0, 80) ?>...
            </button>
        </h2>
        <div id="question<?= $question['id'] ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>">
            <div class="accordion-body">
                <div class="question-content mb-3">
                    <?= $question['question_text'] ?>
                </div>
                
                <?php if ($question['image_path']): ?>
                <div class="mb-3">
                    <img src="<?= base_url('writable/' . $question['image_path']) ?>" 
                         class="img-fluid" style="max-height: 200px;">
                </div>
                <?php endif; ?>
                
                <?php 
                $options = json_decode($question['options'], true);
                ?>
                <div class="options">
                    <strong>Options:</strong>
                    <ul class="list-group mb-3">
                        <?php foreach ($options as $key => $value): ?>
                        <li class="list-group-item <?= $key === $question['correct_answer'] ? 'list-group-item-success' : '' ?>">
                            <strong><?= $key ?>.</strong> <?= $value ?>
                            <?= $key === $question['correct_answer'] ? '<span class="badge bg-success float-end">Correct Answer</span>' : '' ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<a href="<?= base_url('admin/exams') ?>" class="btn btn-secondary mt-3">
    <i class="bi bi-arrow-left"></i> Back to Exams
</a>
<?= $this->endSection() ?>