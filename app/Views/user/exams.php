<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-1 fw-bold text-dark">
            <i class="bi bi-list-check me-2 text-primary"></i>Available Exams
        </h2>
        <p class="text-muted mb-0">Pilih ujian yang ingin Anda ikuti atau lanjutkan</p>
    </div>
    <div class="d-flex align-items-center bg-white px-3 py-2 rounded-3 shadow-sm">
        <span class="live-indicator me-2"></span>
        <small class="text-muted fw-semibold" id="lastUpdate">Memuat data...</small>
    </div>
</div>

<!-- Exams Container -->
<div id="examsContainer">
    <?php if (empty($exams)): ?>
    <!-- Empty State -->
    <div class="card border-0 shadow-sm text-center py-5">
        <div class="card-body">
            <i class="bi bi-journal-x mb-3" style="font-size: 4rem; color: var(--primary-light);"></i>
            <h4 class="mb-3">Belum Ada Ujian Tersedia</h4>
            <p class="text-muted">Silakan kembali nanti untuk melihat ujian baru.</p>
        </div>
    </div>
    <?php else: ?>
    <!-- Initial Grid -->
    <div class="row g-4">
        <?php foreach ($exams as $exam): 
            // Prepare button state
            $latestSession = $exam['latest_session'] ?? null;
            $isOngoing = $latestSession && ($latestSession['status'] === 'ongoing' && strtotime($latestSession['end_time']) > time());
            
            $btnClass = 'primary'; $btnIcon = 'bi-pencil-square'; $btnLabel = 'Daftar Ujian'; $btnUrl = base_url('exam/register/' . $exam['id']);
            if ($exam['registered']) {
                if ($isOngoing) {
                    $btnClass = 'warning'; $btnIcon = 'bi-play-circle'; $btnLabel = 'Lanjutkan'; $btnUrl = base_url('exam/start/' . $exam['id']);
                } elseif ($latestSession && in_array($latestSession['status'], ['finished', 'expired'])) {
                    $btnClass = 'secondary'; $btnIcon = 'bi-eye'; $btnLabel = 'Lihat Hasil (' . ($latestSession['score'] ?? '-') . ')'; $btnUrl = base_url('exam/review/' . $latestSession['id']);
                } else {
                    $btnClass = 'success'; $btnIcon = 'bi-play-circle'; $btnLabel = 'Mulai Ujian'; $btnUrl = base_url('exam/start/' . $exam['id']);
                }
            }
            
            $examDataJson = htmlspecialchars(json_encode([
                'id' => $exam['id'], 'title' => $exam['title'], 'description' => $exam['description'],
                'duration_minutes' => $exam['duration_minutes'], 'total_questions' => $exam['total_questions'],
                'registered' => $exam['registered'], 'button_type' => $btnClass, 'button_label' => $btnLabel,
                'button_url' => $btnUrl, 'button_class' => $btnClass, 'button_icon' => $btnIcon,
                'score' => $latestSession['score'] ?? null
            ]), ENT_QUOTES, 'UTF-8');
        ?>
        <div class="col-md-6 col-lg-4">
            <div class="card exam-card border-0 h-100 shadow-sm" data-exam-id="<?= $exam['id'] ?>" data-exam-data='<?= $examDataJson ?>'>
                <!-- ✅ SELALU BIRU (exam-header-primary) -->
                <div class="card-header exam-header-primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-white fw-bold"><?= esc($exam['title']) ?></h5>
                        <span class="badge bg-white text-primary bg-opacity-90">
                            <?= $exam['registered'] ? 'Terdaftar' : 'Baru' ?>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3"><?= esc($exam['description']) ?></p>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="info-badge bg-light rounded-3 p-2 text-center">
                                <i class="bi bi-clock text-primary d-block mb-1"></i>
                                <small class="text-muted d-block">Durasi</small>
                                <strong class="text-dark"><?= $exam['duration_minutes'] ?> mnt</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-badge bg-light rounded-3 p-2 text-center">
                                <i class="bi bi-question-circle text-primary d-block mb-1"></i>
                                <small class="text-muted d-block">Soal</small>
                                <strong class="text-dark"><?= $exam['total_questions'] ?> item</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top">
                    <a href="<?= $btnUrl ?>" class="btn btn-<?= $btnClass ?> w-100 fw-bold">
                        <i class="bi <?= $btnIcon ?> me-2"></i><?= $btnLabel ?>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<style>
    /* Live Indicator */
    .live-indicator {
        width: 10px; height: 10px;
        background-color: #10B981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulse-green 2s infinite;
    }
    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    /* Exam Cards */
    .exam-card {
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .exam-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(30, 149, 224, 0.15) !important;
    }

    /* ✅ SELALU BIRU */
    .exam-header-primary {
        background: linear-gradient(135deg, #3264B9 0%, #1E95E0 100%);
        border-radius: 12px 12px 0 0 !important;
        padding: 1.25rem;
    }

    .info-badge {
        transition: background-color 0.2s;
    }
    .exam-card:hover .info-badge {
        background-color: var(--primary-lighter) !important;
    }

    /* Smooth Update Transition */
    #examsContainer {
        transition: opacity 0.3s ease;
    }
    .exam-card-enter {
        animation: slideUp 0.4s ease forwards;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
var csrfName = '<?= csrf_token() ?>';
var csrfHash = '<?= csrf_hash() ?>';
var previousExamsData = null;

function escapeHtml(text) {
    if (!text) return '';
    var map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

function renderCardHtml(exam) {
    var badgeText = exam.registered ? 'Terdaftar' : 'Baru';
    var scoreText = (exam.button_class === 'secondary' && exam.score !== null) ? ' (' + exam.score + ')' : '';

    // ✅ SELALU BIRU (exam-header-primary)
    return '<div class="card exam-card border-0 h-100 shadow-sm exam-card-enter" data-exam-id="' + exam.id + '">' +
        '<div class="card-header exam-header-primary">' +
            '<div class="d-flex justify-content-between align-items-center">' +
                '<h5 class="mb-0 text-white fw-bold">' + escapeHtml(exam.title) + '</h5>' +
                '<span class="badge bg-white text-primary bg-opacity-90">' + badgeText + '</span>' +
            '</div>' +
        '</div>' +
        '<div class="card-body">' +
            '<p class="text-muted mb-3">' + escapeHtml(exam.description) + '</p>' +
            '<div class="row g-2 mb-3">' +
                '<div class="col-6"><div class="info-badge bg-light rounded-3 p-2 text-center">' +
                    '<i class="bi bi-clock text-primary d-block mb-1"></i><small class="text-muted d-block">Durasi</small>' +
                    '<strong class="text-dark">' + exam.duration_minutes + ' mnt</strong>' +
                '</div></div>' +
                '<div class="col-6"><div class="info-badge bg-light rounded-3 p-2 text-center">' +
                    '<i class="bi bi-question-circle text-primary d-block mb-1"></i><small class="text-muted d-block">Soal</small>' +
                    '<strong class="text-dark">' + exam.total_questions + ' item</strong>' +
                '</div></div>' +
            '</div>' +
        '</div>' +
        '<div class="card-footer bg-white border-top">' +
            '<a href="' + exam.button_url + '" class="btn btn-' + exam.button_class + ' w-100 fw-bold">' +
                '<i class="bi ' + exam.button_icon + ' me-2"></i>' + exam.button_label + scoreText +
            '</a>' +
        '</div>' +
    '</div>';
}

function hasDataChanged(newExams) {
    if (previousExamsData === null) return true;
    if (previousExamsData.length !== newExams.length) return true;
    for (var i = 0; i < newExams.length; i++) {
        var old = previousExamsData[i];
        var n = newExams[i];
        if (old.id !== n.id || old.title !== n.title || old.description !== n.description ||
            old.duration_minutes !== n.duration_minutes || old.total_questions !== n.total_questions ||
            old.registered !== n.registered || old.button_class !== n.button_class ||
            old.button_label !== n.button_label || old.button_url !== n.button_url ||
            old.score !== n.score) {
            return true;
        }
    }
    return false;
}

function updateExamsUI(newExams) {
    var container = document.getElementById('examsContainer');
    if (newExams.length === 0) {
        container.innerHTML = '<div class="card border-0 shadow-sm text-center py-5"><div class="card-body"><i class="bi bi-journal-x mb-3" style="font-size: 4rem; color: var(--primary-light);"></i><h4 class="mb-3">Belum Ada Ujian Tersedia</h4><p class="text-muted">Silakan kembali nanti untuk melihat ujian baru.</p></div></div>';
        return;
    }

    var html = '<div class="row g-4">';
    newExams.forEach(function(exam) {
        html += '<div class="col-md-6 col-lg-4">' + renderCardHtml(exam) + '</div>';
    });
    html += '</div>';

    // Smooth fade transition
    container.style.opacity = '0.5';
    setTimeout(function() {
        container.innerHTML = html;
        container.style.opacity = '1';
    }, 150);
}

function updateExamsData() {
    var formData = new FormData();
    formData.append(csrfName, csrfHash);
    
    fetch('<?= base_url('exam/get-exams-data') ?>', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.csrf_token) {
                csrfHash = data.csrf_token;
                document.querySelector('meta[name="csrf-token"]').content = data.csrf_token;
            }
            
            if (hasDataChanged(data.exams)) {
                updateExamsUI(data.exams);
                previousExamsData = data.exams;
            }
            
            var now = new Date();
            document.getElementById('lastUpdate').textContent = 'Live: ' + now.toLocaleTimeString('id-ID');
        }
    })
    .catch(error => console.error('Polling error:', error));
}

var pollingInterval = null;
function startPolling() {
    if (!pollingInterval) pollingInterval = setInterval(updateExamsData, 2000);
}
function stopPolling() {
    if (pollingInterval) { clearInterval(pollingInterval); pollingInterval = null; }
}

document.addEventListener('visibilitychange', function() {
    if (document.hidden) stopPolling(); else { startPolling(); updateExamsData(); }
});

document.addEventListener('DOMContentLoaded', function() {
    var initialCards = document.querySelectorAll('.exam-card');
    if (initialCards.length > 0) {
        previousExamsData = Array.from(initialCards).map(function(card) {
            return JSON.parse(card.dataset.examData);
        });
    }
    startPolling();
});
</script>
<?= $this->endSection() ?>