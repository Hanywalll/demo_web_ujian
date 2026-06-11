<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h2 class="mb-4"><i class="bi bi-clock-history"></i> Add Extra Time</h2>

<?php if (empty($ongoingSessions)): ?>
<div class="alert alert-info text-center">
    <i class="bi bi-info-circle"></i> No ongoing exam sessions at the moment.
</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>User</th>
                <th>Exam</th>
                <th>Started</th>
                <th>End Time</th>
                <th>Add Time</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ongoingSessions as $session): ?>
            <tr>
                <td><?= esc($session['user_name']) ?></td>
                <td><?= esc($session['exam_title']) ?></td>
                <td><?= date('H:i:s', strtotime($session['start_time'])) ?></td>
                <td><?= date('H:i:s', strtotime($session['end_time'])) ?></td>
                <td>
                    <form action="<?= base_url('admin/add-extra-time') ?>" method="POST" class="d-flex">
                        <?= csrf_field() ?>
                        <input type="hidden" name="session_id" value="<?= $session['id'] ?>">
                        <input type="number" name="extra_minutes" class="form-control form-control-sm me-2" 
                               min="1" max="60" value="10" style="width: 70px;">
                        <button type="submit" class="btn btn-warning btn-sm">
                            <i class="bi bi-clock"></i> Add
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
<?= $this->endSection() ?>