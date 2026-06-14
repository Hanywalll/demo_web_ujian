<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h4>Sisa Waktu: <span id="timer" style="font-size: 2rem; font-weight: bold;">00:00:00</span></h4>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <h5>Soal <?= $currentQuestion['order'] ?> dari <?= count($questions) ?></h5>
                    
                    <div class="question-text mb-3">
                        <?= $currentQuestion['question_text'] ?>
                    </div>

                    <?php if (!empty($currentQuestion['image_path'])): ?>
                    <div class="mb-3 text-center">
                        <img src="<?= base_url($currentQuestion['image_path']) ?>" 
                            class="img-fluid border rounded shadow-sm" 
                            alt="Question Image"
                            style="max-height: 400px;"
                            onerror="console.error('Gambar gagal dimuat:', this.src); this.style.display='none';">
                    </div>
                    <?php endif; ?>
                    
                    <?php 
                    $options = json_decode($currentQuestion['options'], true);
                    $currentAnswer = $answersMap[$currentQuestion['id']] ?? '';
                    ?>
                    
                    <?php foreach ($options as $key => $value): ?>
                    <div class="form-check mb-2">
                        <input class="form-check-input answer-radio" type="radio" 
                               name="answer" id="option_<?= $key ?>" 
                               value="<?= $key ?>"
                               data-question-id="<?= $currentQuestion['id'] ?>"
                               <?= ($currentAnswer === $key) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="option_<?= $key ?>">
                            <strong><?= $key ?>.</strong> <?= $value ?>
                        </label>
                    </div>
                    <?php endforeach; ?>
                    
                    <div class="mt-3 d-flex gap-2">
                        <button class="btn btn-warning" id="markDoubtful" 
                                data-question-id="<?= $currentQuestion['id'] ?>">
                            <i class="bi bi-flag"></i> Ragu-ragu
                        </button>
                        
                        <?php 
                        $currentIndex = array_search($currentQuestion['id'], array_column($questions, 'id'));
                        $prevQuestion = $currentIndex > 0 ? $questions[$currentIndex - 1] : null;
                        $nextQuestion = $currentIndex < count($questions) - 1 ? $questions[$currentIndex + 1] : null;
                        ?>
                        
                        <?php if ($prevQuestion): ?>
                        <a href="<?= base_url('exam/take/' . $session['id'] . '/question/' . $prevQuestion['id']) ?>" 
                           class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Sebelumnya
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($nextQuestion): ?>
                        <a href="<?= base_url('exam/take/' . $session['id'] . '/question/' . $nextQuestion['id']) ?>" 
                           class="btn btn-primary">
                            Selanjutnya <i class="bi bi-arrow-right"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5>Navigasi Soal</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <?php foreach ($navigation as $nav): ?>
                        <a href="<?= base_url('exam/take/' . $session['id'] . '/question/' . $nav['id']) ?>" 
                           data-question-id="<?= $nav['id'] ?>"
                           class="btn btn-sm m-1 question-nav-btn nav-btn-<?= $nav['id'] ?>
                                  <?= $nav['status'] === 'answered' ? 'btn-success' : 
                                      ($nav['status'] === 'doubtful' ? 'btn-warning' : 'btn-outline-secondary') ?>">
                            <?= $nav['order'] ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    
                    <button class="btn btn-danger w-100 mt-3" id="finishExam">
                        <i class="bi bi-stop-circle"></i> Selesai Ujian
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
var examEndTimestamp = <?= strtotime($session['end_time']) * 1000 ?>;
var currentQuestionId = <?= $currentQuestion['id'] ?>;
var sessionId = <?= $session['id'] ?>;
var localStorageKey = 'examAnswers_' + sessionId;

// ========================================
// TIMER FUNCTIONS
// ========================================
function updateTimer() {
    var now = new Date().getTime();
    var distance = examEndTimestamp - now;
    
    if (distance <= 0) {
        clearInterval(timerInterval);
        document.getElementById('timer').innerHTML = "WAKTU HABIS!";
        alert('Waktu habis! Ujian akan diselesaikan.');
        window.location.href = '<?= base_url('exam/finish/' . $session['id']) ?>';
        return;
    }
    
    var hours = Math.floor(distance / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    document.getElementById('timer').innerHTML = 
        String(hours).padStart(2, '0') + ':' + 
        String(minutes).padStart(2, '0') + ':' + 
        String(seconds).padStart(2, '0');
}

var timerInterval = setInterval(updateTimer, 1000);
updateTimer();

// ========================================
// NAVIGATION INDICATOR
// ========================================
function updateNavigationIndicator(questionId, status) {
    var navBtn = document.querySelector('.nav-btn-' + questionId);
    if (!navBtn) return;
    
    navBtn.classList.remove('btn-success', 'btn-warning', 'btn-outline-secondary');
    
    if (status === 'answered') {
        navBtn.classList.add('btn-success');
    } else if (status === 'doubtful') {
        navBtn.classList.add('btn-warning');
    } else {
        navBtn.classList.add('btn-outline-secondary');
    }
}

// ========================================
// LOCALSTORAGE BACKUP FUNCTIONS
// ========================================
function saveToLocalStorage(questionId, answer, isDoubtful) {
    var savedAnswers = JSON.parse(localStorage.getItem(localStorageKey) || '{}');
    savedAnswers[questionId] = { 
        answer: answer, 
        isDoubtful: isDoubtful,
        timestamp: new Date().getTime()
    };
    localStorage.setItem(localStorageKey, JSON.stringify(savedAnswers));
}

function removeFromLocalStorage(questionId) {
    var savedAnswers = JSON.parse(localStorage.getItem(localStorageKey) || '{}');
    delete savedAnswers[questionId];
    localStorage.setItem(localStorageKey, JSON.stringify(savedAnswers));
}

function syncLocalStorageToServer() {
    var savedAnswers = JSON.parse(localStorage.getItem(localStorageKey) || '{}');
    var hasData = false;
    
    for (var qId in savedAnswers) {
        if (savedAnswers.hasOwnProperty(qId)) {
            hasData = true;
            console.log('Syncing from localStorage:', qId, savedAnswers[qId]);
            sendAnswerToServer(qId, savedAnswers[qId].answer, savedAnswers[qId].isDoubtful, false);
        }
    }
    
    if (hasData) {
        console.log('✅ Sync localStorage ke server selesai');
    }
}

// ========================================
// AJAX SAVE FUNCTION
// ========================================
function sendAnswerToServer(questionId, answer, isDoubtful, showLog = true) {
    var csrfName = document.querySelector('meta[name="csrf-name"]').content;
    var csrfHash = document.querySelector('meta[name="csrf-token"]').content;
    
    var formData = new FormData();
    formData.append('session_id', sessionId);
    formData.append('question_id', questionId);
    formData.append('answer', answer);
    formData.append('is_doubtful', isDoubtful ? 1 : 0);
    formData.append(csrfName, csrfHash);
    
    fetch('<?= base_url('exam/save-answer') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.csrf_token) {
                document.querySelector('meta[name="csrf-token"]').content = data.csrf_token;
            }
            
            if (showLog) {
                console.log('✅ Jawaban tersimpan:', questionId, answer, isDoubtful ? '(ragu)' : '');
            }
            
            // Update indikator navigasi
            var status = isDoubtful ? 'doubtful' : (answer ? 'answered' : 'unanswered');
            updateNavigationIndicator(questionId, status);
            
            // Hapus dari localStorage karena sudah tersimpan di server
            removeFromLocalStorage(questionId);
        } else {
            console.error('❌ Gagal simpan:', data);
        }
    })
    .catch(error => {
        console.error('❌ Error AJAX:', error);
    });
}

