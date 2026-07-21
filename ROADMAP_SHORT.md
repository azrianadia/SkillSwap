# KolaboKampus - Roadmap Singkat 



---

## 🎯 Target: Stabilisasi & Polish MVP

---

## ✅ Sudah Selesai (MVP Core)

- User Auth & Profile (prodi, semester, WA)
- Skill Offer/Seek + Proficiency Level
- Swap Request (Pending/Accepted/Rejected/Completed)
- Chat Real-time (Polling 3s) + Privacy WA
- Rating & Review
- Notifikasi
- Dashboard Search/Filter
- My Swaps dengan link ke profil
- **Email Verification wajib sebelum swap** ✅
- **Avatar Upload** ✅
- **Swap Quota System** (5/bulan free, unlimited Pro, refund on reject) ✅
- **Midtrans Subscription Integration** (Code done, sandbox issues) ⚠️
- **Delete Account** (Button ada di edit profil) ✅
- **Seeder Data Demo** ✅

---

## 📅 Sprint 1 : Bug Fix & UX Polish

| Task | Status | Estimasi |
|------|--------|----------|
| Fix duplikasi bubble chat (sudah fix polling) | ✅ Done | - |
| Fix preview chat terakhir di `/chat` | ✅ Done | - |
| Validasi form swap (prevent duplicate request) | ✅ Done | - |
| Loading state kirim pesan | ✅ Done | - |
| Empty state yang informatif | ✅ Done | - |
| Responsive mobile check & fix | ⏳ TODO | 3 jam |
| Error handling toast/alert konsisten | ✅ Done | - |
| Unit test critical path (swap, chat, review) | ✅ Done | - |

---

## 📅 Sprint 2 : Fitur Kecil & Deployment Prep

| Task | Status | Estimasi |
|------|--------|----------|
| **Email verification wajib sebelum swap** | 🔴 **BELUM AKTIF** | 
| **Midtrans webhook/callback fix** | 🔴 **BROKEN** (Sandbox 500) | 
| **Hapus akun user** (UI sudah ada, cek flow) | ✅ Done | 
| **Edit profil skill AJAX/Modal** | 🟡 Sedang | 
| **Search skill autocomplete** | 🟡 Sedang | 
| **Seeder data demo** | ✅ **SUDAH ADA** | 
| **Deploy ke production** | 🔴 Prioritas | 
| **Dokumentasi user guide** | 🔴 Prioritas | 

---

## 📅 Sprint 3 : Final Polish & Demo

| Task | Status | Estimasi |
|------|--------|----------|
| Bug bash & regression test | 🔴 Prioritas | 
| Performance check (query log, slow queries) | 🟡 Sedang | 
| Backup database & config production | 🔴 Prioritas | 
| Presentasi/demo video recording | 🔴 Prioritas | 
| Handover dokumentasi teknis | 🔴 Prioritas |

---

## 🚫 Tidak Dikerjakan (Out of Scope)

| Fitur | Alasan |
|-------|--------|
| WebSocket/Reverb real-time chat | Butuh setup server & infra tambahan |
| Push notification (Firebase/OneSignal) | Butuh mobile app / service worker |
| Group swap / Study group | Logic kompleks, butuh redesign DB |
| AI Matching / Recommendation | Butuh ML model & data training |
| Mobile App (React Native/Flutter) | Terlalu besar untuk 2 minggu |
| Gamification (XP, Badge, Leaderboard) | Butuh sistem poin & event tracking |
| Video Call WebRTC | Infra signaling server kompleks |
| Multi-language (i18n) | Butuh translate semua string |
| Admin Analytics Dashboard | Butuh role admin & query aggregation |
| Premium/Subscription | Butuh payment gateway (Midtrans/Xendit) - **Partial Done** |

---

## 📋 Definition of Done (20 Juli)

- [ ] Semua bug critical & high fixed
- [ ] Deploy production accessible via domain/IP
- [ ] Email verification working
- [ ] Midtrans subscription working (webhook OK)
- [ ] User guide dokumentasi selesai
- [ ] Database backup & restore tested
- [ ] Demo video recorded (5-10 menit)
- [ ] Kode push ke GitHub (private repo clean history bersih)
- [ ] Kode push ke GitHub (private repo clean history bersih)

---

## 📊 Daily Standup Format (15 menit)

```
Hari, Tanggal
1. Kemarin: [selesai apa]
2. Hari ini: [fokus apa]
3. Blocker: [butuh bantuan apa]
```

---

## 🔗 Link Penting

- Repo: `github.com/.../SkillSwap`
- Production: `http://domain-anda.com`
- Database Schema: `DATABASE_SCHEMA.md`
- API Routes: `routes/web.php`
- Midtrans Docs: https://api-docs.midtrans.com/#create-subscription

---

*Simpan file ini sebagai `ROADMAP_SHORT.md` di root project.*

---

**Status Update (19 Juli 2026):**
- Midtrans Subscription: Code ✅, Sandbox 500 error (server issue)
- Email Verification: Belum aktifkan middleware
- Delete Account: UI ada di edit profil
- Edit Profil Skill: Masih full page reload
- Search Skill: Masih `<select>` dropdown
- Seeder: Sudah ada