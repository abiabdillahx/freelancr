# Contributing to Freelancr

Dokumen ini menjelaskan workflow Git yang kita pakai selama pengerjaan proyek. Baca sekali, ikutin terus.

---

## 🌿 Struktur Branch

```
main
└── dev
    ├── feature/auth
    ├── feature/services
    ├── feature/orders
    └── feature/reviews
```

| Branch | Fungsi |
|--------|--------|
| `main` | Kode final, hanya diupdate saat demo/submit |
| `dev` | Branch utama pengerjaan, tempat semua fitur digabung |
| `feature/*` | Branch per fitur, dibuat dari `dev` |

> ⚠️ **Jangan pernah commit langsung ke `main` atau `dev`.**

---

## 🔁 Alur Kerja Harian

### 1. Mulai kerja — selalu sync dulu

```bash
git checkout dev
git pull origin dev
git checkout feature/nama-fiturmu
git merge dev   # ambil update terbaru dari dev
```

### 2. Kerja di branch masing-masing

```bash
# ... coding ...

git add .
git commit -m "feat: tambah endpoint POST /services"
git push origin feature/nama-fiturmu
```

### 3. Selesai fitur — buat Pull Request

- Buka GitHub → **New Pull Request**
- `base: dev` ← `compare: feature/nama-fiturmu`
- Isi judul & deskripsi singkat apa yang diubah
- Minta **minimal 1 orang** review sebelum merge
- Setelah di-approve → **Squash and Merge** ke `dev`

### 4. Setelah PR di-merge

```bash
git checkout dev
git pull origin dev

# Hapus branch lokal yang sudah selesai
git branch -d feature/nama-fiturmu
```

---

## 🏷️ Konvensi Nama Branch

```
feature/auth
feature/services
feature/orders
feature/reviews
feature/gateway
feature/frontend
fix/nama-bug
```

---

## ✍️ Konvensi Commit Message

Format: `<type>: <deskripsi singkat>`

| Type | Kapan dipakai |
|------|--------------|
| `feat` | Tambah fitur baru |
| `fix` | Perbaiki bug |
| `refactor` | Ubah struktur kode tanpa ubah fungsi |
| `docs` | Update dokumentasi / README |
| `chore` | Setup, config, dependency |
| `test` | Tambah atau perbaiki testing |

**Contoh:**
```
feat: tambah JWT middleware untuk role freelancer
fix: validasi gagal di endpoint POST /orders
docs: update README bagian instalasi
chore: install package tymon/jwt-auth
```

---

## 🔀 Aturan Merge ke Main

`dev` → `main` hanya dilakukan **sekali** menjelang demo/submit, oleh satu orang yang ditunjuk (project lead), setelah semua fitur sudah masuk dan dicek bersama.

```bash
git checkout main
git merge dev
git push origin main
```

---

## ⚡ Ringkasan Cepat

```
dev → buat feature branch → coding → push → Pull Request → review → merge ke dev
```
