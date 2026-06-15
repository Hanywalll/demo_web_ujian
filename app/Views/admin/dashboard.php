<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-1 fw-bold text-dark">
            <i class="bi bi-speedometer2 me-2 text-primary"></i>Dasbor Admin
        </h2>
        <p class="text-muted mb-0">Pantau aktivitas ujian dan pengguna secara langsung</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card card border-0 h-100 shadow-sm">
            <div class="card-body d-flex align-items-center p-4">
                <div class="stat-icon stat-icon-primary me-3">
                    <i class="bi bi-journal-text"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-uppercase small fw-bold">Total Ujian</h6>
                    <h3 class="fw-bold mb-0 text-dark" id="statTotalExams"><?= $totalExams ?? 0 ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card card border-0 h-100 shadow-sm">
            <div class="card-body d-flex align-items-center p-4">
                <div class="stat-icon stat-icon-success me-3">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-uppercase small fw-bold">Total Pengguna</h6>
                    <h3 class="fw-bold mb-0 text-dark" id="statTotalUsers"><?= $totalUsers ?? 0 ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card card border-0 h-100 shadow-sm">
            <div class="card-body d-flex align-items-center p-4">
                <div class="stat-icon stat-icon-info me-3">
                    <i class="bi bi-question-circle-fill"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-uppercase small fw-bold">Total Soal</h6>
                    <h3 class="fw-bold mb-0 text-dark" id="statTotalQuestions"><?= $totalQuestions ?? 0 ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-lightning-charge-fill me-2 text-warning"></i>Aksi Cepat</h5>
        <div class="row g-3">
            <div class="col-12">
                <a href="<?= base_url('admin/exams/create') ?>" class="action-card card border-0 text-decoration-none h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="action-icon action-icon-gradient-primary text-white rounded-3 p-2 me-3">
                            <i class="bi bi-plus-circle fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">Buat Ujian Baru</h6>
                            <small class="text-muted">Tambahkan ujian ke sistem</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12">
                <a href="<?= base_url('admin/exams') ?>" class="action-card card border-0 text-decoration-none h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="action-icon action-icon-gradient-success text-white rounded-3 p-2 me-3">
                            <i class="bi bi-list-ul fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">Kelola Ujian</h6>
                            <small class="text-muted">Lihat dan edit daftar ujian</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12">
                <a href="<?= base_url('admin/add-extra-time') ?>" class="action-card card border-0 text-decoration-none h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="action-icon action-icon-gradient-warning text-white rounded-3 p-2 me-3">
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">Tambah Waktu</h6>
                            <small class="text-muted">Berikan tambahan waktu ke peserta</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0 text-white"><i class="bi bi-calendar-range me-2 text-white"></i>Statistik Periode</h5>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="<?= base_url('admin') ?>" class="row g-3 mb-4 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted text-uppercase">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="<?= esc($startDate) ?>" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted text-uppercase">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control" value="<?= esc($endDate) ?>" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <div class="row g-3 text-center">
                    <div class="col-6 col-md-4">
                        <div class="p-3 rounded-3 stat-box-primary">
                            <h4 class="fw-bold text-white mb-1" id="statTotalSessions"><?= $periodStats['total_sessions'] ?? 0 ?></h4>
                            <small class="text-white fw-semibold opacity-75">Total Sesi</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="p-3 rounded-3 stat-box-success">
                            <h4 class="fw-bold text-white mb-1" id="statCompleted"><?= $periodStats['completed'] ?? 0 ?></h4>
                            <small class="text-white fw-semibold opacity-75">Selesai</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="p-3 rounded-3 stat-box-warning">
                            <h4 class="fw-bold text-white mb-1" id="statOngoing"><?= $periodStats['ongoing'] ?? 0 ?></h4>
                            <small class="text-white fw-semibold opacity-75">Berlangsung</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header header-gradient-success border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-white"><i class="bi bi-people-fill me-2"></i>Daftar Peserta Terdaftar</h5>
        <span class="badge bg-white text-success rounded-pill" id="userCountBadge"><?= count($users ?? []) ?></span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 modern-table">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 small text-uppercase text-muted fw-bold">No</th>
                        <th class="py-3 small text-uppercase text-muted fw-bold">Nama dan Email</th>
                        <th class="py-3 small text-uppercase text-muted fw-bold text-center">Ujian Diikuti</th>
                        <th class="py-3 small text-uppercase text-muted fw-bold text-center">Total Sesi</th>
                        <th class="py-3 small text-uppercase text-muted fw-bold">Aktivitas Terakhir</th>
                        <th class="pe-4 py-3 small text-uppercase text-muted fw-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <?php if (empty($users)): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada peserta terdaftar</td></tr>
                    <?php else: ?>
                        <?php foreach ($users as $i => $user): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-muted"><?= $i + 1 ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary text-white me-3"><?= strtoupper(substr($user['name'], 0, 1)) ?></div>
                                    <div>
                                        <div class="fw-bold text-dark"><?= esc($user['name']) ?></div>
                                        <small class="text-muted"><?= esc($user['email']) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center"><span class="badge badge-gradient-primary text-white rounded-pill px-3"><?= $user['total_exams_taken'] ?? 0 ?></span></td>
                            <td class="text-center"><span class="badge badge-gradient-info text-white rounded-pill px-3"><?= $user['total_sessions'] ?? 0 ?></span></td>
                            <td class="text-muted small"><?= $user['last_activity'] ? date('d M Y, H:i', strtotime($user['last_activity'])) : '<span class="text-muted fst-italic">Belum ada</span>' ?></td>
                            <td class="text-center pe-4">
                                <button class="btn btn-sm btn-info text-white btn-detail-user" 
                                        data-user-id="<?= $user['id'] ?>" 
                                        data-user-name="<?= esc($user['name']) ?>"
                                        title="Lihat Riwayat Ujian">
                                    <i class="bi bi-eye me-1"></i>Detail
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header header-gradient-info border-0 pt-4 px-4">
        <h5 class="fw-bold mb-0 text-white"><i class="bi bi-clock-history me-2"></i>Log Aktivitas Ujian</h5>
        <small class="text-white opacity-75">Periode: <?= date('d M Y', strtotime($startDate)) ?> - <?= date('d M Y', strtotime($endDate)) ?></small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 modern-table">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 small text-uppercase text-muted fw-bold">Peserta</th>
                        <th class="py-3 small text-uppercase text-muted fw-bold">Ujian</th>
                        <th class="py-3 small text-uppercase text-muted fw-bold">Waktu</th>
                        <th class="py-3 small text-uppercase text-muted fw-bold text-center">Status</th>
                        <th class="pe-4 py-3 small text-uppercase text-muted fw-bold text-end">Durasi</th>
                    </tr>
                </thead>
                <tbody id="sessionsTableBody">
                    <?php if (empty($examSessions)): ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">Tidak ada aktivitas pada periode ini</td></tr>
                    <?php else: ?>
                        <?php foreach ($examSessions as $session): 
                            $statusClass = 'secondary'; $statusIcon = 'dash-circle';
                            if ($session['status'] === 'finished') { $statusClass = 'success'; $statusIcon = 'check-circle-fill'; }
                            elseif ($session['status'] === 'ongoing') { $statusClass = 'warning'; $statusIcon = 'arrow-repeat'; }
                        ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark"><?= esc($session['user_name']) ?></div>
                                <small class="text-muted"><?= esc($session['user_email']) ?></small>
                            </td>
                            <td><span class="fw-semibold"><?= esc($session['exam_title']) ?></span></td>
                            <td>
                                <div class="small text-dark"><?= date('d/m/Y', strtotime($session['start_time'])) ?></div>
                                <div class="small text-muted"><?= date('H:i', strtotime($session['start_time'])) ?> - <?= $session['end_time'] ? date('H:i', strtotime($session['end_time'])) : '-' ?></div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-gradient-<?= $statusClass ?> text-white rounded-pill px-3">
                                    <i class="bi bi-<?= $statusIcon ?> me-1"></i> <?= ucfirst($session['status']) ?>
                                </span>
                            </td>
                            <td class="text-end pe-4 fw-bold text-muted">
                                <?= $session['total_time_taken'] ? round($session['total_time_taken'], 1) . ' mnt' : '-' ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<style>
    .action-card { transition: all 0.3s ease; border: 1px solid transparent !important; }
    .action-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; border-color: var(--primary-lighter) !important; }
    .action-icon { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; }

    .action-icon-gradient-primary { background: linear-gradient(135deg, #3264B9 0%, #1E95E0 100%); }
    .action-icon-gradient-success { background: linear-gradient(135deg, #059669 0%, #10B981 100%); }
    .action-icon-gradient-warning { background: linear-gradient(135deg, #D97706 0%, #F59E0B 100%); }

    .stat-box-primary { background: linear-gradient(135deg, #3264B9 0%, #1E95E0 100%); }
    .stat-box-success { background: linear-gradient(135deg, #059669 0%, #10B981 100%); }
    .stat-box-warning { background: linear-gradient(135deg, #D97706 0%, #F59E0B 100%); }

    .header-gradient-success { background: linear-gradient(135deg, #059669 0%, #10B981 100%) !important; border-radius: 16px 16px 0 0 !important; }
    .header-gradient-info { background: linear-gradient(135deg, #0891B2 0%, #06B6D4 100%) !important; border-radius: 16px 16px 0 0 !important; }

    .badge-gradient-primary { background: linear-gradient(135deg, #3264B9 0%, #1E95E0 100%); }
    .badge-gradient-info { background: linear-gradient(135deg, #0891B2 0%, #06B6D4 100%); }
    .badge-gradient-success { background: linear-gradient(135deg, #059669 0%, #10B981 100%); }
    .badge-gradient-warning { background: linear-gradient(135deg, #D97706 0%, #F59E0B 100%); }
    .badge-gradient-secondary { background: linear-gradient(135deg, #4B5563 0%, #1F2937 100%); }

    .avatar-circle {
        width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; font-size: 1.1rem;
    }

    .modern-table thead th { border-bottom: 2px solid var(--gray-200); }
    .modern-table tbody tr { transition: background-color 0.2s; }
    .modern-table tbody tr:hover { background-color: var(--primary-lighter) !important; }
    
    .stat-card { transition: transform 0.3s ease; }
    .stat-card:hover { transform: translateY(-5px); }
    .stat-icon { width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .stat-icon-primary { background: linear-gradient(135deg, #3264B9 0%, #1E95E0 100%); }
    .stat-icon-success { background: linear-gradient(135deg, #059669 0%, #10B981 100%); }
    .stat-icon-info { background: linear-gradient(135deg, #0891B2 0%, #06B6D4 100%); }

    .stat-mini-card {
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        transition: transform 0.2s;
    }
    .stat-mini-card:hover {
        transform: translateY(-3px);
    }
    .stat-mini-value {
        font-size: 1.8rem;
        font-weight: 800;
        line-height: 1;
    }
    #userDetailModal .modal-header {
        border-radius: 0 !important;
        padding: 1.5rem !important;
    }

    #userDetailModal .modal-content {
        border-radius: 12px !important;
        overflow: hidden;
    }

    #userDetailModal .modal-body {
        padding: 2rem !important;
    }

    #userDetailModal .modal-dialog {
        max-width: 1200px !important;
        margin: 1rem auto !important;
    }

    @media (max-width: 768px) {
        #userDetailModal .modal-dialog {
            max-width: 100% !important;
            margin: 0 !important;
        }
        
        #userDetailModal .modal-content {
            border-radius: 0 !important;
            height: 100vh;
            height: -webkit-fill-available;
        }
        
        #userDetailModal .modal-header {
            padding: 1rem !important;
        }
        
        #userDetailModal .modal-body {
            padding: 1rem !important;
        }
    }
    .stat-mini-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 600;
        margin-top: 0.3rem;
    }
    .grade-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 1.2rem;
        color: white;
    }
    .grade-A { background: linear-gradient(135deg, #059669 0%, #10B981 100%); }
    .grade-B { background: linear-gradient(135deg, #0891B2 0%, #06B6D4 100%); }
    .grade-C { background: linear-gradient(135deg, #3264B9 0%, #1E95E0 100%); }
    .grade-D { background: linear-gradient(135deg, #D97706 0%, #F59E0B 100%); }
    .grade-E { background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%); }
</style>

<div class="modal fade" id="userDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header header-gradient-info border-0">
                <div class="d-flex align-items-center w-100">
                    <i class="bi bi-person-lines-fill fs-3 me-3 text-white"></i>
                    <div>
                        <h5 class="modal-title text-white fw-bold mb-1">
                            Riwayat Ujian Peserta
                        </h5>
                        <small class="text-white opacity-75" id="modalUserName">Nama Peserta</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body p-0" id="modalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-3 text-muted">Memuat data...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var startDate = '<?= $startDate ?>';
var endDate = '<?= $endDate ?>';
var csrfName = '<?= csrf_token() ?>';
var csrfHash = '<?= csrf_hash() ?>';

function updateDashboard() {
    var formData = new FormData();
    formData.append('start_date', startDate);
    formData.append('end_date', endDate);
    formData.append(csrfName, csrfHash);
    
    fetch('<?= base_url('admin/get-dashboard-data') ?>', {
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
            
            document.getElementById('statTotalExams').textContent = data.stats.totalExams;
            document.getElementById('statTotalUsers').textContent = data.stats.totalUsers;
            document.getElementById('statTotalQuestions').textContent = data.stats.totalQuestions;
            document.getElementById('statTotalSessions').textContent = data.periodStats.total_sessions;
            document.getElementById('statCompleted').textContent = data.periodStats.completed;
            document.getElementById('statOngoing').textContent = data.periodStats.ongoing;
            
            var usersHtml = '';
            if (data.users.length === 0) {
                usersHtml = '<tr><td colspan="6" class="text-center py-4 text-muted">Belum ada peserta terdaftar</td></tr>';
            } else {
                data.users.forEach(function(user, i) {
                    var initial = user.name.charAt(0).toUpperCase();
                    var lastAct = user.last_activity !== 'Belum ada aktivitas' ? user.last_activity : '<span class="text-muted fst-italic">Belum ada</span>';
                    
                    usersHtml += '<tr>' +
                        '<td class="ps-4 fw-bold text-muted">' + (i + 1) + '</td>' +
                        '<td><div class="d-flex align-items-center"><div class="avatar-circle bg-primary text-white me-3">' + initial + '</div><div><div class="fw-bold text-dark">' + user.name + '</div><small class="text-muted">' + user.email + '</small></div></div></td>' +
                        '<td class="text-center"><span class="badge badge-gradient-primary text-white rounded-pill px-3">' + user.total_exams_taken + '</span></td>' +
                        '<td class="text-center"><span class="badge badge-gradient-info text-white rounded-pill px-3">' + user.total_sessions + '</span></td>' +
                        '<td class="text-muted small">' + lastAct + '</td>' +
                        '<td class="text-center pe-4"><button class="btn btn-sm btn-info text-white btn-detail-user" data-user-id="' + user.id + '" data-user-name="' + user.name + '" title="Lihat Riwayat Ujian"><i class="bi bi-eye me-1"></i>Detail</button></td>' +
                        '</tr>';
                });
            }
            document.getElementById('usersTableBody').innerHTML = usersHtml;
            document.getElementById('userCountBadge').textContent = data.users.length;
            
            attachDetailButtons();
            
            var sessionsHtml = '';
            if (data.examSessions.length === 0) {
                sessionsHtml = '<tr><td colspan="5" class="text-center py-4 text-muted">Tidak ada aktivitas pada periode ini</td></tr>';
            } else {
                data.examSessions.forEach(function(session) {
                    var gradientClass = 'secondary';
                    var statusIcon = 'dash-circle';
                    if (session.status === 'Finished') { gradientClass = 'success'; statusIcon = 'check-circle-fill'; }
                    else if (session.status === 'Ongoing') { gradientClass = 'warning'; statusIcon = 'arrow-repeat'; }

                    var startTimeParts = session.start_time.split(' ');
                    var endTimeParts = session.end_time !== '-' ? session.end_time.split(' ') : ['-', '-'];

                    sessionsHtml += '<tr>' +
                        '<td class="ps-4"><div class="fw-bold text-dark">' + session.user_name + '</div><small class="text-muted">' + session.user_email + '</small></td>' +
                        '<td><span class="fw-semibold">' + session.exam_title + '</span></td>' +
                        '<td><div class="small text-dark">' + (startTimeParts[0] || '-') + '</div><div class="small text-muted">' + (startTimeParts[1] || '') + ' - ' + (endTimeParts[1] || '-') + '</div></td>' +
                        '<td class="text-center"><span class="badge badge-gradient-' + gradientClass + ' text-white rounded-pill px-3"><i class="bi bi-' + statusIcon + ' me-1"></i> ' + session.status + '</span></td>' +
                        '<td class="text-end pe-4 fw-bold text-muted">' + session.duration + '</td>' +
                        '</tr>';
                });
            }
            document.getElementById('sessionsTableBody').innerHTML = sessionsHtml;
        }
    })
    .catch(error => console.error('Polling error:', error));
}

function attachDetailButtons() {
    document.querySelectorAll('.btn-detail-user').forEach(function(btn) {
        btn.onclick = null;
        
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var userId = this.getAttribute('data-user-id');
            var userName = this.getAttribute('data-user-name');
            
            if (!userId || userId === 'undefined') {
                console.error('userId tidak valid:', userId);
                alert('Error: ID pengguna tidak valid');
                return;
            }
            
            console.log('Memuat detail untuk user:', userId, userName);
            showUserDetail(userId, userName);
        });
    });
}

function showUserDetail(userId, userName) {
    if (!userId || userId === 'undefined') {
        console.error('userId tidak valid:', userId);
        return;
    }
    
    document.getElementById('modalUserName').textContent = userName || 'Peserta';
    document.getElementById('modalBody').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-3 text-muted">Memuat data...</p></div>';
    
    var modal = new bootstrap.Modal(document.getElementById('userDetailModal'));
    modal.show();
    
    var formData = new FormData();
    formData.append(csrfName, csrfHash);
    
    var url = '<?= base_url('admin/get-user-exam-history') ?>/' + userId;
    console.log('Fetching:', url);
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response:', data);
        if (data.success) {
            if (data.csrf_token) {
                csrfHash = data.csrf_token;
                document.querySelector('meta[name="csrf-token"]').content = data.csrf_token;
            }
            renderUserDetail(data);
        } else {
            document.getElementById('modalBody').innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i>' + (data.message || 'Gagal memuat data') + '</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('modalBody').innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i>Terjadi kesalahan: ' + error.message + '</div>';
    });
}

function renderUserDetail(data) {
    var stats = data.statistics;
    var history = data.history;
    
    var avgGrade = 'E';
    if (stats.average_score >= 90) avgGrade = 'A';
    else if (stats.average_score >= 80) avgGrade = 'B';
    else if (stats.average_score >= 70) avgGrade = 'C';
    else if (stats.average_score >= 60) avgGrade = 'D';
    
    var html = '<div class="p-4">';
    
    if (history.length === 0) {
        html += '<div class="text-center py-5">' +
            '<i class="bi bi-inbox" style="font-size: 4rem; color: var(--primary-light);"></i>' +
            '<h5 class="mt-3 text-muted">Peserta ini belum menyelesaikan ujian apapun</h5>' +
            '</div>';
    } else {
        html += '<div class="row g-3 mb-4">';
        
        html += '<div class="col-6 col-md-3">' +
            '<div class="stat-mini-card stat-box-primary text-white">' +
            '<div class="stat-mini-value">' + stats.total_exams + '</div>' +
            '<div class="stat-mini-label">Total Ujian</div>' +
            '</div></div>';
        
        html += '<div class="col-6 col-md-3">' +
            '<div class="stat-mini-card stat-box-success text-white">' +
            '<div class="stat-mini-value">' + stats.average_score + '</div>' +
            '<div class="stat-mini-label">Rata-rata Nilai</div>' +
            '</div></div>';
        
        html += '<div class="col-6 col-md-3">' +
            '<div class="stat-mini-card stat-box-warning text-white">' +
            '<div class="stat-mini-value">' + stats.highest_score + '</div>' +
            '<div class="stat-mini-label">Nilai Tertinggi</div>' +
            '</div></div>';
        
        html += '<div class="col-6 col-md-3">' +
            '<div class="stat-mini-card" style="background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%); color: white;">' +
            '<div class="stat-mini-value">' + stats.lowest_score + '</div>' +
            '<div class="stat-mini-label">Nilai Terendah</div>' +
            '</div></div>';
        
        html += '</div>';
        
        if (stats.best_exam && stats.best_exam !== '-') {
            html += '<div class="alert alert-info d-flex align-items-center mb-4">' +
                '<i class="bi bi-trophy-fill fs-3 me-3 text-warning"></i>' +
                '<div><strong>Ujian Terbaik:</strong> ' + stats.best_exam + ' dengan nilai <strong>' + stats.highest_score + '</strong></div>' +
                '</div>';
        }
        
        html += '<h6 class="fw-bold text-dark mb-3"><i class="bi bi-list-check me-2 text-primary"></i>Daftar Ujian yang Pernah Diikuti</h6>';
        
        html += '<div class="table-responsive"><table class="table table-hover align-middle mb-0 modern-table">' +
            '<thead class="bg-light"><tr>' +
            '<th class="ps-3 py-2 small text-uppercase text-muted fw-bold">No</th>' +
            '<th class="py-2 small text-uppercase text-muted fw-bold">Nama Ujian</th>' +
            '<th class="py-2 small text-uppercase text-muted fw-bold">Tanggal</th>' +
            '<th class="py-2 small text-uppercase text-muted fw-bold text-center">Waktu</th>' +
            '<th class="py-2 small text-uppercase text-muted fw-bold text-center">Benar</th>' +
            '<th class="py-2 small text-uppercase text-muted fw-bold text-center">Nilai</th>' +
            '<th class="pe-3 py-2 small text-uppercase text-muted fw-bold text-center">Grade</th>' +
            '</tr></thead><tbody>';
        
        history.forEach(function(exam, i) {
            var scoreColor = exam.score >= 70 ? 'text-success' : (exam.score >= 60 ? 'text-warning' : 'text-danger');
            
            html += '<tr>' +
                '<td class="ps-3 fw-bold text-muted">' + (i + 1) + '</td>' +
                '<td><div class="fw-bold text-dark">' + exam.exam_title + '</div>' +
                '<small class="text-muted">Durasi: ' + exam.duration + ' | Pengerjaan: ' + exam.time_taken + '</small></td>' +
                '<td><div class="small text-dark">' + exam.exam_date + '</div>' +
                '<small class="text-muted">' + exam.exam_time + '</small></td>' +
                '<td class="text-center"><span class="badge badge-gradient-info text-white rounded-pill px-3">' + exam.time_taken + '</span></td>' +
                '<td class="text-center"><span class="fw-bold">' + exam.correct_count + '/' + exam.total_questions + '</span></td>' +
                '<td class="text-center"><span class="fw-bold fs-5 ' + scoreColor + '">' + exam.score + '</span></td>' +
                '<td class="text-center pe-3"><span class="grade-badge grade-' + exam.grade + '">' + exam.grade + '</span></td>' +
                '</tr>';
        });
        
        html += '</tbody></table></div>';
    }
    
    html += '</div>';
    
    document.getElementById('modalBody').innerHTML = html;
}

setInterval(updateDashboard, 2000);
document.addEventListener('DOMContentLoaded', function() {
    updateDashboard();
    attachDetailButtons();
});
</script>
<?= $this->endSection() ?>