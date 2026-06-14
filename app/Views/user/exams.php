<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h2 class="mb-4">
    <i class="bi bi-list-check"></i> Available Exams
    <small class="text-muted" id="lastUpdate" style="font-size: 0.9rem;"></small>
</h2>

<div id="examsContainer">
    <?php if (empty($exams)): ?>
    <div class="alert alert-info text-center">
        <i class="bi bi-info-circle"></i> No exams available at the moment.
    </div>
    <?php else: ?>
    <div class="row">
        <?php foreach ($exams as $exam): ?>
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm exam-card" data-exam-id="<?= $exam['id'] ?>">
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
                    <?php 
                    $latestSession = $exam['latest_session'] ?? null;
                    $isOngoing = false;
                    
                    if ($latestSession) {
                        $isOngoing = ($latestSession['status'] === 'ongoing' && strtotime($latestSession['end_time']) > time());
                    }
                    ?>
                    
                    <?php if (!$exam['registered']): ?>
                        <a href="<?= base_url('exam/register/' . $exam['id']) ?>" class="btn btn-primary w-100">
                            <i class="bi bi-pencil-square"></i> Register
                        </a>
                    <?php elseif ($isOngoing): ?>
                        <a href="<?= base_url('exam/start/' . $exam['id']) ?>" class="btn btn-warning w-100">
                            <i class="bi bi-play-circle"></i> Lanjutkan Ujian
                        </a>
                    <?php elseif ($latestSession && in_array($latestSession['status'], ['finished', 'expired'])): ?>
                        <a href="<?= base_url('exam/review/' . $latestSession['id']) ?>" class="btn btn-secondary w-100">
                            <i class="bi bi-eye"></i> Lihat Hasil (Nilai: <?= $latestSession['score'] ?? '-' ?>)
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('exam/start/' . $exam['id']) ?>" class="btn btn-success w-100">
                            <i class="bi bi-play-circle"></i> Mulai Ujian
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
var csrfName = '<?= csrf_token() ?>';
var csrfHash = '<?= csrf_hash() ?>';
var previousExamsData = null; // ✅ Simpan data sebelumnya untuk diffing

