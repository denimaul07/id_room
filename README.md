# ID ROOM Apps

Repositori ini berisi tiga bagian utama aplikasi:

- **backend/**: Backend API berbasis Laravel
- **frontendv3/**: Frontend aplikasi menggunakan Vite + Vue.js
- **website/**: Website landing page menggunakan Vite + Tailwind CSS

---

## Struktur Folder

- `backend/`  
  Backend Laravel untuk API dan logika bisnis.
- `frontendv3/`  
  Frontend aplikasi utama berbasis Vue.js.
- `website/`  
  Website landing page.

---

## Cara Menjalankan

### Backend (Laravel)
1. Masuk ke folder `backend/`
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Copy `.env.example` ke `.env` dan sesuaikan konfigurasi
4. Generate key:
   ```bash
   php artisan key:generate
   ```
5. Jalankan migrasi:
   ```bash
   php artisan migrate
   ```
6. Jalankan server:
   ```bash
   php artisan serve
   ```

### Frontend (Vue.js)
1. Masuk ke folder `frontendv3/`
2. Install dependencies:
   ```bash
   npm install
   ```
3. Jalankan aplikasi:
   ```bash
   npm run dev
   ```

### Website (Landing Page)
1. Masuk ke folder `website/`
2. Install dependencies:
   ```bash
   npm install
   ```
3. Jalankan aplikasi:
   ```bash
   npm run dev
   ```

---

## Fitur Utama
- Backend API dengan Laravel
- Frontend SPA dengan Vue.js
- Website landing page modern


## Lisensi
Proyek ini menggunakan lisensi MIT.
