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
                    
                    <div class="mt-3">
                        <button class="btn btn-warning" id="markDoubtful">
                            <i class="bi bi-flag"></i> Ragu-ragu
                        </button>
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
                           class="btn btn-sm m-1 question-nav-btn 
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
var examEndTime = new Date('<?= $session['end_time'] ?>').getTime();
var timerInterval = setInterval(function() {
    var now = new Date().getTime();
    var distance = examEndTime - now;
    
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
}, 1000);

document.getElementById('finishExam').addEventListener('click', function() {
    if (confirm('Yakin ingin menyelesaikan ujian?')) {
        window.location.href = '<?= base_url('exam/finish/' . $session['id']) ?>';
    }
});

document.getElementById('markDoubtful').addEventListener('click', function() {
    var questionId = '<?= $currentQuestion['id'] ?>';
    
    var answers = JSON.parse(localStorage.getItem('examAnswers') || '{}');
    answers[questionId] = 'doubtful';
    localStorage.setItem('examAnswers', JSON.stringify(answers));
    
    alert('Soal ditandai ragu-ragu!');
    location.reload();
});

document.querySelectorAll('.answer-radio').forEach(function(radio) {
    radio.addEventListener('change', function() {
        var questionId = this.dataset.questionId;
        var answer = this.value;
        
        var answers = JSON.parse(localStorage.getItem('examAnswers') || '{}');
        answers[questionId] = answer;
        localStorage.setItem('examAnswers', JSON.stringify(answers));
    });
});
</script>
<?= $this->endSection() ?>