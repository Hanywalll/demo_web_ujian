<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h2 class="mb-4"><i class="bi bi-list-check"></i> Available Exams</h2>

<?php if (empty($exams)): ?>
<div class="alert alert-info text-center">
    <i class="bi bi-info-circle"></i> No exams available at the moment.
</div>
<?php else: ?>
<div class="row">
    <?php foreach ($exams as $exam): ?>
    <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-header <?= $exam['registered'] ? 'bg-success text-white' : 'bg-primary text-white' ?>">
                <h5 class="mb-0"><?= esc($exam['title']) ?></h5>
            </div>
            <div class="card-body">
                <p><?= esc($exam['description']) ?></p>
                <ul class="list-unstyled">
                    <li><strong>Duration:</strong> <?= $exam['duration_minutes'] ?> minutes</li>
                    <li><strong>Questions:</strong> <?= $exam['total_questions'] ?></li>
                    <li><strong>Status:</strong> 
                        <span class="badge bg-success">Published</span>
                    </li>
                </ul>
            </div>
            <div class="card-footer">
                <?php if ($exam['registered']): ?>
                <a href="<?= base_url('exam/start/' . $exam['id']) ?>" class="btn btn-success w-100">
                    <i class="bi bi-play-circle"></i> Start Exam
                </a>
                <?php else: ?>
                <a href="<?= base_url('exam/register/' . $exam['id']) ?>" class="btn btn-primary w-100">
                    <i class="bi bi-pencil-square"></i> Register
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?= $this->endSection() ?>