<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-1 fw-bold text-dark">
            <i class="bi bi-speedometer2 me-2 text-primary"></i>Admin Dashboard
        </h2>
        <p class="text-muted mb-0">Pantau aktivitas ujian dan pengguna secara real-time</p>
    </div>
    
</div>

<!-- 1. Summary Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card card border-0 h-100 shadow-sm">
            <div class="card-body d-flex align-items-center p-4">
                <div class="stat-icon stat-icon-primary me-3">
                    <i class="bi bi-journal-text"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-uppercase small fw-bold">Total Exams</h6>
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
                    <h6 class="text-muted mb-1 text-uppercase small fw-bold">Total Users</h6>
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
                    <h6 class="text-muted mb-1 text-uppercase small fw-bold">Total Questions</h6>
                    <h3 class="fw-bold mb-0 text-dark" id="statTotalQuestions"><?= $totalQuestions ?? 0 ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. Quick Actions & Period Stats -->
<div class="row g-4 mb-4">
    <!-- Quick Actions -->
    <div class="col-lg-4">
        <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-lightning-charge-fill me-2 text-warning"></i>Quick Actions</h5>
        <div class="row g-3">
            <div class="col-12">
                <a href="<?= base_url('admin/exams/create') ?>" class="action-card card border-0 text-decoration-none h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center p-3">
                        <!-- ✅ Ikon Putih dengan Gradient -->
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
                            <small class="text-muted">Lihat & edit daftar ujian</small>
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
                            <small class="text-muted">Beri extra time ke user</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Period Filter & Stats -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0 text-white"><i class="bi bi-calendar-range me-2 text-white"></i>Statistik Periode</h5>
            </div>
            <div class="card-body p-4">
                <!-- Filter Form -->
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

                <!-- Period Stats Grid (✅ Font Putih & Background Gradient) -->
                <div class="row g-3 text-center">
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 stat-box-primary">
                            <h4 class="fw-bold text-white mb-1" id="statTotalSessions"><?= $periodStats['total_sessions'] ?? 0 ?></h4>
                            <small class="text-white fw-semibold opacity-75">Total Sesi</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 stat-box-success">
                            <h4 class="fw-bold text-white mb-1" id="statCompleted"><?= $periodStats['completed'] ?? 0 ?></h4>
                            <small class="text-white fw-semibold opacity-75">Selesai</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 stat-box-warning">
                            <h4 class="fw-bold text-white mb-1" id="statOngoing"><?= $periodStats['ongoing'] ?? 0 ?></h4>
                            <small class="text-white fw-semibold opacity-75">Berlangsung</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
    <div class="p-3 rounded-3 stat-box-gradient-danger p-4 rounded-4 h-100 text-white shadow-sm position-relative">
        <i class="bi bi-x-circle-fill fs-2 opacity-75"></i>
        <h3 class="fw-bold mt-2 mb-0" id="statExpired"><?= $periodStats['expired'] ?? 0 ?></h3>
        <small class="opacity-75 fw-semibold">Expired</small>
        
        <!-- ✅ KETERANGAN / LEGEND -->
        <div class="mt-2 pt-2 border-top border-white border-opacity-25">
            <small class="d-block" style="font-size: 0.7rem; opacity: 0.8;">
                <i class="bi bi-info-circle me-1"></i> Waktu habis tanpa klik selesai
            </small>
        </div>
    </div>
</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3. Registered Users Table -->
<div class="card border-0 shadow-sm mb-4">
    <!-- ✅ Header Putih dengan Gradient Hijau -->
    <div class="card-header header-gradient-success border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-white"><i class="bi bi-people-fill me-2"></i>Daftar User Terdaftar</h5>
        <span class="badge bg-white text-success rounded-pill" id="userCountBadge"><?= count($users ?? []) ?></span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 modern-table">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 small text-uppercase text-muted fw-bold">No</th>
                        <th class="py-3 small text-uppercase text-muted fw-bold">Nama & Email</th>
                        <th class="py-3 small text-uppercase text-muted fw-bold text-center">Ujian Diikuti</th>
                        <th class="py-3 small text-uppercase text-muted fw-bold text-center">Total Sesi</th>
                        <th class="pe-4 py-3 small text-uppercase text-muted fw-bold">Aktivitas Terakhir</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <?php if (empty($users)): ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada user terdaftar</td></tr>
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
                            <!-- ✅ Badge Font Putih -->
                            <td class="text-center"><span class="badge badge-gradient-primary text-white rounded-pill px-3"><?= $user['total_exams_taken'] ?? 0 ?></span></td>
                            <td class="text-center"><span class="badge badge-gradient-info text-white rounded-pill px-3"><?= $user['total_sessions'] ?? 0 ?></span></td>
                            <td class="text-muted small"><?= $user['last_activity'] ? date('d M Y, H:i', strtotime($user['last_activity'])) : '<span class="text-muted fst-italic">Belum ada</span>' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 4. Activity Log Table -->
