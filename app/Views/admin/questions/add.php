<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    <i class="bi bi-plus-circle"></i> Tambah Soal ke: <?= esc($exam['title']) ?>
                </h4>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/exams/' . $exam['id'] . '/questions/add') ?>" 
                      method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="question_text" class="form-label">Teks Soal *</label>
                        
                        <div class="btn-toolbar mb-2" role="toolbar">
                            <div class="btn-group me-2 mb-1" role="group" aria-label="Matematika Dasar">
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        onclick="insertMath('fraction')" title="Pecahan">
                                    <i>ᵃ⁄ᵦ</i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        onclick="insertMath('sqrt')" title="Akar">
                                    √
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        onclick="insertMath('power')" title="Pangkat">
                                    x²
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        onclick="insertMath('subscript')" title="Subscript">
                                    x₂
                                </button>
                            </div>
                            
                            <div class="btn-group me-2 mb-1" role="group" aria-label="Kalkulus">
                                <button type="button" class="btn btn-sm btn-outline-success" 
                                        onclick="insertMath('integral')" title="Integral">
                                    ∫
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-success" 
                                        onclick="insertMath('definite_integral')" title="Integral Tentu">
                                    ∫ₐᵇ
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-success" 
                                        onclick="insertMath('derivative')" title="Turunan">
                                    ∂
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-success" 
                                        onclick="insertMath('limit')" title="Limit">
                                    lim
                                </button>
                            </div>
                            
                            <div class="btn-group me-2 mb-1" role="group" aria-label="Sigma dan Produk">
                                <button type="button" class="btn btn-sm btn-outline-warning" 
                                        onclick="insertMath('sum')" title="Sigma (Jumlah)">
                                    Σ
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning" 
                                        onclick="insertMath('product')" title="Produk">
                                    Π
                                </button>
                            </div>
                            
                            <div class="btn-group me-2 mb-1" role="group" aria-label="Huruf Yunani">
                                <button type="button" class="btn btn-sm btn-outline-info" 
                                        onclick="insertMath('alpha')" title="Alpha">α</button>
                                <button type="button" class="btn btn-sm btn-outline-info" 
                                        onclick="insertMath('beta')" title="Beta">β</button>
                                <button type="button" class="btn btn-sm btn-outline-info" 
                                        onclick="insertMath('theta')" title="Theta">θ</button>
                                <button type="button" class="btn btn-sm btn-outline-info" 
                                        onclick="insertMath('pi')" title="Pi">π</button>
                                <button type="button" class="btn btn-sm btn-outline-info" 
                                        onclick="insertMath('sigma')" title="Sigma">σ</button>
                                <button type="button" class="btn btn-sm btn-outline-info" 
                                        onclick="insertMath('delta')" title="Delta">Δ</button>
                            </div>
                            
                            <div class="btn-group me-2 mb-1" role="group" aria-label="Simbol">
                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                        onclick="insertMath('infinity')" title="Tak Hingga">∞</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                        onclick="insertMath('pm')" title="Plus Minus">±</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                        onclick="insertMath('neq')" title="Tidak Sama Dengan">≠</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                        onclick="insertMath('leq')" title="Kurang Dari Sama Dengan">≤</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                        onclick="insertMath('geq')" title="Lebih Dari Sama Dengan">≥</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                        onclick="insertMath('approx')" title="Sekitar">≈</button>
                            </div>
                        </div>
                        
                        <textarea class="form-control" id="question_text" name="question_text" 
                                  rows="5" required placeholder="Tulis soal di sini..."><?= old('question_text') ?></textarea>
                        <small class="text-muted">
                            💡 Gunakan <code>$...$</code> untuk rumus inline atau <code>$$...$$</code> untuk rumus di tengah. 
                            Contoh: <code>$x^2 + y^2 = z^2$</code>
                        </small>
                        
                        <div class="mt-3">
                            <strong><i class="bi bi-eye"></i> Pratinjau:</strong>
                            <div id="mathPreview" class="border p-3 mt-1 bg-light" style="min-height: 60px;">
                                <em class="text-muted">Pratinjau akan muncul di sini...</em>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="question_image" class="form-label">Gambar Soal (Opsional)</label>
                        <input type="file" class="form-control" id="question_image" name="question_image" 
                               accept="image/jpeg,image/png,image/jpg">
                        <small class="text-muted">Ukuran maks: 2MB. Format: JPG, PNG</small>
                    </div>
                    
                    <hr>
                    <h5><i class="bi bi-list-ol"></i> Pilihan Jawaban</h5>
                    
                    <?php foreach (range('A', 'D') as $option): ?>
                    <div class="mb-3">
                        <label for="option_<?= $option ?>" class="form-label">
                            Pilihan <?= $option ?> *
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><strong><?= $option ?></strong></span>
                            <input type="text" class="form-control" id="option_<?= $option ?>" 
                                   name="option_<?= $option ?>" value="<?= old('option_' . $option) ?>" required>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <div class="mb-3">
                        <label for="correct_answer" class="form-label">Jawaban Benar *</label>
                        <select class="form-control" id="correct_answer" name="correct_answer" required>
                            <option value="">Pilih jawaban benar</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-save"></i> Simpan Soal
                        </button>
                        <a href="<?= base_url('admin/exams/' . $exam['id'] . '/questions') ?>" 
                           class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
