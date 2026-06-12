<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-journal-text"></i> Manage Exams</h2>
    <a href="<?= base_url('admin/exams/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Create New Exam
    </a>
</div>

<?php if (empty($exams)): ?>
<div class="alert alert-info text-center">
    <i class="bi bi-info-circle"></i> No exams created yet. 
    <a href="<?= base_url('admin/exams/create') ?>" class="alert-link">Create one now</a>
</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Duration</th>
                <th>Questions</th>
                <th>Status</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($exams as $exam): ?>
            <tr>
                <td><?= $exam['id'] ?></td>
                <td><?= esc($exam['title']) ?></td>
                <td><?= $exam['duration_minutes'] ?> mins</td>
                <td><?= $exam['total_questions'] ?></td>
                <td>
                    <span class="badge <?= $exam['status'] === 'published' ? 'bg-success' : 'bg-warning' ?>">
                        <?= ucfirst($exam['status']) ?>
                    </span>
                </td>
                <td><?= date('d M Y', strtotime($exam['created_at'])) ?></td>
                <td>
                    <a href="<?= base_url('admin/exams/' . $exam['id'] . '/questions') ?>" 
                       class="btn btn-sm btn-info" title="Manage Questions">
                        <i class="bi bi-list-ul"></i> Questions
                    </a>
                    <a href="<?= base_url('admin/exams/' . $exam['id'] . '/questions/add') ?>" 
                       class="btn btn-sm btn-success" title="Add Question">
                        <i class="bi bi-plus"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
<?= $this->endSection() ?>