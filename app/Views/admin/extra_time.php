<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    #sessionsContainer table th,
    #sessionsContainer table td {
        padding: 0.85rem 1rem;
        vertical-align: middle;
    }
    #sessionsContainer table thead th {
        border-bottom: 2px solid #dee2e6;
    }
</style>

<h2 class="mb-4">
    <i class="bi bi-clock-history me-2"></i>Tambah Waktu Ujian
</h2>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show">
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show">
    <?= session()->getFlashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div id="sessionsContainer">
    <div class="text-center py-4">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Memuat...</span>
        </div>
        <p class="mt-2 text-muted">Memuat data...</p>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
var csrfName = '<?= csrf_token() ?>';
var csrfHash = '<?= csrf_hash() ?>';
var isInputFocused = false;
var inputValues = {};
var isBaselineInitialized = false;

function updateExtraTimeData() {
    if (isInputFocused) return;
    
    var formData = new FormData();
    formData.append(csrfName, csrfHash);
    
    fetch('<?= base_url('admin/get-extra-time-data') ?>', {
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
            
            if (!isBaselineInitialized) {
                renderSessionsTable(data.sessions);
                isBaselineInitialized = true;
                return;
            }
            
            renderSessionsTable(data.sessions);
        }
    })
    .catch(error => {
        console.error('Polling error:', error);
    });
}

function renderSessionsTable(sessions) {
    var container = document.getElementById('sessionsContainer');
    
    if (sessions.length === 0) {
        container.innerHTML = '<div class="alert alert-info text-center">' +
            '<i class="bi bi-info-circle me-2"></i>Tidak ada sesi ujian yang sedang berlangsung saat ini.' +
            '</div>';
        return;
    }
    
    var html = '<div class="table-responsive">' +
        '<table class="table table-striped align-middle">' +
        '<thead class="table-dark">' +
        '<tr>' +
        '<th class="text-start">Pengguna</th>' +
        '<th class="text-start">Ujian</th>' +
        '<th class="text-start">Dimulai</th>' +
        '<th class="text-start">Waktu Berakhir</th>' +
        '<th class="text-start">Tambah Waktu</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody>';
    
    sessions.forEach(function(session) {
        var currentValue = inputValues[session.id] || 10;
        
        html += '<tr data-session-id="' + session.id + '">' +
            '<td class="text-start align-middle">' + session.user_name + '</td>' +
            '<td class="text-start align-middle">' + session.exam_title + '</td>' +
            '<td class="text-start align-middle">' + session.start_time + '</td>' +
            '<td class="text-start align-middle">' + session.end_time + '</td>' +
            '<td class="text-start align-middle">' +
            '<div class="d-flex gap-2 align-items-center">' +
            '<input type="number" ' +
            'class="form-control form-control-sm extra-time-input" ' +
            'data-session-id="' + session.id + '" ' +
            'min="1" max="60" ' +
            'value="' + currentValue + '" ' +
            'style="width: 80px;">' +
            '<button type="button" ' +
            'class="btn btn-warning btn-sm btn-add-time text-white" ' +
            'data-session-id="' + session.id + '">' +
            '<i class="bi bi-clock me-1"></i>Tambah' +
            '</button>' +
            '</div>' +
            '</td>' +
            '</tr>';
    });
    
    html += '</tbody></table></div>';
    container.innerHTML = html;
    
    attachInputListeners();
}

function attachInputListeners() {
    document.querySelectorAll('.extra-time-input').forEach(function(input) {
        var sessionId = input.dataset.sessionId;
        
        if (inputValues[sessionId]) {
            input.value = inputValues[sessionId];
        }
        
        input.addEventListener('focus', function() {
            isInputFocused = true;
        });
        
        input.addEventListener('blur', function() {
            isInputFocused = false;
            inputValues[sessionId] = parseInt(this.value) || 10;
        });
        
        input.addEventListener('input', function() {
            inputValues[sessionId] = parseInt(this.value) || 10;
        });
        
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addExtraTime(sessionId);
            }
        });
    });
    
    document.querySelectorAll('.btn-add-time').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var sessionId = this.dataset.sessionId;
            addExtraTime(sessionId);
        });
    });
}

function addExtraTime(sessionId) {
    var input = document.querySelector('.extra-time-input[data-session-id="' + sessionId + '"]');
    var extraMinutes = parseInt(input.value) || 10;
    var btn = document.querySelector('.btn-add-time[data-session-id="' + sessionId + '"]');
    var originalBtnHtml = btn.innerHTML;
    
    if (extraMinutes < 1 || extraMinutes > 60) {
        alert('Masukkan angka antara 1 sampai 60 menit.');
        return;
    }
    
    input.disabled = true;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
    
    var formData = new FormData();
    formData.append('session_id', sessionId);
    formData.append('extra_minutes', extraMinutes);
    formData.append(csrfName, csrfHash);
    
    fetch('<?= base_url('admin/add-extra-time') ?>', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        input.disabled = false;
        btn.disabled = false;
        btn.innerHTML = originalBtnHtml;
        
        if (data.success) {
            if (data.csrf_token) {
                csrfHash = data.csrf_token;
                document.querySelector('meta[name="csrf-token"]').content = data.csrf_token;
            }
            
            delete inputValues[sessionId];
            updateExtraTimeData();
        } else {
            alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        input.disabled = false;
        btn.disabled = false;
        btn.innerHTML = originalBtnHtml;
        alert('Terjadi kesalahan: ' + error.message);
    });
}

setInterval(updateExtraTimeData, 2000);

document.addEventListener('DOMContentLoaded', function() {
    updateExtraTimeData();
});
</script>
<?= $this->endSection() ?>