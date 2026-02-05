<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - Sistem Data Mahasiswa</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="/css/style.css">
</head>
<body class="gradient-bg">

  <div class="login-container">
    <div class="glass-card login-card fade-in-up">
      <div class="login-header">
        <div class="login-logo">
          <i class="bi bi-key-fill"></i>
        </div>
        <h1>Reset Password</h1>
        <p>Masukkan username dan password baru</p>
      </div>

      <form id="formReset" class="login-form">
        <div class="form-group">
          <label class="form-label" for="username">Username</label>
          <input type="text" id="username" class="form-input" placeholder="Masukkan username" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="new_password">Password Baru</label>
          <input type="password" id="new_password" class="form-input" placeholder="Masukkan password baru" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="confirm_password">Konfirmasi Password</label>
          <input type="password" id="confirm_password" class="form-input" placeholder="Konfirmasi password baru" required>
        </div>

        <button type="submit" class="btn-modern btn-primary-modern login-submit" id="resetBtn">
          <span id="btnText">Reset Password</span>
          <span id="btnSpinner" style="display: none;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83">
                <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite" />
              </path>
            </svg>
          </span>
        </button>
      </form>

      <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
        <p style="color: var(--text-muted); font-size: 0.875rem;">
          Sudah ingat password? <a href="<?= base_url('login') ?>" style="color: var(--primary); text-decoration: none; font-weight: 500;">Kembali ke Login</a>
        </p>
      </div>
    </div>
  </div>

  <div class="toast" id="toast">
    <span id="toastIcon">✓</span>
    <span id="toastMessage">Pesan notifikasi</span>
  </div>

  <script>
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
      const btn = document.getElementById('resetBtn');
      const btnText = document.getElementById('btnText');
      const btnSpinner = document.getElementById('btnSpinner');
      btn.disabled = loading;
      btnText.style.display = loading ? 'none' : 'inline';
      btnSpinner.style.display = loading ? 'inline' : 'none';
    }

    document.getElementById('formReset').addEventListener('submit', async (e) => {
      e.preventDefault();
      setLoading(true);

      const username = document.getElementById('username').value;
      const newPassword = document.getElementById('new_password').value;
      const confirmPassword = document.getElementById('confirm_password').value;

      if (newPassword !== confirmPassword) {
        showToast('Password tidak cocok!', 'error');
        setLoading(false);
        return;
      }

      if (newPassword.length < 6) {
        showToast('Password minimal 6 karakter!', 'error');
        setLoading(false);
        return;
      }

      try {
        const res = await fetch('/api/auth/reset-password', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username, new_password: newPassword })
        });

        const data = await res.json();

        if (!res.ok) {
          showToast(data.message || 'Reset password gagal', 'error');
          setLoading(false);
          return;
        }

        showToast('Password berhasil direset! Mengalihkan ke login...', 'success');
        setTimeout(() => {
          window.location.href = '<?= base_url("login") ?>';
        }, 2000);

      } catch (err) {
        console.error('Network error:', err);
        showToast('Gagal koneksi ke server', 'error');
        setLoading(false);
      }
    });
  </script>
</body>
</html>
