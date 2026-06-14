<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<<<<<<< HEAD
<<<<<<< HEAD
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
=======
<style>
    /* Modern Score Donut Chart */
    .score-donut {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: conic-gradient(var(--score-color, #10B981) calc(var(--score-percent, 0) * 1%), #E5E7EB 0);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        position: relative;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        transition: all 0.5s ease;
    }
    .score-donut::before {
        content: '';
        position: absolute;
        width: 140px;
        height: 140px;
        background: white;
        border-radius: 50%;
    }
    .score-content {
        position: relative;
        z-index: 1;
        text-align: center;
    }
    .score-value {
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1;
        color: var(--gray-800);
    }
    .score-label {
        font-size: 0.85rem;
        color: var(--gray-600);
        font-weight: 600;
        text-transform: uppercase;
    }

    /* Gradient Stat Boxes */
    .stat-box-gradient-primary { background: linear-gradient(135deg, #3264B9 0%, #1E95E0 100%); }
    .stat-box-gradient-success { background: linear-gradient(135deg, #059669 0%, #10B981 100%); }
    .stat-box-gradient-danger { background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%); }
    .stat-box-gradient-warning { background: linear-gradient(135deg, #D97706 0%, #F59E0B 100%); }
    .stat-box-gradient-secondary { background: linear-gradient(135deg, #4B5563 0%, #1F2937 100%); }

    /* Question Review Cards */
    .question-review-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
        overflow: hidden;
        border-left: 6px solid transparent;
    }
    .q-status-correct { border-left-color: #10B981; }
    .q-status-wrong { border-left-color: #EF4444; }
    .q-status-doubtful { border-left-color: #F59E0B; }
    .q-status-empty { border-left-color: #6B7280; }

    .option-item {
        border-radius: 8px;
        margin-bottom: 0.5rem;
        border: 1px solid var(--gray-200);
        transition: all 0.2s;
    }
    .option-item:hover { background-color: var(--gray-50); }
    .option-correct { background-color: #D1FAE5 !important; border-color: #10B981 !important; color: #065F46; }
    .option-wrong { background-color: #FEE2E2 !important; border-color: #EF4444 !important; color: #991B1B; }
</style>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-1 fw-bold text-dark">
            <i class="bi bi-clipboard-check me-2 text-primary"></i>Hasil Ujian
        </h2>
        <p class="text-muted mb-0"><?= esc($exam['title'] ?? 'Ujian') ?></p>
    </div>
    <a href="<?= base_url('exam') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Ujian
    </a>
</div>

<!-- ✅ FALLBACK: Pastikan variabel ada -->
<?php 
$score = $score ?? 0;
$grade = $grade ?? 'E';
$totalQuestions = $totalQuestions ?? 0;
$correctCount = $correctCount ?? 0;
$wrongCount = $wrongCount ?? 0;
$emptyCount = $emptyCount ?? 0;
$doubtfulCount = $doubtfulCount ?? 0;
?>

<!-- Top Section: Score & Stats -->
<div class="row g-4 mb-4">
    <!-- Score Donut -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-4">
                <h6 class="text-muted text-uppercase small fw-bold mb-4">Nilai Akhir Anda</h6>
                
                <?php 
                    $scoreColor = $score >= 70 ? '#10B981' : ($score >= 60 ? '#F59E0B' : '#EF4444');
                ?>
                <div class="score-donut" style="--score-percent: <?= $score ?>; --score-color: <?= $scoreColor ?>;">
                    <div class="score-content">
                        <div class="score-value" style="color: <?= $scoreColor ?>;"><?= $score ?></div>
                        <div class="score-label">Grade <?= $grade ?></div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between text-muted small mb-1">
                        <span>Waktu Pengerjaan</span>
                        <strong class="text-dark"><?= round($session['total_time_taken'] ?? 0, 1) ?> Menit</strong>
                    </div>
                    <div class="d-flex justify-content-between text-muted small">
                        <span>Selesai Pada</span>
                        <strong class="text-dark"><?= date('d M Y, H:i', strtotime($session['updated_at'] ?? $session['end_time'])) ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="col-lg-8">
        <div class="row g-3 h-100">
            <div class="col-6 col-md-4">
                <div class="stat-box-gradient-primary p-4 rounded-4 h-100 text-white shadow-sm">
                    <i class="bi bi-list-ol fs-2 opacity-75"></i>
                    <h3 class="fw-bold mt-2 mb-0"><?= $totalQuestions ?></h3>
                    <small class="opacity-75 fw-semibold">Total Soal</small>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stat-box-gradient-success p-4 rounded-4 h-100 text-white shadow-sm">
                    <i class="bi bi-check-circle-fill fs-2 opacity-75"></i>
                    <h3 class="fw-bold mt-2 mb-0"><?= $correctCount ?></h3>
                    <small class="opacity-75 fw-semibold">Benar</small>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stat-box-gradient-danger p-4 rounded-4 h-100 text-white shadow-sm">
                    <i class="bi bi-x-circle-fill fs-2 opacity-75"></i>
                    <h3 class="fw-bold mt-2 mb-0"><?= $wrongCount ?></h3>
                    <small class="opacity-75 fw-semibold">Salah</small>
                </div>
            </div>
            <div class="col-6 col-md-6">
                <div class="stat-box-gradient-warning p-4 rounded-4 h-100 text-white shadow-sm">
                    <i class="bi bi-flag-fill fs-2 opacity-75"></i>
                    <h3 class="fw-bold mt-2 mb-0"><?= $doubtfulCount ?></h3>
                    <small class="opacity-75 fw-semibold">Ragu-ragu</small>
                </div>
            </div>
            <div class="col-6 col-md-6">
                <div class="stat-box-gradient-secondary p-4 rounded-4 h-100 text-white shadow-sm">
                    <i class="bi bi-dash-circle-fill fs-2 opacity-75"></i>
                    <h3 class="fw-bold mt-2 mb-0"><?= $emptyCount ?></h3>
                    <small class="opacity-75 fw-semibold">Tidak Dijawab</small>
                </div>
            </div>
        </div>
>>>>>>> userfeature
=======
<style>
    /* Modern Score Donut Chart */
    .score-donut {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: conic-gradient(var(--score-color, #10B981) calc(var(--score-percent, 0) * 1%), #E5E7EB 0);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        position: relative;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        transition: all 0.5s ease;
    }
    .score-donut::before {
        content: '';
        position: absolute;
        width: 140px;
        height: 140px;
        background: white;
        border-radius: 50%;
    }
    .score-content {
        position: relative;
        z-index: 1;
        text-align: center;
    }
    .score-value {
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1;
        color: var(--gray-800);
    }
    .score-label {
        font-size: 0.85rem;
        color: var(--gray-600);
        font-weight: 600;
        text-transform: uppercase;
    }

    /* Gradient Stat Boxes */
    .stat-box-gradient-primary { background: linear-gradient(135deg, #3264B9 0%, #1E95E0 100%); }
    .stat-box-gradient-success { background: linear-gradient(135deg, #059669 0%, #10B981 100%); }
    .stat-box-gradient-danger { background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%); }
    .stat-box-gradient-warning { background: linear-gradient(135deg, #D97706 0%, #F59E0B 100%); }
    .stat-box-gradient-secondary { background: linear-gradient(135deg, #4B5563 0%, #1F2937 100%); }

    /* Question Review Cards */
    .question-review-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
        overflow: hidden;
        border-left: 6px solid transparent;
    }
    .q-status-correct { border-left-color: #10B981; }
    .q-status-wrong { border-left-color: #EF4444; }
    .q-status-doubtful { border-left-color: #F59E0B; }
    .q-status-empty { border-left-color: #6B7280; }

    .option-item {
        border-radius: 8px;
        margin-bottom: 0.5rem;
        border: 1px solid var(--gray-200);
        transition: all 0.2s;
    }
    .option-item:hover { background-color: var(--gray-50); }
    .option-correct { background-color: #D1FAE5 !important; border-color: #10B981 !important; color: #065F46; }
    .option-wrong { background-color: #FEE2E2 !important; border-color: #EF4444 !important; color: #991B1B; }
</style>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-1 fw-bold text-dark">
            <i class="bi bi-clipboard-check me-2 text-primary"></i>Hasil Ujian
        </h2>
        <p class="text-muted mb-0"><?= esc($exam['title'] ?? 'Ujian') ?></p>
    </div>
    <a href="<?= base_url('exam') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Ujian
    </a>
</div>

<!-- ✅ FALLBACK: Pastikan variabel ada -->
<?php 
$score = $score ?? 0;
$grade = $grade ?? 'E';
$totalQuestions = $totalQuestions ?? 0;
$correctCount = $correctCount ?? 0;
$wrongCount = $wrongCount ?? 0;
$emptyCount = $emptyCount ?? 0;
$doubtfulCount = $doubtfulCount ?? 0;
?>

<!-- Top Section: Score & Stats -->
<div class="row g-4 mb-4">
    <!-- Score Donut -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-4">
                <h6 class="text-muted text-uppercase small fw-bold mb-4">Nilai Akhir Anda</h6>
                
                <?php 
                    $scoreColor = $score >= 70 ? '#10B981' : ($score >= 60 ? '#F59E0B' : '#EF4444');
                ?>
                <div class="score-donut" style="--score-percent: <?= $score ?>; --score-color: <?= $scoreColor ?>;">
                    <div class="score-content">
                        <div class="score-value" style="color: <?= $scoreColor ?>;"><?= $score ?></div>
                        <div class="score-label">Grade <?= $grade ?></div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between text-muted small mb-1">
                        <span>Waktu Pengerjaan</span>
                        <strong class="text-dark"><?= round($session['total_time_taken'] ?? 0, 1) ?> Menit</strong>
                    </div>
                    <div class="d-flex justify-content-between text-muted small">
                        <span>Selesai Pada</span>
                        <strong class="text-dark"><?= date('d M Y, H:i', strtotime($session['updated_at'] ?? $session['end_time'])) ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="col-lg-8">
        <div class="row g-3 h-100">
            <div class="col-6 col-md-4">
                <div class="stat-box-gradient-primary p-4 rounded-4 h-100 text-white shadow-sm">
                    <i class="bi bi-list-ol fs-2 opacity-75"></i>
                    <h3 class="fw-bold mt-2 mb-0"><?= $totalQuestions ?></h3>
                    <small class="opacity-75 fw-semibold">Total Soal</small>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stat-box-gradient-success p-4 rounded-4 h-100 text-white shadow-sm">
                    <i class="bi bi-check-circle-fill fs-2 opacity-75"></i>
                    <h3 class="fw-bold mt-2 mb-0"><?= $correctCount ?></h3>
                    <small class="opacity-75 fw-semibold">Benar</small>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stat-box-gradient-danger p-4 rounded-4 h-100 text-white shadow-sm">
                    <i class="bi bi-x-circle-fill fs-2 opacity-75"></i>
                    <h3 class="fw-bold mt-2 mb-0"><?= $wrongCount ?></h3>
                    <small class="opacity-75 fw-semibold">Salah</small>
                </div>
            </div>
            <div class="col-6 col-md-6">
                <div class="stat-box-gradient-warning p-4 rounded-4 h-100 text-white shadow-sm">
                    <i class="bi bi-flag-fill fs-2 opacity-75"></i>
                    <h3 class="fw-bold mt-2 mb-0"><?= $doubtfulCount ?></h3>
                    <small class="opacity-75 fw-semibold">Ragu-ragu</small>
                </div>
            </div>
            <div class="col-6 col-md-6">
                <div class="stat-box-gradient-secondary p-4 rounded-4 h-100 text-white shadow-sm">
                    <i class="bi bi-dash-circle-fill fs-2 opacity-75"></i>
                    <h3 class="fw-bold mt-2 mb-0"><?= $emptyCount ?></h3>
                    <small class="opacity-75 fw-semibold">Tidak Dijawab</small>
                </div>
            </div>
        </div>
>>>>>>> userfeature
    </div>
</div>

<!-- Bottom Section: Question Details -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-journal-text me-2 text-primary"></i>Tinjauan Detail Jawaban</h5>
    </div>
    <div class="card-body p-4">
        <?php if (empty($questionResults)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                Tidak ada data soal untuk ditampilkan.
            </div>
        <?php else: ?>
            <?php foreach ($questionResults as $index => $result): 
                if (!isset($result['question'])) continue;
                
                $q = $result['question'];
                $options = json_decode($q['options'] ?? '[]', true);
                if (!is_array($options)) $options = [];
                
                $statusClass = 'q-status-empty'; $badgeClass = 'secondary'; $statusText = 'Tidak Dijawab'; $icon = 'dash-circle-fill';
                if ($result['status'] === 'correct') { $statusClass = 'q-status-correct'; $badgeClass = 'success'; $statusText = 'Benar'; $icon = 'check-circle-fill'; }
                elseif ($result['status'] === 'wrong') { $statusClass = 'q-status-wrong'; $badgeClass = 'danger'; $statusText = 'Salah'; $icon = 'x-circle-fill'; }
                elseif (strpos($result['status'] ?? '', 'doubtful') !== false) { $statusClass = 'q-status-doubtful'; $badgeClass = 'warning'; $statusText = 'Ragu-ragu'; $icon = 'flag-fill'; }
            ?>
            <div class="question-review-card card <?= $statusClass ?>">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                        <h5 class="fw-bold mb-0 text-dark">
                            <span class="text-muted me-2">#<?= $q['order'] ?? ($index + 1) ?></span>
                            <?= $q['question_text'] ?>
                        </h5>
                        <span class="badge bg-<?= $badgeClass ?> bg-opacity-10 text-<?= $badgeClass ?> rounded-pill px-3 py-2">
                            <i class="bi bi-<?= $icon ?> me-1"></i> <?= $statusText ?>
                        </span>
                    </div>

                    <?php if (!empty($q['image_path'])): ?>
                    <div class="mb-4 text-center">
                        <img src="<?= base_url($q['image_path']) ?>" class="img-fluid rounded-3 border" style="max-height: 300px;">
                    </div>
                    <?php endif; ?>

                    <div class="options-list mt-3">
                        <?php foreach ($options as $key => $value): 
                            $optionClass = 'option-item p-3 d-flex align-items-center';
                            $iconHtml = '';
                            $userAnswer = $result['user_answer'] ?? null;
                            $correctAnswer = $result['correct_answer'] ?? null;
                            
                            if ($userAnswer === $key && in_array($result['status'], ['correct', 'doubtful_correct'])) {
                                $optionClass .= ' option-correct';
                                $iconHtml = '<i class="bi bi-check-circle-fill text-success ms-auto fs-5"></i>';
                            } elseif ($userAnswer === $key && in_array($result['status'], ['wrong', 'doubtful_wrong'])) {
                                $optionClass .= ' option-wrong';
                                $iconHtml = '<i class="bi bi-x-circle-fill text-danger ms-auto fs-5"></i>';
                            } elseif ($correctAnswer === $key) {
                                $optionClass .= ' option-correct';
                                $iconHtml = '<i class="bi bi-check-circle-fill text-success ms-auto fs-5" title="Jawaban Benar"></i>';
                            }
                        ?>
                        <div class="<?= $optionClass ?>">
                            <span class="fw-bold me-3 bg-light text-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; flex-shrink: 0;"><?= $key ?></span>
                            <span class="flex-grow-1"><?= $value ?></span>
                            <?= $iconHtml ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.MathJax) {
            MathJax.typesetPromise().catch((err) => console.log('MathJax error:', err));
        }
    });
</script>
<?= $this->endSection() ?>