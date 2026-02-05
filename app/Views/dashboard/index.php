<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Akademik</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .quick-action-card {
            background: var(--bg-surface);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            text-align: center;
            transition: all 0.2s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .quick-action-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        .quick-action-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 1rem;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
        }

        .quick-action-card h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .quick-action-card p {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .welcome-banner {
            background: linear-gradient(135deg, var(--primary), var(--bg-gradient-end));
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .welcome-banner h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-banner p {
            opacity: 0.9;
        }

        .recent-activity {
            background: var(--bg-surface);
            border-radius: var(--border-radius);
            border: 1px solid var(--border-color);
        }

        .recent-activity-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .recent-activity-header h3 {
            font-weight: 600;
        }

        .recent-activity-list {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .recent-activity-list li {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .recent-activity-list li:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .activity-info strong {
            display: block;
            font-weight: 500;
        }

        .activity-info span {
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .two-column {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
        }

        @media (max-width: 900px) {
            .two-column {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header class="modern-header">
        <a href="<?= base_url('dashboard') ?>" class="logo">
            <div class="logo-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <span>Sistem Akademik</span>
        </a>

        <nav style="display: flex; align-items: center; gap: 1rem;">
            <a href="<?= base_url('dashboard') ?>" class="btn-modern btn-primary-modern" style="padding: 0.5rem 1rem;">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
            <a href="<?= base_url('mahasiswa') ?>" class="btn-modern btn-secondary-modern"
                style="padding: 0.5rem 1rem;">
                <i class="bi bi-people-fill"></i> Data Mahasiswa
            </a>
            <button id="logoutBtn" class="btn-modern btn-secondary-modern">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="page-container">

        <!-- Welcome Banner -->
        <div class="welcome-banner fade-in-up">
            <div>
                <h1><i class="bi bi-hand-wave"></i> Selamat Datang!</h1>
                <p>Dashboard Sistem Manajemen Data Mahasiswa</p>
            </div>
            <div id="currentDate" style="text-align: right; opacity: 0.9;"></div>
        </div>

        <!-- Statistics Cards -->
        <div class="stat-cards fade-in-up delay-1">
            <div class="stat-card">
                <div class="stat-card-icon primary"><i class="bi bi-people-fill"></i></div>
                <div class="stat-card-info">
                    <h3 id="totalMahasiswa">0</h3>
                    <p>Total Mahasiswa</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon success"><i class="bi bi-calendar-check"></i></div>
                <div class="stat-card-info">
                    <h3 id="angkatanTerbaru">-</h3>
                    <p>Angkatan Terbaru</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon warning"><i class="bi bi-mortarboard"></i></div>
                <div class="stat-card-info">
                    <h3 id="totalJurusan">0</h3>
                    <p>Jurusan</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;" class="fade-in-up delay-2">
            <i class="bi bi-lightning-charge-fill" style="color: var(--warning);"></i> Menu Cepat
        </h2>
        <div class="dashboard-grid fade-in-up delay-2">
            <a href="<?= base_url('mahasiswa') ?>" class="quick-action-card">
                <div class="quick-action-icon" style="background: var(--primary-light); color: var(--primary);"><i
                        class="bi bi-table"></i></div>
                <h3>Lihat Data Mahasiswa</h3>
                <p>Lihat daftar lengkap semua mahasiswa yang terdaftar</p>
            </a>

            <a href="<?= base_url('mahasiswa/create') ?>" class="quick-action-card">
                <div class="quick-action-icon" style="background: #d1fae5; color: var(--secondary);"><i
                        class="bi bi-person-plus-fill"></i></div>
                <h3>Tambah Mahasiswa</h3>
                <p>Daftarkan mahasiswa baru ke dalam sistem</p>
            </a>

            <a href="<?= base_url('mahasiswa') ?>" class="quick-action-card">
                <div class="quick-action-icon" style="background: #fef3c7; color: var(--warning);"><i
                        class="bi bi-search"></i></div>
                <h3>Cari Mahasiswa</h3>
                <p>Cari data mahasiswa berdasarkan NIM atau nama</p>
            </a>
        </div>

        <!-- Two Column Layout -->
        <div class="two-column fade-in-up delay-3">
            <!-- Recent Students -->
            <div class="recent-activity">
                <div class="recent-activity-header">
                    <h3><i class="bi bi-clock-history"></i> Mahasiswa Terbaru</h3>
                    <a href="<?= base_url('mahasiswa') ?>"
                        style="color: var(--primary); text-decoration: none; font-size: 0.875rem;">Lihat Semua <i
                            class="bi bi-arrow-right"></i></a>
                </div>
                <ul class="recent-activity-list" id="recentList">
                    <li style="justify-content: center; padding: 2rem;">
                        <div class="spinner"></div>
                    </li>
                </ul>
            </div>

            <!-- Info Panel -->
            <div class="recent-activity">
                <div class="recent-activity-header">
                    <h3><i class="bi bi-info-circle"></i> Informasi Sistem</h3>
                </div>
                <ul class="recent-activity-list">
                    <li>
                        <div class="activity-icon" style="background: var(--primary-light); color: var(--primary);"><i
                                class="bi bi-shield-lock-fill"></i></div>
                        <div class="activity-info">
                            <strong>JWT Authentication</strong>
                            <span>Sistem menggunakan token untuk keamanan</span>
                        </div>
                    </li>
                    <li>
                        <div class="activity-icon" style="background: #d1fae5; color: var(--secondary);"><i
                                class="bi bi-rocket-takeoff-fill"></i></div>
                        <div class="activity-info">
                            <strong>CodeIgniter 4</strong>
                            <span>Framework PHP modern</span>
                        </div>
                    </li>
                    <li>
                        <div class="activity-icon" style="background: #fef3c7; color: var(--warning);"><i
                                class="bi bi-database-fill"></i></div>
                        <div class="activity-info">
                            <strong>REST API</strong>
                            <span>Endpoint API untuk integrasi</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </main>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <span id="toastIcon"><i class="bi bi-check-circle-fill"></i></span>
        <span id="toastMessage">Pesan notifikasi</span>
    </div>

    <script>
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

            const icons = {
                success: 'bi-check-circle-fill',
                error: 'bi-x-circle-fill',
                warning: 'bi-exclamation-triangle-fill'
            };
            toastIcon.innerHTML = `<i class="bi ${icons[type] || icons.success}"></i>`;

            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Check login
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = '/auth/login';
                return;
            }

            // Set current date
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('currentDate').innerHTML = new Date().toLocaleDateString('id-ID', options);

            // Load stats
            async function loadStats() {
                try {
                    const res = await fetch('/api/mahasiswa', {
                        method: 'GET',
                        headers: authHeaders()
                    });

                    if (!res.ok) {
                        if (res.status === 401) {
                            localStorage.removeItem('token');
                            window.location.href = '/auth/login';
                            return;
                        }
                        throw new Error('Failed');
                    }

                    const data = await res.json();
                    const list = Array.isArray(data) ? data : [];

                    // Update stats
                    document.getElementById('totalMahasiswa').textContent = list.length;

                    if (list.length > 0) {
                        const angkatan = list.map(m => parseInt(m.angkatan) || 0);
                        document.getElementById('angkatanTerbaru').textContent = Math.max(...angkatan);

                        const jurusanSet = new Set(list.map(m => m.jurusan));
                        document.getElementById('totalJurusan').textContent = jurusanSet.size;

                        // Recent students (last 5)
                        const recent = list.slice(-5).reverse();
                        let html = '';
                        recent.forEach(m => {
                            html += `
                        <li>
                            <div class="activity-icon" style="background: var(--primary-light); color: var(--primary);"><i class="bi bi-person-fill"></i></div>
                            <div class="activity-info">
                                <strong>${m.nama || '-'}</strong>
                                <span>NIM: ${m.nim || '-'} • ${m.jurusan || '-'}</span>
                            </div>
                        </li>
                    `;
                        });
                        document.getElementById('recentList').innerHTML = html || '<li style="text-align: center; color: var(--text-muted);">Belum ada data</li>';
                    } else {
                        document.getElementById('recentList').innerHTML = '<li style="text-align: center; padding: 2rem; color: var(--text-muted);">Belum ada data mahasiswa</li>';
                    }

                } catch (err) {
                    console.error('Error:', err);
                    document.getElementById('recentList').innerHTML = '<li style="text-align: center; color: var(--danger);">Gagal memuat data</li>';
                }
            }

            // Logout
            document.getElementById('logoutBtn').addEventListener('click', async function () {
                if (!confirm('Yakin ingin logout?')) return;

                try {
                    await fetch('/api/auth/logout', {
                        method: 'POST',
                        headers: authHeaders()
                    });
                } catch (e) { }

                localStorage.removeItem('token');
                localStorage.removeItem('token_expires');
                showToast('Logout berhasil', 'success');
                setTimeout(() => window.location.href = '/auth/login', 1000);
            });

            loadStats();
        });
    </script>

</body>

</html>