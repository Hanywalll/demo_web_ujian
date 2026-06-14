<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-clipboard-check"></i> Review Ujian: <?= esc($exam['title']) ?></h2>
        </div>
    </div>
    
    <!-- KARTU SKOR -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white <?= $score >= 70 ? 'bg-success' : ($score >= 60 ? 'bg-warning' : 'bg-danger') ?>">
                <div class="card-body text-center">
                    <h6 class="card-title">Nilai Anda</h6>
                    <h1 class="display-1 fw-bold"><?= $score ?></h1>
                    <h3>Grade: <?= $grade ?></h3>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Statistik Jawaban</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span><i class="bi bi-list-ol"></i> Total Soal</span>
                            <strong><?= $totalQuestions ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between text-success">
                            <span><i class="bi bi-check-circle"></i> Benar</span>
                            <strong><?= $correctCount ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between text-danger">
                            <span><i class="bi bi-x-circle"></i> Salah</span>
                            <strong><?= $wrongCount ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between text-warning">
                            <span><i class="bi bi-flag"></i> Ragu-ragu</span>
                            <strong><?= $doubtfulCount ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between text-secondary">
                            <span><i class="bi bi-dash-circle"></i> Tidak Dijawab</span>
                            <strong><?= $emptyCount ?></strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Informasi Sesi</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Mulai</span>
                            <strong><?= date('H:i:s', strtotime($session['start_time'])) ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Selesai</span>
                            <strong><?= date('H:i:s', strtotime($session['updated_at'] ?? $session['end_time'])) ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Durasi</span>
                            <strong><?= round($session['total_time_taken'] ?? 0, 2) ?> menit</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- DETAIL PER SOAL -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-list-check"></i> Detail Jawaban</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($questionResults)): ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> Tidak ada data soal untuk ditampilkan.
                    </div>
                    <?php else: ?>
                        <?php foreach ($questionResults as $index => $result): 
                            // ✅ FIX: Pastikan 'question' ada
                            if (!isset($result['question'])) {
                                continue; // Skip kalau data tidak valid
                            }
                            
                            $q = $result['question'];
                            $options = json_decode($q['options'] ?? '[]', true);
                            if (!is_array($options)) $options = [];
                            
                            $borderClass = '';
                            $statusBadge = '';
                            
                            if ($result['status'] === 'correct') {
                                $borderClass = 'border-success';
                                $statusBadge = '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Benar</span>';
                            } elseif ($result['status'] === 'wrong') {
                                $borderClass = 'border-danger';
                                $statusBadge = '<span class="badge bg-danger"><i class="bi bi-x-circle"></i> Salah</span>';
                            } elseif ($result['status'] === 'doubtful_correct') {
                                $borderClass = 'border-warning';
                                $statusBadge = '<span class="badge bg-warning text-dark"><i class="bi bi-flag"></i> Ragu-ragu (Benar)</span>';
                            } elseif ($result['status'] === 'doubtful_wrong') {
                                $borderClass = 'border-warning';
                                $statusBadge = '<span class="badge bg-warning text-dark"><i class="bi bi-flag"></i> Ragu-ragu (Salah)</span>';
                            } else {
                                $borderClass = 'border-secondary';
                                $statusBadge = '<span class="badge bg-secondary"><i class="bi bi-dash-circle"></i> Tidak Dijawab</span>';
                            }
                        ?>
                        <div class="card mb-3 <?= $borderClass ?>" style="border-width: 2px !important;">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <strong>Soal <?= $q['order'] ?? ($index + 1) ?></strong>
                                <?= $statusBadge ?>
                            </div>
                            <div class="card-body">
                                <p class="question-text mb-3"><?= $q['question_text'] ?? '' ?></p>
                                
                                <?php if (!empty($q['image_path'])): ?>
                                <div class="mb-3">
                                    <img src="<?= base_url($q['image_path']) ?>" 
                                        class="img-fluid border rounded" 
                                        alt="Question Image"
                                        style="max-height: 300px;">
                                </div>
                                <?php endif; ?>
                                
                                <?php foreach ($options as $key => $value): 
                                    $optionClass = 'list-group-item';
                                    $icon = '';
                                    
                                    $userAnswer = $result['user_answer'] ?? null;
                                    $correctAnswer = $result['correct_answer'] ?? null;
                                    
                                    // Jawaban user
                                    if ($userAnswer === $key) {
                                        if (in_array($result['status'], ['correct', 'doubtful_correct'])) {
                                            $optionClass .= ' list-group-item-success';
                                            $icon = '<i class="bi bi-check-circle-fill text-success"></i> (Jawaban Anda)';
                                        } else {
                                            $optionClass .= ' list-group-item-danger';
                                            $icon = '<i class="bi bi-x-circle-fill text-danger"></i> (Jawaban Anda)';
                                        }
                                    }
                                    // Jawaban benar
                                    elseif ($correctAnswer === $key) {
                                        $optionClass .= ' list-group-item-success';
                                        $icon = '<i class="bi bi-check-circle-fill text-success"></i> (Jawaban Benar)';
                                    }
                                ?>
                                <div class="<?= $optionClass ?> mb-1">
                                    <strong><?= esc($key) ?>.</strong> <?= esc($value) ?>
                                    <?= $icon ?>
                                </div>
                                <?php endforeach; ?>
                                
                                <?php if ($result['is_doubtful'] ?? false): ?>
                                <div class="mt-2">
                                    <small class="text-warning">
                                        <i class="bi bi-flag-fill"></i> Soal ini ditandai ragu-ragu
                                    </small>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- TOMBOL AKSI -->
    <div class="row mt-4 mb-4">
        <div class="col-12 text-center">
            <a href="<?= base_url('exam') ?>" class="btn btn-primary btn-lg">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Ujian
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>