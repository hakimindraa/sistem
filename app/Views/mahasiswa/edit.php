<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa - Sistem Akademik</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

    <!-- Header -->
    <header class="modern-header">
        <a href="<?= base_url('dashboard') ?>" class="logo">
            <div class="logo-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <span>Sistem Akademik</span>
        </a>

        <nav style="display: flex; align-items: center; gap: 0.5rem;">
            <a href="<?= base_url('mahasiswa') ?>" class="btn-modern btn-secondary-modern">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali
            </a>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="page-container" style="max-width: 600px;">

        <div class="page-header fade-in-up">
            <h1 class="page-title">Edit Mahasiswa</h1>
        </div>

        <div class="modern-card fade-in-up delay-1">
            <div class="modern-card-header" style="background: linear-gradient(135deg, var(--warning), #ea580c);">
                <h4><i class="bi bi-pencil-square"></i> Edit Data Mahasiswa</h4>
            </div>

            <div class="modern-card-body">
                <form id="formEdit">
                    <div class="form-floating-custom">
                        <label for="nim">NIM (tidak dapat diubah)</label>
                        <input type="text" id="nim" class="form-input" readonly
                            style="background: var(--bg-body); cursor: not-allowed;">
                    </div>

                    <div class="form-floating-custom">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" class="form-input" placeholder="Masukkan nama lengkap" required>
                    </div>

                    <div class="form-floating-custom">
                        <label for="angkatan">Tahun Angkatan</label>
                        <input type="number" id="angkatan" class="form-input" placeholder="Contoh: 2024" min="2000"
                            max="2099" required>
                    </div>

                    <div class="form-floating-custom">
                        <label for="jurusan">Jurusan / Program Studi</label>
                        <input type="text" id="jurusan" class="form-input" placeholder="Contoh: Teknik Informatika"
                            required>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                        <a href="<?= base_url('mahasiswa') ?>" class="btn-modern btn-secondary-modern"
                            style="flex: 1; text-align: center;">
                            Batal
                        </a>
                        <button type="submit" class="btn-modern btn-warning-modern" style="flex: 2;" id="submitBtn">
                            <span id="btnText">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                Update Data
                            </span>
                            <span id="btnSpinner" style="display: none;">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <span id="toastIcon">✓</span>
        <span id="toastMessage">Pesan notifikasi</span>
    </div>

    <script>
        const nimUrl = "<?= $nim ?>";

        function authHeaders() {
            const token = localStorage.getItem('token') || '';
            return {
                'Content-Type': 'application/json',
                ...(token ? { 'Authorization': 'Bearer ' + token } : {})
            };
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            const toastIcon = document.getElementById('toastIcon');

            toast.className = 'toast ' + type;
            toastMessage.textContent = message;
            toastIcon.textContent = type === 'success' ? '✓' : type === 'error' ? '✕' : '⚠';

            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        function setLoading(loading) {
            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            btn.disabled = loading;
            btnText.style.display = loading ? 'none' : 'inline-flex';
            btnSpinner.style.display = loading ? 'inline' : 'none';
        }

        async function loadData() {
            try {
                const res = await fetch(`/api/mahasiswa/${nimUrl}`, {
                    method: 'GET',
                    headers: authHeaders()
                });

                if (!res.ok) {
                    if (res.status === 401) {
                        showToast('Sesi berakhir, silakan login ulang', 'error');
                        setTimeout(() => window.location.href = '/login', 1500);
                        return;
                    }
                    showToast('Gagal mengambil data mahasiswa', 'error');
                    return;
                }

                const m = await res.json();
                document.getElementById("nim").value = m.nim ?? '';
                document.getElementById("nama").value = m.nama ?? '';
                document.getElementById("angkatan").value = m.angkatan ?? '';
                document.getElementById("jurusan").value = m.jurusan ?? '';

            } catch (err) {
                console.error('Load error:', err);
                showToast('Gagal koneksi ke server', 'error');
            }
        }

        document.getElementById("formEdit").addEventListener("submit", async function (e) {
            e.preventDefault();
            setLoading(true);

            const payload = {
                nama: document.getElementById("nama").value,
                angkatan: document.getElementById("angkatan").value,
                jurusan: document.getElementById("jurusan").value
            };

            try {
                const res = await fetch(`/api/mahasiswa/${nimUrl}`, {
                    method: 'PUT',
                    headers: authHeaders(),
                    body: JSON.stringify(payload)
                });

                if (!res.ok) {
                    if (res.status === 401) {
                        showToast('Sesi berakhir, silakan login ulang', 'error');
                        localStorage.removeItem('token');
                        setTimeout(() => window.location.href = '/login', 1500);
                        return;
                    }
                    const data = await res.json();
                    showToast(data.message || 'Gagal update data', 'error');
                    setLoading(false);
                    return;
                }

                showToast('Data berhasil diperbarui!', 'success');
                setTimeout(() => {
                    window.location.href = "<?= base_url('mahasiswa') ?>";
                }, 1000);

            } catch (err) {
                console.error('Update error:', err);
                showToast('Gagal koneksi ke server', 'error');
                setLoading(false);
            }
        });

        // Check login & load data
        document.addEventListener('DOMContentLoaded', function () {
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = '/login';
                return;
            }
            loadData();
        });
    </script>

</body>

</html>