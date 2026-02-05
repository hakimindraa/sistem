<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Mahasiswa - Sistem Akademik</title>
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

    <div class="search-bar">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="11" cy="11" r="8"></circle>
        <path d="m21 21-4.35-4.35"></path>
      </svg>
      <input type="text" id="searchInput" placeholder="Cari mahasiswa...">
    </div>

    <nav style="display: flex; align-items: center; gap: 0.75rem;">
      <a href="<?= base_url('dashboard') ?>" class="btn-modern btn-secondary-modern" style="padding: 0.5rem 1rem;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
          <polyline points="9 22 9 12 15 12 15 22"></polyline>
        </svg>
        Dashboard
      </a>
      <a href="<?= base_url('mahasiswa') ?>" class="btn-modern btn-primary-modern" style="padding: 0.5rem 1rem;">
        Data Mahasiswa
      </a>
      <button id="logoutBtn" class="btn-modern btn-secondary-modern">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
          <polyline points="16 17 21 12 16 7"></polyline>
          <line x1="21" y1="12" x2="9" y2="12"></line>
        </svg>
        Logout
      </button>
    </nav>
  </header>

  <!-- Main Content -->
  <main class="page-container">

    <!-- Page Header -->
    <div class="page-header fade-in-up">
      <div>
        <h1 class="page-title"><i class="bi bi-table"></i> Data Mahasiswa</h1>
        <p style="color: var(--text-secondary); margin-top: 0.25rem;">Daftar lengkap mahasiswa yang terdaftar dalam
          sistem</p>
      </div>
      <a href="<?= base_url('mahasiswa/create') ?>" class="btn-modern btn-primary-modern">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="12" y1="5" x2="12" y2="19"></line>
          <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Tambah Mahasiswa
      </a>
    </div>

    <!-- Info Bar -->
    <div
      style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;"
      class="fade-in-up delay-1">
      <p style="color: var(--text-secondary); font-size: 0.875rem;">
        Menampilkan <strong id="showingCount">0</strong> mahasiswa
      </p>
      <a href="<?= base_url('dashboard') ?>" style="color: var(--primary); text-decoration: none; font-size: 0.875rem;">
        ← Kembali ke Dashboard
      </a>
    </div>

    <!-- Data Table Card -->
    <div class="modern-card fade-in-up delay-2">
      <div class="modern-card-body" style="padding: 0; overflow: hidden;">
        <table class="modern-table">
          <thead>
            <tr>
              <th>NIM</th>
              <th>Nama Lengkap</th>
              <th>Angkatan</th>
              <th>Jurusan</th>
              <th style="text-align: center;">Aksi</th>
            </tr>
          </thead>
          <tbody id="dataMahasiswa">
            <tr>
              <td colspan="5" style="text-align: center; padding: 3rem;">
                <div class="spinner" style="margin: 0 auto;"></div>
                <p style="margin-top: 1rem; color: var(--text-muted);">Memuat data...</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- Toast Notification -->
  <div class="toast" id="toast">
    <span id="toastIcon">✓</span>
    <span id="toastMessage">Pesan notifikasi</span>
  </div>

  <script>
    // --- Helper Functions ---
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

    // Store all data for search
    let allMahasiswa = [];

    document.addEventListener('DOMContentLoaded', function () {
      // Check login
      const token = localStorage.getItem('token');
      if (!token) {
        window.location.href = '/login';
        return;
      }

      const logoutBtn = document.getElementById('logoutBtn');
      const searchInput = document.getElementById('searchInput');
      const apiBase = '/api';

      // Load data
      async function loadData() {
        try {
          const res = await fetch(`${apiBase}/mahasiswa`, {
            method: 'GET',
            headers: authHeaders()
          });

          if (!res.ok) {
            if (res.status === 401) {
              showToast('Sesi berakhir, silakan login ulang', 'error');
              localStorage.removeItem('token');
              setTimeout(() => window.location.href = '/login', 1500);
              return;
            }
            throw new Error('Failed to fetch');
          }

          const data = await res.json();
          allMahasiswa = Array.isArray(data) ? data : [];
          renderTable(allMahasiswa);

        } catch (err) {
          console.error('Fetch error:', err);
          document.getElementById("dataMahasiswa").innerHTML = `
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <div class="empty-state-icon">⚠️</div>
                            <h3>Gagal mengambil data</h3>
                            <p>Pastikan server berjalan dan coba lagi</p>
                        </div>
                    </td>
                </tr>
            `;
        }
      }

      function updateShowingCount(count) {
        document.getElementById('showingCount').textContent = count;
      }

      function renderTable(data) {
        updateShowingCount(data.length);

        if (data.length === 0) {
          document.getElementById("dataMahasiswa").innerHTML = `
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <div class="empty-state-icon">📋</div>
                            <h3>Belum ada data mahasiswa</h3>
                            <p>Klik tombol "Tambah Mahasiswa" untuk menambahkan data baru</p>
                        </div>
                    </td>
                </tr>
            `;
          return;
        }

        let html = '';
        data.forEach(m => {
          const nim = m.nim ?? '';
          const nama = m.nama ?? '';
          const angkatan = m.angkatan ?? '';
          const jurusan = m.jurusan ?? '';

          html += `
                <tr>
                    <td><strong>${nim}</strong></td>
                    <td>${nama}</td>
                    <td>${angkatan}</td>
                    <td>${jurusan}</td>
                    <td style="text-align: center;">
                        <div class="action-buttons" style="justify-content: center;">
                            <a href="<?= base_url('mahasiswa/edit/') ?>${nim}" class="btn-modern btn-warning-modern btn-icon" title="Edit">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </a>
                            <button onclick="hapus('${nim}')" class="btn-modern btn-danger-modern btn-icon" title="Hapus">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        document.getElementById("dataMahasiswa").innerHTML = html;
      }

      // Search functionality
      searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        const filtered = allMahasiswa.filter(m =>
          (m.nim && m.nim.toLowerCase().includes(query)) ||
          (m.nama && m.nama.toLowerCase().includes(query)) ||
          (m.jurusan && m.jurusan.toLowerCase().includes(query))
        );
        renderTable(filtered);
      });

      // Delete function
      window.hapus = async function (nim) {
        if (!confirm("Yakin ingin menghapus data ini?")) return;

        try {
          const res = await fetch(`/api/mahasiswa/${nim}`, {
            method: 'DELETE',
            headers: authHeaders()
          });

          if (!res.ok) {
            if (res.status === 401) {
              showToast('Sesi berakhir, login ulang', 'error');
              localStorage.removeItem('token');
              setTimeout(() => window.location.href = '/login', 1500);
              return;
            }
            throw new Error('Delete failed');
          }

          showToast('Data berhasil dihapus!', 'success');
          loadData();

        } catch (err) {
          console.error('Delete error:', err);
          showToast('Gagal menghapus data', 'error');
        }
      };

      // Logout
      if (logoutBtn) {
        logoutBtn.addEventListener('click', async function () {
          if (!confirm('Yakin ingin logout?')) return;

          const token = localStorage.getItem('token') || '';

          try {
            await fetch('/api/auth/logout', {
              method: 'POST',
              headers: authHeaders()
            });
          } catch (e) {
            console.warn('Logout request failed:', e);
          } finally {
            localStorage.removeItem('token');
            localStorage.removeItem('token_expires');
            showToast('Logout berhasil', 'success');
            setTimeout(() => window.location.href = '/login', 1000);
          }
        });
      }

      // Initial load
      loadData();
    });
  </script>

</body>

</html>