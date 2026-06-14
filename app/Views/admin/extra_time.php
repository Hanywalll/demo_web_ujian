<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h2 class="mb-4">
    <i class="bi bi-clock-history"></i> Add Extra Time
    <small class="text-muted" id="lastUpdate" style="font-size: 0.9rem;"></small>
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
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">Memuat data...</p>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
var csrfName = '<?= csrf_token() ?>';
var csrfHash = '<?= csrf_hash() ?>';

// ========================================
// POLLING EXTRA TIME SETIAP 2 DETIK
// ========================================
function updateExtraTimeData() {
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
            
            var container = document.getElementById('sessionsContainer');
            
            if (data.sessions.length === 0) {
                container.innerHTML = '<div class="alert alert-info text-center">' +
                    '<i class="bi bi-info-circle"></i> Tidak ada sesi ujian yang sedang berlangsung saat ini.' +
                    '</div>';
            } else {
                var html = '<div class="table-responsive"><table class="table table-striped">' +
                    '<thead class="table-dark">' +
                    '<tr><th>User</th><th>Exam</th><th>Started</th><th>End Time</th><th>Add Time</th></tr>' +
                    '</thead><tbody>';
                
                data.sessions.forEach(function(session) {
                    html += '<tr>' +
                        '<td>' + session.user_name + '</td>' +
                        '<td>' + session.exam_title + '</td>' +
                        '<td>' + session.start_time + '</td>' +
                        '<td>' + session.end_time + '</td>' +
                        '<td>' +
                        '<form action="<?= base_url('admin/add-extra-time') ?>" method="POST" class="d-flex">' +
                        '<input type="hidden" name="<?= csrf_token() ?>" value="' + csrfHash + '">' +
                        '<input type="hidden" name="session_id" value="' + session.id + '">' +
                        '<input type="number" name="extra_minutes" class="form-control form-control-sm me-2" min="1" max="60" value="10" style="width: 70px;">' +
                        '<button type="submit" class="btn btn-warning btn-sm"><i class="bi bi-clock"></i> Add</button>' +
                        '</form>' +
                        '</td>' +
                        '</tr>';
                });
                
                html += '</tbody></table></div>';
                container.innerHTML = html;
            }
            
            var now = new Date();
            document.getElementById('lastUpdate').textContent = 
                '(Update: ' + now.toLocaleTimeString('id-ID') + ')';
        }
    })
    .catch(error => {
        console.error('Polling error:', error);
    });
}

// ✅ Polling setiap 2 DETIK
setInterval(updateExtraTimeData, 2000);

// Initial load
document.addEventListener('DOMContentLoaded', function() {
    updateExtraTimeData();
});
</script>
<?= $this->endSection() ?>