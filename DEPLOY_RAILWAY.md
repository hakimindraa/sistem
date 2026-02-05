# Panduan Deploy ke Railway

## Langkah-langkah Deploy:

### 1. Persiapan Repository
Pastikan project sudah di-push ke GitHub/GitLab:
```bash
git init
git add .
git commit -m "Initial commit"
git remote add origin <URL_REPO_ANDA>
git push -u origin main
```

### 2. Setup Railway Project

1. Buka [railway.app](https://railway.app)
2. Login dengan GitHub
3. Klik "New Project"
4. Pilih "Deploy from GitHub repo"
5. Pilih repository project ini

### 3. Tambahkan MySQL Database

1. Di Railway dashboard, klik "New" → "Database" → "Add MySQL"
2. Railway akan otomatis membuat database dan environment variables

### 4. Setup Environment Variables

Di Railway dashboard, tambahkan variables berikut (Settings → Variables):

**Required Variables:**
```
CI_ENVIRONMENT=production
app.baseURL=https://your-app.up.railway.app/
app.jwtSecret=5b6e7813059dff3649070e41b4d61de7c4a321615d50652970d5736c8fa1ff0a
app.jwtExpire=3600
```

**Database Variables (otomatis dari MySQL service):**
Railway akan otomatis set variables ini dari MySQL service:
- `MYSQLHOST`
- `MYSQLPORT`
- `MYSQLDATABASE`
- `MYSQLUSER`
- `MYSQLPASSWORD`

Tambahkan mapping manual:
```
database.default.hostname=${{MYSQLHOST}}
database.default.port=${{MYSQLPORT}}
database.default.database=${{MYSQLDATABASE}}
database.default.username=${{MYSQLUSER}}
database.default.password=${{MYSQLPASSWORD}}
```

### 5. Deploy

Railway akan otomatis deploy setelah setup selesai. Atau klik "Deploy" manual.

### 6. Setup Database Schema

Setelah deploy berhasil, jalankan migration (jika ada):
1. Buka Railway dashboard
2. Pilih service aplikasi
3. Klik tab "Settings" → "Deploy Logs"
4. Atau gunakan Railway CLI untuk run migration

### 7. Akses Aplikasi

Aplikasi akan tersedia di: `https://your-app.up.railway.app`

## Troubleshooting

### Error: writable directory
Pastikan folder writable memiliki permission yang benar. Railway biasanya handle ini otomatis.

### Database Connection Error
- Cek environment variables sudah benar
- Pastikan MySQL service sudah running
- Cek connection dari service ke database

### 500 Internal Server Error
- Cek logs di Railway dashboard
- Pastikan CI_ENVIRONMENT=production
- Cek file .env sudah sesuai

## Update Aplikasi

Setiap push ke branch main akan otomatis trigger deploy baru di Railway.

```bash
git add .
git commit -m "Update feature"
git push origin main
```

## Railway CLI (Optional)

Install Railway CLI untuk development:
```bash
npm i -g @railway/cli
railway login
railway link
railway run php spark migrate
```
