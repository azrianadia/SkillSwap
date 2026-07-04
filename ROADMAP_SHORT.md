# KolaboKampus - Roadmap Singkat (Hingga 20 Juli 2026)

*Project Timeline: ~2 minggu tersisa*

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

---

## 📅 Sprint 1 (4-10 Juli): Bug Fix & UX Polish

| Task | Status | Estimasi |
|------|--------|----------|
| Fix duplikasi bubble chat (sudah fix polling) | ✅ Done | - |
| Fix preview chat terakhir di `/chat` | ✅ Done | - |
| Validasi form swap (prevent duplicate request) | ✅ Done | - |
| Loading state kirim pesan | ⏳ TODO | 2 jam |
| Empty state yang informatif | ⏳ TODO | 1 jam |
| Responsive mobile check & fix | ⏳ TODO | 3 jam |
| Error handling toast/alert konsisten | ⏳ TODO | 2 jam |
| Unit test critical path (swap, chat, review) | ⏳ TODO | 4 jam |

---

## 📅 Sprint 2 (11-17 Juli): Fitur Kecil & Deployment Prep

| Task | Status | Estimasi |
|------|--------|----------|
| **Email verification wajib sebelum swap** | 🔴 Prioritas | 1 hari |
| **Hapus akun user** (sudah ada route, cek UI) | 🟡 Sedang | 4 jam |
| **Edit profil skill** (tambah hapus via modal/AJAX) | 🟡 Sedang | 1 hari |
| **Search skill autocomplete** di dashboard | 🟡 Sedang | 4 jam |
| **Avatar upload** (opsional, default inisial) | 🟢 Nice to have | 1 hari |
| **Seeder data demo** untuk presentasi | 🔴 Prioritas | 4 jam |
| **Deploy ke production** (Laragon/Shared Hosting/VPS) | 🔴 Prioritas | 1 hari |
| **Dokumentasi user guide** (PDF/Notion) | 🔴 Prioritas | 1 hari |

---

## 📅 Sprint 3 (18-20 Juli): Final Polish & Demo

| Task | Status | Estimasi |
|------|--------|----------|
| Bug bash & regression test | 🔴 Prioritas | 1 hari |
| Performance check (query log, slow queries) | 🟡 Sedang | 4 jam |
| Backup database & config production | 🔴 Prioritas | 1 jam |
| Presentasi/demo video recording | 🔴 Prioritas | 4 jam |
| Handover dokumentasi teknis | 🔴 Prioritas | 2 jam |

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
| Premium/Subscription | Butuh payment gateway (Midtrans/Xendit) |

---

## 📋 Definition of Done (20 Juli)

- [ ] Semua bug critical & high fixed
- [ ] Deploy production accessible via domain/IP
- [ ] Email verification working
- [ ] User guide dokumentasi selesai
- [ ] Database backup & restore tested
- [ ] Demo video recorded (5-10 menit)
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

---

*Simpan file ini sebagai `ROADMAP_SHORT.md` di root project.*