// Wrapper function: simpan ke localStorage dulu, lalu kirim ke server
function saveAnswerToServer(questionId, answer, isDoubtful = false) {
    // 1. Simpan ke localStorage sebagai backup (instant)
    saveToLocalStorage(questionId, answer, isDoubtful);
    
    // 2. Kirim ke server (async)
    sendAnswerToServer(questionId, answer, isDoubtful, true);
}

// ========================================
// POLLING: Sync timer setiap 3 detik
// ========================================
setInterval(function() {
    var csrfName = document.querySelector('meta[name="csrf-name"]').content;
    var csrfHash = document.querySelector('meta[name="csrf-token"]').content;
    
    var formData = new FormData();
    formData.append('session_id', sessionId);
    formData.append(csrfName, csrfHash);
    
    fetch('<?= base_url('exam/get-server-time') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.end_time) {
            var newEndTimestamp = new Date(data.end_time).getTime();
            var serverOffset = new Date(data.server_time).getTimezoneOffset();
            var clientOffset = new Date().getTimezoneOffset();
            var offsetDiff = (clientOffset - serverOffset) * 60 * 1000;
            
            examEndTimestamp = newEndTimestamp + offsetDiff;
        }
    })
    .catch(error => {
        console.error('Sync timer error:', error);
    });
}, 2000);

// ========================================
// EVENT LISTENERS
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    // ✅ SYNC: Kembalikan jawaban dari localStorage saat halaman load
    syncLocalStorageToServer();
    
    // ✅ Event listener untuk radio button
    document.querySelectorAll('.answer-radio').forEach(function(radio) {
        radio.addEventListener('change', function() {
            var questionId = this.dataset.questionId;
            var answer = this.value;
            saveAnswerToServer(questionId, answer, false);
        });
    });
    
    // ✅ Event listener untuk tombol ragu-ragu
    document.getElementById('markDoubtful').addEventListener('click', function() {
        var questionId = this.dataset.questionId;
        
        // Ambil jawaban yang sedang dipilih (kalau ada)
        var selectedRadio = document.querySelector('.answer-radio[data-question-id="' + questionId + '"]:checked');
        var currentAnswer = selectedRadio ? selectedRadio.value : '';
        
        // Simpan jawaban + flag ragu
        saveAnswerToServer(questionId, currentAnswer, true);
        
        // Flash effect untuk feedback visual
        var navBtn = document.querySelector('.nav-btn-' + questionId);
        if (navBtn) {
            navBtn.style.transform = 'scale(1.3)';
            setTimeout(function() {
                navBtn.style.transform = 'scale(1)';
            }, 300);
        }
        
        // Update indikator langsung
        updateNavigationIndicator(questionId, 'doubtful');
    });
    
    // ✅ Event listener untuk tombol selesai
    document.getElementById('finishExam').addEventListener('click', function() {
        if (confirm('Yakin ingin menyelesaikan ujian?')) {
            // Sync semua jawaban yang masih di localStorage sebelum finish
            syncLocalStorageToServer();
            
            // Tunggu sebentar agar AJAX selesai, lalu redirect
            setTimeout(function() {
                // Hapus localStorage setelah ujian selesai
                localStorage.removeItem(localStorageKey);
                window.location.href = '<?= base_url('exam/finish/' . $session['id']) ?>';
            }, 500);
        }
    });
    
    // ✅ PERINGATAN: Kalau user coba keluar tanpa finish
    window.addEventListener('beforeunload', function(e) {
        var savedAnswers = JSON.parse(localStorage.getItem(localStorageKey) || '{}');
        if (Object.keys(savedAnswers).length > 0) {
            var message = 'Masih ada jawaban yang belum tersimpan ke server. Yakin ingin keluar?';
            e.returnValue = message;
            return message;
        }
    });
});
</script>
<?= $this->endSection() ?>