<div class="card border-0 shadow-sm mb-4">
    <!-- ✅ Header Putih dengan Gradient Cyan -->
    <div class="card-header header-gradient-info border-0 pt-4 px-4">
        <h5 class="fw-bold mb-0 text-white"><i class="bi bi-clock-history me-2"></i>Log Aktivitas Ujian</h5>
        <small class="text-white opacity-75">Periode: <?= date('d M Y', strtotime($startDate)) ?> - <?= date('d M Y', strtotime($endDate)) ?></small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 modern-table">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 small text-uppercase text-muted fw-bold">User</th>
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
                            elseif ($session['status'] === 'expired') { $statusClass = 'danger'; $statusIcon = 'x-circle-fill'; }
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
                            <!-- ✅ Badge Status Font & Ikon Putih -->
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
    /* Dashboard Specific Styles */
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

    .action-card { transition: all 0.3s ease; border: 1px solid transparent !important; }
    .action-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; border-color: var(--primary-lighter) !important; }
    .action-icon { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; }

    /* ✅ Gradient Backgrounds untuk Quick Actions */
    .action-icon-gradient-primary { background: linear-gradient(135deg, #3264B9 0%, #1E95E0 100%); }
    .action-icon-gradient-success { background: linear-gradient(135deg, #059669 0%, #10B981 100%); }
    .action-icon-gradient-warning { background: linear-gradient(135deg, #D97706 0%, #F59E0B 100%); }

    /* ✅ Gradient Backgrounds untuk Period Stats */
    .stat-box-primary { background: linear-gradient(135deg, #3264B9 0%, #1E95E0 100%); }
    .stat-box-success { background: linear-gradient(135deg, #059669 0%, #10B981 100%); }
    .stat-box-warning { background: linear-gradient(135deg, #D97706 0%, #F59E0B 100%); }
    .stat-box-danger { background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%); }

    /* ✅ Gradient Backgrounds untuk Table Headers */
    .header-gradient-success { background: linear-gradient(135deg, #059669 0%, #10B981 100%) !important; border-radius: 16px 16px 0 0 !important; }
    .header-gradient-info { background: linear-gradient(135deg, #0891B2 0%, #06B6D4 100%) !important; border-radius: 16px 16px 0 0 !important; }

    /* ✅ Gradient Backgrounds untuk Badges (Font Putih) */
    .badge-gradient-primary { background: linear-gradient(135deg, #3264B9 0%, #1E95E0 100%); }
    .badge-gradient-info { background: linear-gradient(135deg, #0891B2 0%, #06B6D4 100%); }
    .badge-gradient-success { background: linear-gradient(135deg, #059669 0%, #10B981 100%); }
    .badge-gradient-warning { background: linear-gradient(135deg, #D97706 0%, #F59E0B 100%); }
    .badge-gradient-danger { background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%); }
    .badge-gradient-secondary { background: linear-gradient(135deg, #4B5563 0%, #1F2937 100%); }

    .avatar-circle {
        width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; font-size: 1.1rem;
    }

    .modern-table thead th { border-bottom: 2px solid var(--gray-200); }
    .modern-table tbody tr { transition: background-color 0.2s; }
    .modern-table tbody tr:hover { background-color: var(--primary-lighter) !important; }
    
    /* Re-use stat styles from Exams/Index */
    .stat-card { transition: transform 0.3s ease; }
    .stat-card:hover { transform: translateY(-5px); }
    .stat-icon { width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .stat-icon-primary { background: linear-gradient(135deg, #3264B9 0%, #1E95E0 100%); }
    .stat-icon-success { background: linear-gradient(135deg, #059669 0%, #10B981 100%); }
    .stat-icon-info { background: linear-gradient(135deg, #0891B2 0%, #06B6D4 100%); }
</style>

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
            
            // Update Stats
            document.getElementById('statTotalExams').textContent = data.stats.totalExams;
            document.getElementById('statTotalUsers').textContent = data.stats.totalUsers;
            document.getElementById('statTotalQuestions').textContent = data.stats.totalQuestions;
            document.getElementById('statTotalSessions').textContent = data.periodStats.total_sessions;
            document.getElementById('statCompleted').textContent = data.periodStats.completed;
            document.getElementById('statOngoing').textContent = data.periodStats.ongoing;
            document.getElementById('statExpired').textContent = data.periodStats.expired;
            
            // Update Users Table
            var usersHtml = '';
            if (data.users.length === 0) {
                usersHtml = '<tr><td colspan="5" class="text-center py-4 text-muted">Belum ada user terdaftar</td></tr>';
            } else {
                data.users.forEach(function(user, i) {
                    var initial = user.name.charAt(0).toUpperCase();
                    var lastAct = user.last_activity !== 'Belum ada aktivitas' ? user.last_activity : '<span class="text-muted fst-italic">Belum ada</span>';
                    
                    usersHtml += '<tr>' +
                        '<td class="ps-4 fw-bold text-muted">' + (i + 1) + '</td>' +
                        '<td><div class="d-flex align-items-center"><div class="avatar-circle bg-primary text-white me-3">' + initial + '</div><div><div class="fw-bold text-dark">' + user.name + '</div><small class="text-muted">' + user.email + '</small></div></div></td>' +
                        // ✅ Badge Font Putih
                        '<td class="text-center"><span class="badge badge-gradient-primary text-white rounded-pill px-3">' + user.total_exams_taken + '</span></td>' +
                        '<td class="text-center"><span class="badge badge-gradient-info text-white rounded-pill px-3">' + user.total_sessions + '</span></td>' +
                        '<td class="text-muted small">' + lastAct + '</td>' +
                        '</tr>';
                });
            }
            document.getElementById('usersTableBody').innerHTML = usersHtml;
            document.getElementById('userCountBadge').textContent = data.users.length;
            
            // Update Sessions Table
            var sessionsHtml = '';
            if (data.examSessions.length === 0) {
                sessionsHtml = '<tr><td colspan="5" class="text-center py-4 text-muted">Tidak ada aktivitas pada periode ini</td></tr>';
            } else {
                data.examSessions.forEach(function(session) {
                    // Tentukan gradient class berdasarkan status
                    var gradientClass = 'secondary';
                    var statusIcon = 'dash-circle';
                    if (session.status === 'Finished') { gradientClass = 'success'; statusIcon = 'check-circle-fill'; }
                    else if (session.status === 'Ongoing') { gradientClass = 'warning'; statusIcon = 'arrow-repeat'; }
                    else if (session.status === 'Expired') { gradientClass = 'danger'; statusIcon = 'x-circle-fill'; }

                    sessionsHtml += '<tr>' +
                        '<td class="ps-4"><div class="fw-bold text-dark">' + session.user_name + '</div><small class="text-muted">' + session.user_email + '</small></td>' +
                        '<td><span class="fw-semibold">' + session.exam_title + '</span></td>' +
                        '<td><div class="small text-dark">' + session.start_time.split(' ')[0] + '</div><div class="small text-muted">' + (session.start_time.split(' ')[1] || '') + ' - ' + (session.end_time !== '-' ? session.end_time.split(' ')[1] : '-') + '</div></td>' +
                        // ✅ Badge Status Font & Ikon Putih
                        '<td class="text-center"><span class="badge badge-gradient-' + gradientClass + ' text-white rounded-pill px-3"><i class="bi bi-' + statusIcon + ' me-1"></i> ' + session.status + '</span></td>' +
                        '<td class="text-end pe-4 fw-bold text-muted">' + session.duration + '</td>' +
                        '</tr>';
                });
            }
            document.getElementById('sessionsTableBody').innerHTML = sessionsHtml;
            
            // Update Timestamp
            var now = new Date();
            document.getElementById('lastUpdate').textContent = 'Live: ' + now.toLocaleTimeString('id-ID');
        }
    })
    .catch(error => console.error('Polling error:', error));
}

setInterval(updateDashboard, 2000);
document.addEventListener('DOMContentLoaded', updateDashboard);
</script>
<?= $this->endSection() ?>