// ========================================
// ESCAPE HTML (keamanan)
// ========================================
function escapeHtml(text) {
    if (!text) return '';
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

// ========================================
// ✅ SMART DIFFING: Cek apakah data berubah
// ========================================
function hasDataChanged(newExams) {
    // Kalau belum ada data sebelumnya → pasti berubah
    if (previousExamsData === null) {
        return true;
    }
    
    // Bandingkan jumlah exam
    if (previousExamsData.length !== newExams.length) {
        return true;
    }
    
    // Bandingkan setiap exam (cek field yang bisa berubah)
    for (var i = 0; i < newExams.length; i++) {
        var oldExam = previousExamsData[i];
        var newExam = newExams[i];
        
        // Cek field yang bisa berubah
        if (oldExam.id !== newExam.id ||
            oldExam.title !== newExam.title ||
            oldExam.description !== newExam.description ||
            oldExam.duration_minutes !== newExam.duration_minutes ||
            oldExam.total_questions !== newExam.total_questions ||
            oldExam.registered !== newExam.registered ||
            oldExam.button_type !== newExam.button_type ||
            oldExam.button_label !== newExam.button_label ||
            oldExam.button_url !== newExam.button_url ||
            oldExam.button_class !== newExam.button_class ||
            oldExam.header_class !== newExam.header_class ||
            oldExam.score !== newExam.score) {
            return true;
        }
    }
    
    // Semua sama → tidak ada perubahan
    return false;
}

// ========================================
// ✅ UPDATE PER-CARD (bukan rebuild total)
// ========================================
function updateExamsUI(newExams) {
    var container = document.getElementById('examsContainer');
    
    // Kasus 1: Tidak ada exam sama sekali
    if (newExams.length === 0) {
        // Cek apakah sudah ada alert "no exams"
        if (!container.querySelector('.alert-info')) {
            container.innerHTML = '<div class="alert alert-info text-center">' +
                '<i class="bi bi-info-circle"></i> No exams available at the moment.' +
                '</div>';
        }
        return;
    }
    
    // Kasus 2: Ada exam, tapi container masih berisi alert
    var alertEl = container.querySelector('.alert-info');
    if (alertEl) {
        // Ganti alert dengan grid baru
        container.innerHTML = '<div class="row"></div>';
    }
    
    var row = container.querySelector('.row');
    if (!row) {
        container.innerHTML = '<div class="row"></div>';
        row = container.querySelector('.row');
    }
    
    // ✅ Dapatkan semua card yang ada sekarang
    var existingCards = {};
    row.querySelectorAll('.exam-card').forEach(function(card) {
        existingCards[card.dataset.examId] = card;
    });
    
    // ✅ Track exam IDs yang ada di data baru
    var newExamIds = {};
    
    // ✅ Update atau tambah card untuk setiap exam baru
    newExams.forEach(function(exam) {
        newExamIds[exam.id] = true;
        var existingCard = existingCards[exam.id];
        
        // Score badge untuk review
        var scoreText = '';
        if (exam.button_type === 'review' && exam.score !== null) {
            scoreText = ' (Nilai: ' + exam.score + ')';
        }
        
        var newCardHtml = 
            '<div class="card-header ' + exam.header_class + '">' +
                '<h5 class="mb-0">' + escapeHtml(exam.title) + '</h5>' +
            '</div>' +
            '<div class="card-body">' +
                '<p>' + escapeHtml(exam.description) + '</p>' +
                '<ul class="list-unstyled">' +
                    '<li><strong>Duration:</strong> ' + exam.duration_minutes + ' minutes</li>' +
                    '<li><strong>Questions:</strong> ' + exam.total_questions + '</li>' +
                    '<li><strong>Status:</strong> <span class="badge bg-success">Published</span></li>' +
                '</ul>' +
            '</div>' +
            '<div class="card-footer">' +
                '<a href="' + exam.button_url + '" class="btn ' + exam.button_class + ' w-100">' +
                    '<i class="bi ' + exam.button_icon + '"></i> ' + exam.button_label + scoreText +
                '</a>' +
            '</div>';
        
        if (existingCard) {
            // ✅ Card sudah ada → cek apakah perlu update
            if (existingCard.dataset.examData !== JSON.stringify(exam)) {
                // Data berubah → update card dengan smooth transition
                existingCard.style.transition = 'opacity 0.3s ease';
                existingCard.style.opacity = '0.7';
                
                setTimeout(function() {
                    existingCard.innerHTML = newCardHtml;
                    existingCard.dataset.examData = JSON.stringify(exam);
                    existingCard.style.opacity = '1';
                }, 150);
            }
        } else {
            // ✅ Card baru → tambahkan dengan animasi
            var colDiv = document.createElement('div');
            colDiv.className = 'col-md-6 mb-4';
            
            var cardDiv = document.createElement('div');
            cardDiv.className = 'card h-100 shadow-sm exam-card';
            cardDiv.dataset.examId = exam.id;
            cardDiv.dataset.examData = JSON.stringify(exam);
            cardDiv.innerHTML = newCardHtml;
            
            // Animasi masuk
            cardDiv.style.opacity = '0';
            cardDiv.style.transform = 'translateY(20px)';
            cardDiv.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            
            colDiv.appendChild(cardDiv);
            row.appendChild(colDiv);
            
            // Trigger animasi
            setTimeout(function() {
                cardDiv.style.opacity = '1';
                cardDiv.style.transform = 'translateY(0)';
            }, 50);
        }
    });
    
    // ✅ Hapus card yang sudah tidak ada di data baru
    Object.keys(existingCards).forEach(function(examId) {
        if (!newExamIds[examId]) {
            var card = existingCards[examId];
            var colDiv = card.closest('.col-md-6');
            
            // Animasi keluar
            card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            card.style.opacity = '0';
            card.style.transform = 'translateY(-20px)';
            
            setTimeout(function() {
                if (colDiv && colDiv.parentNode) {
                    colDiv.parentNode.removeChild(colDiv);
                }
            }, 300);
        }
    });
}

// ========================================
// ✅ POLLING DENGAN SMART DIFFING
// ========================================
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
            // Update CSRF token
            if (data.csrf_token) {
                csrfHash = data.csrf_token;
                document.querySelector('meta[name="csrf-token"]').content = data.csrf_token;
            }
            
            // ✅ SMART DIFFING: Hanya update kalau data berubah
            if (hasDataChanged(data.exams)) {
                updateExamsUI(data.exams);
                previousExamsData = data.exams; // Simpan untuk perbandingan berikutnya
            }
            
            // Update timestamp (ini selalu update, tapi tidak bikin kedip)
            var now = new Date();
            document.getElementById('lastUpdate').textContent = 
                '(Auto-refresh: ' + now.toLocaleTimeString('id-ID') + ')';
        }
    })
    .catch(error => {
        console.error('Polling error:', error);
    });
}

// ✅ Pause polling saat tab tidak aktif (hemat resource)
var pollingInterval = null;

function startPolling() {
    if (!pollingInterval) {
        pollingInterval = setInterval(updateExamsData, 2000);
    }
}

function stopPolling() {
    if (pollingInterval) {
        clearInterval(pollingInterval);
        pollingInterval = null;
    }
}

// Pause saat tab tidak aktif
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        stopPolling();
    } else {
        startPolling();
        updateExamsData(); // Refresh langsung saat kembali
    }
});

// Initial load
document.addEventListener('DOMContentLoaded', function() {
    updateExamsData();
    startPolling();
});
</script>
<?= $this->endSection() ?>