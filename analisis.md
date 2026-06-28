Saya sedang membangun aplikasi web 'SkillSwap' menggunakan Laravel 13. Aplikasi ini adalah platform pertukaran keahlian antar mahasiswa.

Tolong bantu saya membuat tabel database (Migrations) untuk aplikasi ini. Saya butuh struktur tabel berikut dengan relasi yang benar:

Users: (id, name, email, password, prodi, semester, whatsapp_number)

Skills: (id, skill_name)

User_skills: (id, user_id, skill_id, type [offer/seek])

Swaps: (id, sender_id, receiver_id, status [pending/accepted/rejected])

Reviews: (id, swap_id, rating, comment)

Sebelum membuat file kodenya, tolong jelaskan dulu apakah rancangan relasi tabel ini sudah efisien untuk sistem 'matching' keahlian? Setelah saya setujui, tolong buatkan perintah php artisan make:migration untuk setiap tabel tersebut.

