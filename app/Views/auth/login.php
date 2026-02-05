<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Sistem Data Mahasiswa</title>
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
          <i class="bi bi-mortarboard-fill"></i>
        </div>
        <h1>Selamat Datang</h1>
        <p>Masuk ke Sistem Data Mahasiswa</p>
      </div>

      <form id="formLogin" class="login-form">
        <div class="form-group">
          <label class="form-label" for="username">Username</label>
          <input type="text" id="username" class="form-input" placeholder="Masukkan username" required
            autocomplete="username">
        </div>

        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <input type="password" id="password" class="form-input" placeholder="Masukkan password" required
            autocomplete="current-password">
        </div>

        <button type="submit" class="btn-modern btn-primary-modern login-submit" id="loginBtn">
          <span id="btnText">Masuk</span>
          <span id="btnSpinner" style="display: none;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path
                d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83">
                <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s"
                  repeatCount="indefinite" />
              </path>
            </svg>
          </span>
        </button>
      </form>

      <div
        style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
        <p style="color: var(--text-muted); font-size: 0.875rem;">
          Belum punya akun? <a href="#" style="color: var(--primary); text-decoration: none; font-weight: 500;">Hubungi
            Admin</a>
        </p>
      </div>
    </div>
  </div>

  <!-- Toast Notification -->
  <div class="toast" id="toast">
    <span id="toastIcon">✓</span>
    <span id="toastMessage">Pesan notifikasi</span>
  </div>

  <script>
    // Toast helper
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

    // Loading state
    function setLoading(loading) {
      const btn = document.getElementById('loginBtn');
      const btnText = document.getElementById('btnText');
      const btnSpinner = document.getElementById('btnSpinner');

      btn.disabled = loading;
      btnText.style.display = loading ? 'none' : 'inline';
      btnSpinner.style.display = loading ? 'inline' : 'none';
    }

    // Form submit
    document.getElementById('formLogin').addEventListener('submit', async (e) => {
      e.preventDefault();
      setLoading(true);

      const body = {
        username: document.getElementById('username').value,
        password: document.getElementById('password').value
      };

      try {
        const res = await fetch('/api/auth/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(body)
        });

        const data = await res.json();

        if (!res.ok) {
          showToast(data.message || 'Login gagal', 'error');
          setLoading(false);
          return;
        }

        if (!data.access_token) {
          console.error('Response login tidak mengandung token:', data);
          showToast('Token tidak ditemukan', 'error');
          setLoading(false);
          return;
        }

        // Store token
        localStorage.setItem('token', data.access_token);
        localStorage.setItem('token_expires', (Date.now() + (data.expires_in || 3600) * 1000).toString());

        showToast('Login berhasil! Mengalihkan...', 'success');

        // Redirect after short delay
        setTimeout(() => {
          window.location.href = '<?= base_url("dashboard") ?>';
        }, 1000);

      } catch (err) {
        console.error('Network error:', err);
        showToast('Gagal koneksi ke server', 'error');
        setLoading(false);
      }
    });
  </script>

</body>

</html>