<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h2 class="mb-4"><i class="bi bi-clipboard-check"></i> Exam Review</h2>
        
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Exam Summary</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <h6>Exam Title</h6>
                            <p class="lead"><?= esc($exam['title']) ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h6>Time Taken</h6>
                            <p class="lead"><?= round($session['total_time_taken'], 2) ?> minutes</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h6>Questions Answered</h6>
                            <p class="lead"><?= count($answersMap) ?> / <?= count($questions) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Your Answers</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Question</th>
                                <th>Your Answer</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($questions as $index => $question): ?>
                            <tr>
                                <td><?= $question['order'] ?></td>
                                <td><?= substr(strip_tags($question['question_text']), 0, 100) ?>...</td>
                                <td>
                                    <?php if (isset($answersMap[$question['id']])): ?>
                                        <?php if ($answersMap[$question['id']] === 'doubtful'): ?>
                                            <span class="badge bg-warning">Marked as Doubtful</span>
                                        <?php else: ?>
                                            <strong><?= $answersMap[$question['id']] ?></strong>
                                            <?php 
                                            $options = json_decode($question['options'], true);
                                            echo '- ' . esc($options[$answersMap[$question['id']]] ?? '');
                                            ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Not Answered</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!isset($answersMap[$question['id']])): ?>
                                        <span class="text-danger">Skipped</span>
                                    <?php elseif ($answersMap[$question['id']] === 'doubtful'): ?>
                                        <span class="text-warning">Doubtful</span>
                                    <?php else: ?>
                                        <span class="text-success">Answered</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <a href="<?= base_url('exam') ?>" class="btn btn-primary mt-3">
            <i class="bi bi-arrow-left"></i> Back to Exams
        </a>
    </div>
</div>
<?= $this->endSection() ?>