var mathTemplates = {
    'fraction': '\\frac{a}{b}',
    'sqrt': '\\sqrt{x}',
    'power': 'x^{2}',
    'subscript': 'x_{i}',
    'integral': '\\int f(x) \\, dx',
    'definite_integral': '\\int_{a}^{b} f(x) \\, dx',
    'derivative': '\\frac{d}{dx} f(x)',
    'limit': '\\lim_{x \\to a} f(x)',
    'sum': '\\sum_{i=1}^{n} x_i',
    'product': '\\prod_{i=1}^{n} x_i',
    'alpha': '\\alpha',
    'beta': '\\beta',
    'theta': '\\theta',
    'pi': '\\pi',
    'sigma': '\\sigma',
    'delta': '\\Delta',
    'infinity': '\\infty',
    'pm': '\\pm',
    'neq': '\\neq',
    'leq': '\\leq',
    'geq': '\\geq',
    'approx': '\\approx'
};

function insertMath(type) {
    var textarea = document.getElementById('question_text');
    var template = mathTemplates[type];
    
    if (!template) {
        console.error('Template tidak ditemukan:', type);
        return;
    }
    
    var cursorPos = textarea.selectionStart;
    var endPos = textarea.selectionEnd;
    var text = textarea.value;
    var beforeCursor = text.slice(0, cursorPos);
    var afterCursor = text.slice(endPos);
    var insertion = '$' + template + '$';
    
    textarea.value = beforeCursor + insertion + afterCursor;
    
    var newCursorPos = cursorPos + insertion.length;
    textarea.setSelectionRange(newCursorPos, newCursorPos);
    textarea.focus();
    
    updatePreview();
}

function updatePreview() {
    var textarea = document.getElementById('question_text');
    var preview = document.getElementById('mathPreview');
    var text = textarea.value;
    
    if (!text.trim()) {
        preview.innerHTML = '<em class="text-muted">Pratinjau akan muncul di sini...</em>';
        return;
    }
    
    preview.innerHTML = text.replace(/</g, '&lt;').replace(/>/g, '&gt;');
    
    if (window.MathJax && window.MathJax.typesetPromise) {
        MathJax.typesetPromise([preview]).catch((err) => {
            console.log('MathJax error:', err);
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var textarea = document.getElementById('question_text');
    
    textarea.addEventListener('input', updatePreview);
    
    textarea.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            e.preventDefault();
            var start = this.selectionStart;
            var end = this.selectionEnd;
            this.value = this.value.substring(0, start) + '    ' + this.value.substring(end);
            this.selectionStart = this.selectionEnd = start + 4;
        }
    });
    
    if (textarea.value.trim()) {
        updatePreview();
    }
});
</script>
<?= $this->endSection() ?>