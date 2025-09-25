## 🛠️ Teknologi yang Digunakan

* **Framework**: Laravel 12
* **Database**: PostgreSQL 17
* **Bahasa Pemrograman**: PHP 8.3+

## 🔌 Instalasi & Konfigurasi

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal Anda.

1.  **Clone Repository Ini**

2.  **Install Dependencies**
    Pastikan Anda memiliki Composer terinstal.
    ```bash
    composer install
    ```

3.  **Setup Environment File**
    Salin file `.env.example` menjadi `.env`.
    ```bash
    cp .env.example .env
    ```
    Kemudian, sesuaikan konfigurasi database PostgreSQL Anda di dalam file `.env`.
    ```env
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=nama_database_anda
    DB_USERNAME=user_postgres_anda
    DB_PASSWORD=password_anda
    ```

4.  **Generate Keys**
    Jalankan perintah berikut untuk men-generate kunci aplikasi dan JWT secret.
    ```bash
    php artisan key:generate
    php artisan jwt:secret
    ```

5.  **Jalankan Migrasi Database**
    Perintah ini akan membuat semua tabel yang dibutuhkan di database Anda.
    ```bash
    php artisan migrate
    ```

6.  **(Opsional) Jalankan Database Seeder**
    Jalankan perintah ini untuk membuat data dummy.
    ```bash
    php artisan db:seed
    ```

7.  **Jalankan Server**
    Proyek Anda sekarang siap diakses.
    ```bash
    php artisan serve
    ```
    API akan tersedia di `http://127.0.0.1:8000`.

## 📚 Dokumentasi API

Berikut adalah daftar endpoint utama yang tersedia. Semua endpoint yang membutuhkan otentikasi harus menyertakan header `Authorization: Bearer {token}` dan `Accept: application/json`.

### 1. Login User
- **Endpoint**: `POST /api/login`
- **Deskripsi**: Mengotentikasi user dan mengembalikan JWT token.
- **Body (Request)**:
  ```json
  {
      "email": "user@example.com",
      "password": "password"
  }
  ```
- **Respons (Success)**:
  ```json
  {
      "message": "Login berhasil",
      "data": {
          "access_token": "jwt_token_anda"
      }
  }
  ```

### 2. Mencatat Absensi
- **Endpoint**: `POST /api/epresence`
- **Otentikasi**: Diperlukan.
- **Body (Request)**:
  ```json
  {
      "type": "IN",
      "waktu": "2025-09-25 08:00:00"
  }
  ```
- **Respons (Success)**:
  ```json
  {
      "message": "Absensi berhasil dicatat",
      "data": {
          "id": 1,
          "id_users": 1,
          "type": "IN",
          "is_approve": false,
          "waktu": "2025-09-25 08:00:00",
          "created_at": "2025-09-25T01:05:00.000000Z",
          "updated_at": "2025-09-25T01:05:00.000000Z"
      }
  }
  ```

### 3. Menyetujui Absensi (Approval)
- **Endpoint**: `PATCH /api/epresence/{id}/approve`
- **Otentikasi**: Diperlukan (hanya supervisor yang berwenang).
- **Deskripsi**: `{id}` adalah ID dari tabel `epresences`.
- **Respons (Success)**:
  ```json
  {
      "message": "Presensi berhasil disetujui.",
      "data": {
          "id": 1,
          "is_approve": true,
          "updated_at": "2025-09-25T01:10:00.000000Z",
          "..." : "..."
      }
  }
  ```

### 4. Mendapatkan Rekapitulasi Data
- **Endpoint**: `GET /api/epresence`
- **Otentikasi**: Diperlukan.
- **Respons (Success)**:
  ```json
  {
    "message": "Success get data",
    "data": [
      {
        "id_user": 1,
        "nama_user": "Ananda Bayu",
        "tanggal": "2025-09-25",
        "waktu_masuk": "08:00:00",
        "waktu_pulang": "17:00:00",
        "status_masuk": "APPROVE",
        "status_pulang": "PENDING"
      }
    ]
  }
  ```
