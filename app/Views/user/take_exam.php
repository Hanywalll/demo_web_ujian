<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card bg-light">
                <div class="card-body">
                    <h4>Time Remaining: <span id="timer" class="text-danger">00:00:00</span></h4>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <h5>Question <?= $currentQuestion['order'] ?> of <?= count($questions) ?></h5>
                    
                    <?php if ($currentQuestion['image_path']): ?>
                        <img src="<?= base_url('writable/' . $currentQuestion['image_path']) ?>" 
                             class="img-fluid mb-3" alt="Question Image">
                    <?php endif; ?>
                    
                    <p class="lead question-text">
                        <?= $currentQuestion['question_text'] ?>
                    </p>
                    
                    <?php 
                    $options = json_decode($currentQuestion['options'], true);
                    $currentAnswer = $answersMap[$currentQuestion['id']] ?? '';
                    ?>
                    
                    <div class="options">
                        <?php foreach ($options as $key => $value): ?>
                        <div class="form-check mb-3">
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
                    </div>
                    
                    <button class="btn btn-warning" id="markDoubtful" 
                            data-question-id="<?= $currentQuestion['id'] ?>">
                        Mark as Doubtful
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5>Navigation</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <?php foreach ($navigation as $nav): ?>
                        <a href="<?= base_url('exam/take/' . $session['id'] . '/question/' . $nav['id']) ?>" 
                           class="btn btn-sm m-1 question-nav-btn 
                                  <?= $nav['status'] === 'answered' ? 'btn-success' : 
                                      ($nav['status'] === 'doubtful' ? 'btn-warning' : 'btn-outline-secondary') ?>
                                  <?= ($nav['id'] === $currentQuestion['id']) ? 'active' : '' ?>">
                            <?= $nav['order'] ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    
                    <button class="btn btn-danger btn-block mt-3" id="finishExam">
                        Finish Exam
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">

<script>
const AUTO_SAVE_DELAY = 1500; 
const SERVER_SYNC_INTERVAL = 30000; 

let autoSaveTimer;
let serverSyncTimer;

const examData = {
    sessionId: <?= $session['id'] ?>,
    examId: <?= $session['exam_id'] ?>,
    startTime: '<?= $session['start_time'] ?>',
    endTime: '<?= $session['end_time'] ?>',
    currentQuestionId: <?= $currentQuestion['id'] ?>
};

function initializeTimer() {
    const endTime = new Date(examData.endTime).getTime();
    
    function updateTimer() {
        const now = new Date().getTime();
        const distance = endTime - now;
        
        if (distance < 0) {
            clearInterval(timerInterval);
            document.getElementById('timer').innerHTML = "00:00:00";
            finishExamAutomatically();
            return;
        }
        
        const hours = Math.floor(distance / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        document.getElementById('timer').innerHTML = 
            String(hours).padStart(2, '0') + ':' +
            String(minutes).padStart(2, '0') + ':' +
            String(seconds).padStart(2, '0');
    }
    
    updateTimer();
    const timerInterval = setInterval(updateTimer, 1000);
}

function autoSave(answer) {
    clearTimeout(autoSaveTimer);
    
    autoSaveTimer = setTimeout(() => {
        const questionId = <?= $currentQuestion['id'] ?>;
        
        const answers = JSON.parse(localStorage.getItem('examAnswers') || '{}');
        answers[questionId] = answer;
        localStorage.setItem('examAnswers', JSON.stringify(answers));
        
        const csrfName = document.querySelector('meta[name="csrf-name"]').getAttribute('content');
        const csrfHash = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const formData = new FormData();
        formData.append('session_id', examData.sessionId);
        formData.append('question_id', questionId);
        formData.append('answer', answer);
        formData.append(csrfName, csrfHash);
        
        fetch('/exam/save-answer', {
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
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                }
                console.log('Answer saved successfully');
            }
        })
        .catch(error => {
            console.error('Error saving answer:', error);
        });
    }, AUTO_SAVE_DELAY);
}

function syncServerTime() {
    const csrfName = document.querySelector('meta[name="csrf-name"]').getAttribute('content');
    const csrfHash = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const formData = new FormData();
    formData.append('session_id', examData.sessionId);
    formData.append(csrfName, csrfHash);
    
    fetch('/exam/get-server-time', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            examData.endTime = data.end_time;
            if (data.csrf_token) {
                document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
            }
        }
    });
}

function syncLocalStorage() {
    const answers = JSON.parse(localStorage.getItem('examAnswers') || '{}');
    const csrfName = document.querySelector('meta[name="csrf-name"]').getAttribute('content');
    const csrfHash = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    for (const [questionId, answer] of Object.entries(answers)) {
        const formData = new FormData();
        formData.append('session_id', examData.sessionId);
        formData.append('question_id', questionId);
        formData.append('answer', answer);
        formData.append(csrfName, csrfHash);
        
        fetch('/exam/save-answer', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.csrf_token) {
                document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    initializeTimer();
    
    syncLocalStorage();
    
    serverSyncTimer = setInterval(syncServerTime, SERVER_SYNC_INTERVAL);
    
    document.querySelectorAll('.answer-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            const answer = this.value;
            autoSave(answer);
        });
    });
    
    document.getElementById('markDoubtful').addEventListener('click', function() {
        const questionId = this.getAttribute('data-question-id');
        autoSave('doubtful');
    });
    
    document.getElementById('finishExam').addEventListener('click', function() {
        if (confirm('Are you sure you want to finish the exam?')) {
            const answers = JSON.parse(localStorage.getItem('examAnswers') || '{}');
            const csrfName = document.querySelector('meta[name="csrf-name"]').getAttribute('content');
            const csrfHash = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const promises = [];
            for (const [questionId, answer] of Object.entries(answers)) {
                const formData = new FormData();
                formData.append('session_id', examData.sessionId);
                formData.append('question_id', questionId);
                formData.append('answer', answer);
                formData.append(csrfName, csrfHash);
                
                promises.push(
                    fetch('/exam/save-answer', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                );
            }
            
            Promise.all(promises).then(() => {
                localStorage.removeItem('examAnswers');
                window.location.href = '/exam/finish/' + examData.sessionId;
            });
        }
    });
});

function finishExamAutomatically() {
    alert('Time is up! Exam will be submitted automatically.');
    const answers = JSON.parse(localStorage.getItem('examAnswers') || '{}');
    const csrfName = document.querySelector('meta[name="csrf-name"]').getAttribute('content');
    const csrfHash = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const promises = [];
    for (const [questionId, answer] of Object.entries(answers)) {
        const formData = new FormData();
        formData.append('session_id', examData.sessionId);
        formData.append('question_id', questionId);
        formData.append('answer', answer);
        formData.append(csrfName, csrfHash);
        
        promises.push(
            fetch('/exam/save-answer', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
        );
    }
    
    Promise.all(promises).then(() => {
        localStorage.removeItem('examAnswers');
        window.location.href = '/exam/finish/' + examData.sessionId;
    });
}
</script>

<style>
.question-nav-btn {
    width: 40px;
    height: 40px;
}
.question-text {
    font-size: 1.2rem;
}
</style>

<?= $this->endSection() ?>