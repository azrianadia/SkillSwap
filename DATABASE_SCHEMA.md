# Skema Database KolaboKampus (Updated)

*Last Updated: 2026-07-19*

---

## Overview
Database untuk platform marketplace pertukaran skill antar mahasiswa dengan sistem freemium (Free/Pro).

---

## Tables

### 1. users
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    prodi VARCHAR(255) NULL,
    semester TINYINT UNSIGNED NULL,
    whatsapp_number VARCHAR(255) NULL,
    avatar VARCHAR(255) NULL,
    plan ENUM('free', 'pro') DEFAULT 'free',
    swap_quota INT DEFAULT 5,
    quota_reset_at TIMESTAMP NULL,
    is_pro BOOLEAN DEFAULT FALSE,
    midtrans_customer_id VARCHAR(255) NULL,
    midtrans_subscription_id VARCHAR(255) NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

**Indexes:**
- PRIMARY KEY (`id`)
- UNIQUE KEY `users_email_unique` (`email`)

---

### 2. skills
```sql
CREATE TABLE skills (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    skill_name VARCHAR(255) NOT NULL UNIQUE,
    category VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

---

### 3. user_skills (Pivot Table)
```sql
CREATE TABLE user_skills (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    skill_id BIGINT UNSIGNED NOT NULL,
    type ENUM('offer', 'seek') NOT NULL,
    proficiency_level ENUM('beginner', 'intermediate', 'expert') DEFAULT 'beginner',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
);
```

---

### 4. swaps
```sql
CREATE TABLE swaps (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    sender_id BIGINT UNSIGNED NOT NULL,
    receiver_id BIGINT UNSIGNED NOT NULL,
    offered_skill_id BIGINT UNSIGNED NOT NULL,
    requested_skill_id BIGINT UNSIGNED NOT NULL,
    status ENUM('pending', 'accepted', 'rejected', 'completed') DEFAULT 'pending',
    swapped_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (offered_skill_id) REFERENCES skills(id) ON DELETE CASCADE,
    FOREIGN KEY (requested_skill_id) REFERENCES skills(id) ON DELETE CASCADE
);
```

---

### 5. reviews
```sql
CREATE TABLE reviews (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    swap_id BIGINT UNSIGNED NOT NULL,
    reviewer_id BIGINT UNSIGNED NOT NULL,
    reviewee_id BIGINT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL,
    comment TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (swap_id) REFERENCES swaps(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewee_id) REFERENCES users(id) ON DELETE CASCADE
);
```

---

### 6. messages
```sql
CREATE TABLE messages (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    swap_id BIGINT UNSIGNED NOT NULL,
    sender_id BIGINT UNSIGNED NOT NULL,
    receiver_id BIGINT UNSIGNED NOT NULL,
    content TEXT NOT NULL,
    type ENUM('text', 'image', 'file', 'system') DEFAULT 'text',
    file_path VARCHAR(255) NULL,
    is_read BOOLEAN DEFAULT FALSE,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (swap_id) REFERENCES swaps(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);
```

---

### 7. transactions (NEW - Midtrans Payment History)
```sql
CREATE TABLE transactions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    order_id VARCHAR(255) UNIQUE NOT NULL,
    midtrans_transaction_id VARCHAR(255) NULL,
    midtrans_subscription_id VARCHAR(255) NULL,
    amount INT NOT NULL,
    status ENUM('pending', 'settlement', 'failed', 'expired', 'cancelled', 'denied') DEFAULT 'pending',
    plan ENUM('free', 'pro') DEFAULT 'pro',
    midtrans_response JSON NULL,
    paid_at TIMESTAMP NULL,
    expired_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

**Indexes:**
- PRIMARY KEY (`id`)
- UNIQUE KEY `transactions_order_id_unique` (`order_id`)
- FOREIGN KEY `transactions_user_id_foreign` (`user_id`) → `users(id)`

---

### 8. notifications (Laravel default)
```sql
CREATE TABLE notifications (
    id CHAR(36) PRIMARY KEY,
    type VARCHAR(255) NOT NULL,
    notifiable_type VARCHAR(255) NOT NULL,
    notifiable_id BIGINT UNSIGNED NOT NULL,
    data JSON NOT NULL,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

---

### 9. System Tables (Laravel defaults)
- `cache`, `cache_locks` - Cache driver
- `jobs`, `job_batches`, `failed_jobs` - Queue worker
- `password_reset_tokens` - Password reset

---

## Entity Relationship Diagram (ERD)

```
users ||--o{ user_skills }o--|| skills
users ||--o{ swaps (sender) }o--|| swaps
users ||--o{ swaps (receiver) }o--|| swaps
skills ||--o{ swaps (offered) }o--|| swaps
skills ||--o{ swaps (requested) }o--|| swaps
swaps ||--o{ reviews }o--|| users (reviewer)
swaps ||--o{ reviews }o--|| users (reviewee)
swaps ||--o{ messages }o--|| users (sender)
swaps ||--o{ messages }o--|| users (receiver)
users ||--o{ transactions }o--|| transactions
users ||--o{ notifications }o--|| users
```

---

## Key Relationships

| Relationship | Type | Description |
|-------------|------|-------------|
| User → Skills (offer) | Many-to-Many | Skill yang ditawarkan user |
| User → Skills (seek) | Many-to-Many | Skill yang dicari user |
| User → Sent Swaps | One-to-Many | Swap yang dikirim user |
| User → Received Swaps | One-to-Many | Swap yang diterima user |
| Swap → Messages | One-to-Many | Percakapan dalam swap |
| Swap → Reviews | One-to-Many | Review setelah swap selesai |
| User → Transactions | One-to-Many | Riwayat pembayaran |
| User → Notifications | One-to-Many | Notifikasi sistem |

---

## Enum Values

### user_skills.type
- `offer` - Skill yang ditawarkan
- `seek` - Skill yang dicari

### user_skills.proficiency_level
- `beginner` - Pemula
- `intermediate` - Menengah
- `expert` - Ahli

### swaps.status
- `pending` - Menunggu konfirmasi
- `accepted` - Diterima (bisa chat, bisa selesaikan)
- `rejected` - Ditolak
- `completed` - Selesai (bisa review, chat tetap buka)

### reviews.rating
- `1` - `5` (TINYINT UNSIGNED)

### messages.type
- `text` - Pesan teks
- `image` - Gambar
- `file` - File
- `system` - Pesan sistem

### transactions.status
- `pending` - Menunggu pembayaran
- `settlement` - Berhasil
- `failed` - Gagal
- `expired` - Kadaluarsa
- `cancelled` - Dibatalkan
- `denied` - Ditolak

### users.plan
- `free` - Gratis (5 swap/bulan)
- `pro` - Berbayar (Unlimited)

### transactions.plan
- `free` - Gratis
- `pro` - Berbayar (Rp 25.000/bulan)

---

## Important Notes

1. **Privacy**: `users.whatsapp_number` hanya terlihat setelah swap status `accepted` atau `completed`
2. **Matching**: Engine mencocokkan `user_skills` type `seek` dengan type `offer` user lain
3. **Chat**: Bisa diakses jika swap status `pending`, `accepted`, atau `completed`
4. **Review**: Hanya bisa diberikan setelah swap status `completed`
5. **Swap Quota**: 
   - Free: 5 swap/bulan, reset otomatis per 30 hari sejak registrasi
   - Pro: Unlimited
   - Kuota berkurang saat request dikirim
   - Jika request ditolak/dibatalkan → kuota dikembalikan (+1)
6. **Soft Deletes**: Tabel utama TIDAK menggunakan soft deletes (hapus permanen)
6. **Subscription**: Midtrans recurring billing, webhook sinkronisasi status otomatis

---

## Migration Files

| File | Description |
|------|-------------|
| `0001_01_01_000000_create_users_table.php` | Tabel users |
| `2026_06_28_111915_create_skills_table.php` | Tabel skills |
| `2026_06_28_111916_create_user_skills_table.php` | Tabel user_skills |
| `2026_06_28_111917_create_swaps_table.php` | Tabel swaps (status: pending, accepted, rejected) |
| `2026_06_28_111918_create_reviews_table.php` | Tabel reviews |
| `2026_07_01_070921_create_messages_table.php` | Tabel messages |
| `2026_07_04_061329_add_completed_status_to_swaps_table.php` | **Add 'completed' ke enum status** |
| `2026_07_01_024132_add_completed_at_to_swaps_table.php` | Tambah completed_at ke swaps |
| `2026_07_11_070016_add_avatar_to_users_table.php` | **Add avatar ke users** |
| `2026_07_18_122448_add_subscription_fields_to_users_table.php` | **Add subscription fields ke users** |
| `2026_07_18_122510_create_transactions_table.php` | **Tabel transactions untuk payment history** |
| `2026_07_01_013934_create_notifications_table.php` | Tabel notifications |

---

*Generated: 2026-07-19*