<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-success text-white text-center">
                <h3 class="mb-0">
                    <i class="bi bi-person-plus"></i> Daftar
                </h3>
            </div>
            <div class="card-body p-4">
                <form action="<?= base_url('register') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="name" name="name" 
                                   placeholder="Masukkan nama lengkap" value="<?= old('name') ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="Masukkan email Anda" value="<?= old('email') ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Minimal 6 karakter" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Konfirmasi Kata Sandi</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control" id="confirm_password" 
                                   name="confirm_password" placeholder="Konfirmasi kata sandi" required>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-person-plus"></i> Daftar
                        </button>
                    </div>
                </form>
                
                <hr>
                
                <div class="text-center">
                    <p>Sudah punya akun?</p>
                    <a href="<?= base_url('login') ?>" class="btn btn-outline-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Masuk Di Sini
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>