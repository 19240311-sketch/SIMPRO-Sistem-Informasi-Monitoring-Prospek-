# Design System — SIMPRO

**Sistem Informasi Monitoring Prospek · Blue & White Edition**

| Item | Keterangan |
|---|---|
| Tema Visual | Blue & White Edition |
| Vibe | Profesional, modern, bersih, terpercaya, mudah digunakan |
| Referensi Pattern | Vercel Design System, Tailwind UI |
| Implementasi | Tailwind CSS / CSS modern, Blade Template (Laravel 10) |
| Font Utama | Inter |
| Versi | 1.0 |

---

## 1. Overview & Tipografi

### 1.1 Overview Brand

SIMPRO adalah alat kerja harian Sales dan Admin dealer sepeda motor. Filosofi visualnya: **tenang, rapi, dan langsung ke inti**. Antarmuka tidak boleh bersaing dengan data prospek, karena data itulah yang dibaca berulang kali sepanjang hari.

- **Putih & abu sangat muda** menjadi kanvas dominan. Ini memberi kesan bersih dan memberi ruang napas pada tabel serta form yang padat data.
- **Biru** dipilih karena diasosiasikan dengan kepercayaan, stabilitas, dan profesionalisme. Biru dipakai hemat: hanya untuk aksi utama, tautan, elemen aktif, dan penanda progres, sehingga mata pengguna langsung tertuju ke hal yang bisa dilakukan.
- **Gradient biru** (biru → cyan) menjadi satu-satunya elemen dekoratif. Ia dipakai pada hero, angka statistik utama, dan progres status, sebagai penanda "perkembangan prospek" yang bergerak maju.
- **Bayangan tipis berlapis + hairline ring** memberi kedalaman halus tanpa terlihat berat, sesuai gaya Vercel.

### 1.2 Font Family

| Peran | Font | Fallback | Dipakai untuk |
|---|---|---|---|
| **Font 1 — Fungsional** | **Inter** | `ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif` | Body, UI, tombol, form, navigasi, tabel, seluruh komponen aplikasi |
| **Font 2 — Editorial** | **Plus Jakarta Sans** | Inter, `ui-sans-serif, system-ui, sans-serif` | Hero headline dan tagline di halaman login/landing saja |

> Sesuai arahan proyek, **Inter adalah font utama untuk seluruh UI**. Font 2 dipilih tetap bersih dan geometris (bukan serif/script) agar tidak terlalu dekoratif, dan **dilarang** dipakai di dalam area dashboard.

Muat font (Blade `<head>`):

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
```

### 1.3 Hierarki Tipografi

| Token | Font | Ukuran | Weight | Line-height | Letter-spacing | Kegunaan |
|---|---|---|---|---|---|---|
| `display-xl` | Plus Jakarta Sans | 56px | 800 | 60px (1.07) | -0.03em | Hero headline halaman login/landing (desktop) |
| `display-lg` | Plus Jakarta Sans | 40px | 700 | 44px (1.1) | -0.025em | Tagline, judul hero mobile/tablet |
| `heading-lg` | Inter | 30px | 700 | 36px | -0.02em | Judul halaman dashboard |
| `heading-md` | Inter | 20px | 600 | 28px | -0.01em | Judul kartu, judul modal, judul section |
| `body-md` | Inter | 16px | 400 | 24px | 0 | Teks isi utama, isi form |
| `body-sm` | Inter | 14px | 400 | 20px | 0 | Isi tabel, teks pendukung, deskripsi |
| `button-md` | Inter | 14px | 500 | 20px | 0 | Label tombol, tab, item menu |
| `caption` | Inter | 12px | 500 | 16px | 0.01em | Label badge, timestamp, helper text, header kolom tabel |

### 1.4 Prinsip Tipografi

**Do**
- Gunakan tracking (letter-spacing) **negatif** pada ukuran ≥ 30px agar heading terasa rapat dan modern.
- Gunakan **sentence case** untuk judul, tombol, dan label ("Tambah prospek", bukan "TAMBAH PROSPEK").
- Batasi lebar baris teks isi maksimal ±70 karakter (`max-w-prose`).
- Gunakan `tabular-nums` untuk angka pada tabel dan statistik agar sejajar.
- Gunakan hanya weight 400, 500, 600, 700.

**Don't**
- Jangan memakai lebih dari 2 font family dalam satu halaman.
- Jangan memakai ALL CAPS untuk teks panjang. Uppercase hanya untuk `caption` pendek (opsional, dengan tracking +0.04em).
- Jangan memakai weight di bawah 400 atau 800+ di dalam dashboard.
- Jangan memakai teks di bawah 12px.
- Jangan memakai italic atau underline dekoratif; underline hanya untuk tautan.

---

## 2. Sistem Warna (Color Tokens)

### 2.1 Brand & Aksen

| Token | Hex | Penggunaan |
|---|---|---|
| `primary` | `#2563EB` | Tombol utama, tautan, item aktif, fokus ring |
| `primary-deep` | `#1D4ED8` | Hover/pressed tombol utama, teks tautan hover |
| `primary-soft` | `#EFF6FF` | Latar highlight lembut: item menu aktif, chip terpilih, baris terpilih |
| `primary-softer` | `#DBEAFE` | Border highlight, ikon latar, hover pada `primary-soft` |
| `primary-ink` | `#1E3A8A` | Teks pada latar `primary-soft` |
| `accent` | `#06B6D4` | Ujung gradient, aksen ilustratif (bukan untuk tombol) |

### 2.2 Surface

| Token | Hex | Penggunaan |
|---|---|---|
| `canvas` | `#FFFFFF` | Latar halaman utama |
| `canvas-soft` | `#F8FAFC` | Latar area dashboard, sidebar, header tabel |
| `surface` | `#FFFFFF` | Kartu, modal, dropdown, popover |
| `hairline` | `#E2E8F0` | Border dan divider standar |
| `hairline-strong` | `#CBD5E1` | Border input, border tombol sekunder |

### 2.3 Teks

| Token | Hex | Penggunaan |
|---|---|---|
| `ink` | `#0F172A` | Heading, angka utama, teks penting |
| `body` | `#334155` | Teks isi standar |
| `mute` | `#64748B` | Teks sekunder, placeholder, caption, timestamp |
| `on-primary` | `#FFFFFF` | Teks/ikon di atas `primary`, `danger`, dan gradient |

### 2.4 Semantik & Status

| Token | Hex (solid) | Hex (soft bg) | Hex (teks di soft) | Penggunaan |
|---|---|---|---|---|
| `success` | `#16A34A` | `#F0FDF4` | `#166534` | Berhasil disimpan, prospek closing |
| `warning` | `#D97706` | `#FFFBEB` | `#92400E` | Perlu perhatian, sedang diproses, follow-up tertunda |
| `error` | `#DC2626` | `#FEF2F2` | `#991B1B` | Gagal, validasi error, prospek batal/ditolak |
| `info` | `#0284C7` | `#F0F9FF` | `#075985` | Informasi netral, tips |

### 2.5 Pemetaan Status Prospek

> Nama status di bawah adalah **usulan awal**. Status dikelola sebagai master data (`prospect_statuses`), sehingga nama dan jumlahnya dapat berubah. Yang dijaga adalah **pemetaan warna per kategori makna**.

| Status Prospek | Makna | Warna Solid | Background Badge | Teks Badge |
|---|---|---|---|---|
| Baru | Prospek baru masuk, belum di-follow-up | `#64748B` | `#F1F5F9` | `#334155` |
| Follow-up | Sedang dalam proses follow-up | `#2563EB` | `#EFF6FF` | `#1E3A8A` |
| Hot | Berpotensi tinggi, perlu tindakan segera | `#D97706` | `#FFFBEB` | `#92400E` |
| Deal | Prospek berhasil menjadi pembelian | `#16A34A` | `#F0FDF4` | `#166534` |
| Batal / Tidak Jadi | Prospek batal atau ditolak | `#DC2626` | `#FEF2F2` | `#991B1B` |

Jenis aktivitas:

| Aktivitas | Warna | Ikon |
|---|---|---|
| Phone | `primary` `#2563EB` | telepon |
| Visit | `accent` `#06B6D4` (teks `#0E7490`) | lokasi / pin |

### 2.6 Brand Gradient

```css
:root {
  /* Gradient utama brand */
  --gradient-brand: linear-gradient(135deg, #2563EB 0%, #06B6D4 100%);
  /* Varian gelap untuk hero / banner */
  --gradient-brand-deep: linear-gradient(135deg, #1E3A8A 0%, #2563EB 55%, #06B6D4 100%);
  /* Glow lembut di belakang hero */
  --gradient-glow: radial-gradient(60% 60% at 50% 0%, rgba(37, 99, 235, 0.18) 0%, rgba(255, 255, 255, 0) 100%);
  /* Latar progres (track) */
  --gradient-progress: linear-gradient(90deg, #2563EB 0%, #06B6D4 100%);
}
```

Aturan pakai: gradient **hanya** untuk hero, angka statistik utama, progress bar/tracker, dan avatar/logo. Jangan untuk tombol standar, tabel, atau latar halaman penuh.

---

## 3. Layout, Elevasi, & Bentuk

### 3.1 Sistem Spacing (basis 4px)

| Token | Nilai | Tailwind | Contoh penggunaan |
|---|---|---|---|
| `xxs` | 2px | `0.5` | Jarak ikon-teks sangat rapat |
| `xs` | 4px | `1` | Gap dalam badge |
| `sm` | 8px | `2` | Gap antar elemen kecil |
| `md` | 12px | `3` | Padding input, gap form |
| `lg` | 16px | `4` | Padding kartu kecil, gap standar |
| `xl` | 24px | `6` | Padding kartu, jarak antar kartu |
| `2xl` | 32px | `8` | Jarak antar blok |
| `3xl` | 48px | `12` | Padding section |
| `4xl` | 64px | `16` | Jarak antar section besar |
| `section` / `5xl` | 96px | `24` | Jarak section landing/hero |

### 3.2 Grid & Container

| Breakpoint | Rentang | Tailwind | Kolom | Gutter | Margin sisi | Perilaku |
|---|---|---|---|---|---|---|
| Mobile | < 640px | default | 4 | 16px | 16px | Sidebar jadi drawer, tabel scroll horizontal / jadi card list |
| Tablet | 640–1023px | `sm`, `md` | 8 | 24px | 24px | Sidebar collapse ke ikon atau drawer |
| Desktop | 1024–1279px | `lg` | 12 | 24px | 32px | Sidebar penuh (256px) |
| Wide | ≥ 1280px | `xl`, `2xl` | 12 | 32px | auto | Konten dibatasi `max-w-7xl` (1280px) |

- Container halaman: `max-w-7xl mx-auto px-4 sm:px-6 lg:px-8`.
- Lebar sidebar dashboard: **256px** (desktop), **64px** (collapsed).
- Area target sentuh minimum di mobile: **44×44px**.

### 3.3 Elevasi & Bayangan

Teknik: beberapa bayangan tipis berlapis + **inset hairline ring** (`0 0 0 1px`) agar tepi tetap tajam.

| Level | Token | Penggunaan | Nilai CSS |
|---|---|---|---|
| 0 | `elev-0` | Flat: latar halaman, baris tabel | `none` |
| 1 | `elev-1` | Kartu standar, input | `0 0 0 1px rgba(15,23,42,0.06), 0 1px 2px rgba(15,23,42,0.04)` |
| 2 | `elev-2` | Kartu interaktif (hover), tombol sekunder hover | `0 0 0 1px rgba(15,23,42,0.06), 0 1px 2px rgba(15,23,42,0.04), 0 4px 8px -2px rgba(15,23,42,0.06)` |
| 3 | `elev-3` | Dropdown, popover, tooltip | `0 0 0 1px rgba(15,23,42,0.06), 0 2px 4px rgba(15,23,42,0.04), 0 8px 16px -4px rgba(15,23,42,0.08)` |
| 4 | `elev-4` | Navbar sticky saat scroll, toast | `0 0 0 1px rgba(15,23,42,0.06), 0 2px 4px rgba(15,23,42,0.04), 0 12px 24px -6px rgba(15,23,42,0.10), 0 24px 48px -12px rgba(15,23,42,0.08)` |
| 5 | `elev-5` | Modal | `0 0 0 1px rgba(15,23,42,0.06), 0 4px 8px rgba(15,23,42,0.04), 0 16px 32px -8px rgba(15,23,42,0.12), 0 32px 64px -16px rgba(15,23,42,0.16)` |

### 3.4 Border Radius

| Token | Nilai | Komponen |
|---|---|---|
| `none` | 0 | Divider, tabel penuh lebar |
| `sm` | 4px | Checkbox, tag kecil |
| `md` | 8px | Input, select, textarea, tombol kecil |
| `lg` | 12px | Kartu, modal, dropdown |
| `pill` | 9999px | Tombol utama/sekunder (pill), badge status, chip/tab |
| `full` | 50% | Avatar, titik status, ikon bulat pada timeline |

---

## 4. Spesifikasi Komponen UI Standar

### 4.1 Tombol (Buttons)

| Varian | Ukuran | Padding | Radius | Default | Hover | Active | Focus | Disabled |
|---|---|---|---|---|---|---|---|---|
| **Primary pill** (aksi utama / marketing) | tinggi 44px, `button-md` | `px-6` | `pill` | bg `primary`, teks `on-primary` | bg `primary-deep` + `elev-2` | bg `primary-deep`, scale 0.98 | ring 2px `primary` offset 2px | bg `primary` opacity 50% |
| **Secondary pill** | tinggi 44px | `px-6` | `pill` | bg `surface`, border `hairline-strong`, teks `ink` | bg `canvas-soft` | bg `hairline` | ring 2px `primary` offset 2px | opacity 50% |
| **Small nav button** | tinggi 32px, `button-md` | `px-3` | `md` | bg transparan, teks `body` | bg `canvas-soft`, teks `ink` | bg `hairline` | ring 2px `primary` | opacity 50% |
| **Danger** | tinggi 40px | `px-5` | `pill` | bg `error`, teks `on-primary` | bg `#B91C1C` | bg `#991B1B` | ring 2px `error` offset 2px | opacity 50% |
| **Tab ghost / chip** | tinggi 32px, `button-md` | `px-4` | `pill` | bg transparan, teks `mute` | bg `canvas-soft`, teks `ink` | – | ring 2px `primary` | – |
| Tab/chip **terpilih** | idem | idem | `pill` | bg `primary-soft`, teks `primary-ink` | bg `primary-softer` | – | – | – |

Aturan: satu halaman hanya boleh punya **satu** tombol Primary utama per area. Ikon di tombol berukuran 16px dengan gap `sm`.

### 4.2 Kartu (Cards)

| Varian | Padding | Radius | Background | Border/Shadow | Hover |
|---|---|---|---|---|---|
| **Card marketing** | `2xl` (32px) | `lg` | `surface` | `elev-1` | `elev-2`, translateY(-2px) |
| **Card dashboard / statistik** | `xl` (24px) | `lg` | `surface` | `elev-1` | tidak berubah (non-klik) |
| **Card list item** | `lg` (16px) | `lg` | `surface` | `elev-1` | `elev-2` + border `primary-softer` bila bisa diklik |
| **Card soft** | `lg` (16px) | `lg` | `canvas-soft` | border 1px `hairline`, tanpa shadow | – |

Struktur Card statistik: label (`caption`, `mute`) → angka (`heading-lg`, `ink`, `tabular-nums`) → delta/keterangan (`body-sm`). Angka utama boleh memakai teks gradient `--gradient-brand`.

### 4.3 Form Input

| Elemen | Spesifikasi |
|---|---|
| **Label** | `body-sm`, weight 500, `ink`, margin bawah `xs` (4px). Tanda wajib: `*` warna `error`. |
| **Input teks** | tinggi 40px, padding `px-3`, radius `md`, bg `surface`, border 1px `hairline-strong`, teks `body-md` `ink`, placeholder `mute` |
| **Textarea** | min-height 96px, padding `md`, radius `md`, resize vertikal saja |
| **Dropdown select** | sama dengan input, ikon chevron 16px kanan (`mute`), menu memakai `elev-3` radius `lg` |
| **Hover** | border `mute` |
| **Focus ring** | border `primary` + ring 3px `rgba(37,99,235,0.20)` (`0 0 0 3px`) |
| **Error state** | border `error`, ring `rgba(220,38,38,0.20)`, pesan error di bawah field |
| **Pesan error** | `caption`, warna `error`, ikon 14px, margin atas `xs` |
| **Helper text** | `caption`, `mute` |
| **Disabled** | bg `canvas-soft`, teks `mute`, cursor not-allowed |

### 4.4 Navigasi

| Komponen | Spesifikasi |
|---|---|
| **Sticky navbar atas** | tinggi 64px, `position: sticky; top: 0`, bg `rgba(255,255,255,0.85)` + `backdrop-blur`, border bawah 1px `hairline`; saat scroll memakai `elev-4`. Isi: logo kiri, menu tengah/kiri, avatar & aksi kanan. |
| **Sidebar dashboard** | lebar 256px, bg `canvas-soft`, border kanan 1px `hairline`, padding `lg`. Berisi logo, grup menu, dan profil pengguna di bawah. Mobile: drawer overlay dengan `elev-5`. |
| **Item menu — default** | tinggi 40px, padding `px-3`, radius `md`, teks `button-md` `body`, ikon 20px `mute` |
| **Item menu — hover** | bg `hairline` opacity 60%, teks `ink` |
| **Item menu — active** | bg `primary-soft`, teks `primary-ink`, ikon `primary`, indikator kiri 2px `primary` |
| **Item menu — focus** | ring 2px `primary` |
| **Footer halaman** | bg `canvas`, border atas 1px `hairline`, padding `2xl` vertikal, teks `caption` `mute` |

Menu per role: **Sales** melihat Dashboard, Prospek Saya, Aktivitas. **Admin** ditambah Semua Prospek, Data Motor, Sumber, Status, Pengguna.

### 4.5 Tabel Data & Modal

**Tabel**

| Bagian | Spesifikasi |
|---|---|
| Wadah | radius `lg`, border 1px `hairline`, bg `surface`, overflow-x auto |
| Header | bg `canvas-soft`, teks `caption` uppercase opsional, `mute`, weight 600, tinggi 40px, border bawah `hairline` |
| Cell | padding `px-4 py-3`, `body-sm`, `body`; kolom utama (nama prospek) `ink` weight 500 |
| Baris | border bawah 1px `hairline`; baris terakhir tanpa border |
| Hover row | bg `canvas-soft` |
| Baris terpilih | bg `primary-soft` |
| Kolom angka | rata kanan, `tabular-nums` |
| Aksi | tombol ikon 32px di kolom paling kanan |
| Mobile | < 640px: ubah baris menjadi **Card list item** atau aktifkan scroll horizontal |
| Empty state | ikon 40px `mute`, judul `heading-md`, teks `body-sm`, tombol Primary |

**Modal**

| Bagian | Spesifikasi |
|---|---|
| Overlay | `rgba(15,23,42,0.5)` + `backdrop-blur-sm` |
| Wadah | bg `surface`, radius `lg`, `elev-5`, lebar 480px (sm) / 640px (md), maks `calc(100vw - 32px)` |
| Header | padding `xl`, judul `heading-md`, tombol tutup (ikon X 20px) di kanan |
| Body | padding `xl`, gap antar field `lg`, scroll internal jika melebihi 80vh |
| Footer | padding `lg xl`, border atas 1px `hairline`, tombol rata kanan (Secondary + Primary/Danger) |
| Perilaku | tutup dengan `Esc` dan klik overlay; fokus dikunci di dalam modal; mobile tampil sebagai bottom sheet |

---

## 5. Komponen Khusus SIMPRO

### 5.1 Prospect Status Tracker

Stepper horizontal yang menunjukkan posisi prospek dalam tahapan (mis. Baru → Follow-up → Hot → Deal). Di mobile berubah menjadi vertikal.

| Aspek | Spesifikasi |
|---|---|
| Node | 24px lingkaran (`full`); selesai: bg `primary` + ikon centang putih; aktif: bg `surface`, border 2px `primary`, titik dalam 8px `primary`; belum: border 2px `hairline-strong` |
| Konektor | tinggi 2px; bagian selesai memakai `--gradient-progress`, sisanya `hairline` |
| Label | `caption` weight 500; aktif `ink`, selesai `body`, belum `mute` |
| Status akhir | **Deal** node `success`; **Batal** node `error` dengan ikon X, konektor sesudahnya berhenti |
| Interaksi | Hanya-baca di kartu ringkasan; di halaman detail, klik node membuka modal konfirmasi perubahan status |
| Aksesibilitas | `role="list"`, node aktif `aria-current="step"` |

### 5.2 Prospect Summary Card

Ringkasan prospek di daftar dan dashboard.

| Aspek | Spesifikasi |
|---|---|
| Wadah | Card list item (`elev-1`, radius `lg`, padding `lg`) |
| Baris 1 | Avatar inisial 40px (`primary-soft`, teks `primary-ink`) + nama prospek (`heading-md`) + Badge status di kanan |
| Baris 2 | Motor diminati (ikon motor 16px + `body-sm`), sumber prospek (chip `caption`) |
| Baris 3 | Sales penanggung jawab (avatar 20px + nama, `caption` `mute`) |
| Footer | Aktivitas terakhir: "Phone · 2 hari lalu" (`caption` `mute`) + tombol kecil "Detail" |
| Hover | `elev-2`, border `primary-softer` |
| Peringatan | Bila tanpa aktivitas > N hari, tampilkan titik `warning` 8px di samping aktivitas terakhir |

### 5.3 Follow-up Activity Card

Kartu untuk satu aktivitas Phone atau Visit, dan juga bentuk formulir ringkas untuk menambah aktivitas.

| Aspek | Spesifikasi |
|---|---|
| Wadah | Card soft (`canvas-soft`, border `hairline`, radius `lg`, padding `lg`) |
| Header | Ikon jenis aktivitas dalam lingkaran 32px + judul "Phone" / "Visit" (`body-md`, weight 600) + tanggal & jam (`caption` `mute`) di kanan |
| Isi | Catatan hasil follow-up (`body-sm`, `body`), maks 3 baris + "Lihat selengkapnya" |
| Meta | Nama sales pencatat + perubahan status (jika ada): badge lama → badge baru |
| Warna aksen | Phone: ikon di atas `primary-soft`; Visit: ikon di atas `#ECFEFF` (teks `#0E7490`) |
| Mode form | Segmented control **Phone / Visit** (chip pill), field tanggal-waktu, textarea catatan, select status baru (opsional), tombol Primary "Simpan aktivitas" |
| Aturan | Aktivitas yang tersimpan bersifat riwayat dan tidak ditimpa aktivitas baru |

### 5.4 Phone & Visit Activity Timeline

Daftar kronologis seluruh aktivitas satu prospek, terbaru di atas.

| Aspek | Spesifikasi |
|---|---|
| Garis vertikal | lebar 2px `hairline`, posisi 16px dari kiri |
| Node | 32px `full`, bg `surface`, ring 2px sesuai jenis (Phone `primary`, Visit `accent`), ikon 16px di dalamnya |
| Konten | Follow-up Activity Card (5.3) dengan jarak `xl` antar item |
| Penanda tanggal | Chip `caption` `mute` pada bg `canvas-soft`, radius `pill`, sebagai pemisah per hari |
| Perubahan status | Item khusus kecil: node 12px `primary`, teks `body-sm` "Status berubah: Baru → Follow-up" |
| Empty state | "Belum ada aktivitas" + tombol Primary "Tambah aktivitas" |
| Mobile | Node mengecil ke 28px, kartu penuh lebar |

---

## 6. Implementasi Tailwind CSS

### 6.1 `tailwind.config.js`

```js
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#2563EB',
          deep: '#1D4ED8',
          soft: '#EFF6FF',
          softer: '#DBEAFE',
          ink: '#1E3A8A',
        },
        accent: { DEFAULT: '#06B6D4', ink: '#0E7490', soft: '#ECFEFF' },
        canvas: { DEFAULT: '#FFFFFF', soft: '#F8FAFC' },
        surface: '#FFFFFF',
        hairline: { DEFAULT: '#E2E8F0', strong: '#CBD5E1' },
        ink: '#0F172A',
        body: '#334155',
        mute: '#64748B',
        'on-primary': '#FFFFFF',
        success: { DEFAULT: '#16A34A', soft: '#F0FDF4', ink: '#166534' },
        warning: { DEFAULT: '#D97706', soft: '#FFFBEB', ink: '#92400E' },
        error:   { DEFAULT: '#DC2626', soft: '#FEF2F2', ink: '#991B1B', deep: '#B91C1C' },
        info:    { DEFAULT: '#0284C7', soft: '#F0F9FF', ink: '#075985' },
        neutral: { DEFAULT: '#64748B', soft: '#F1F5F9', ink: '#334155' }, // status "Baru"
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', '"Segoe UI"', 'sans-serif'],
        display: ['"Plus Jakarta Sans"', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      fontSize: {
        'display-xl': ['56px', { lineHeight: '60px', letterSpacing: '-0.03em', fontWeight: '800' }],
        'display-lg': ['40px', { lineHeight: '44px', letterSpacing: '-0.025em', fontWeight: '700' }],
        'heading-lg': ['30px', { lineHeight: '36px', letterSpacing: '-0.02em', fontWeight: '700' }],
        'heading-md': ['20px', { lineHeight: '28px', letterSpacing: '-0.01em', fontWeight: '600' }],
        'body-md':    ['16px', { lineHeight: '24px' }],
        'body-sm':    ['14px', { lineHeight: '20px' }],
        'button-md':  ['14px', { lineHeight: '20px', fontWeight: '500' }],
        caption:      ['12px', { lineHeight: '16px', letterSpacing: '0.01em', fontWeight: '500' }],
      },
      spacing: {
        xxs: '2px', xs: '4px', sm: '8px', md: '12px', lg: '16px',
        xl: '24px', '2xl': '32px', '3xl': '48px', '4xl': '64px', section: '96px',
      },
      borderRadius: {
        none: '0', sm: '4px', md: '8px', lg: '12px', pill: '9999px', full: '50%',
      },
      boxShadow: {
        'elev-0': 'none',
        'elev-1': '0 0 0 1px rgba(15,23,42,0.06), 0 1px 2px rgba(15,23,42,0.04)',
        'elev-2': '0 0 0 1px rgba(15,23,42,0.06), 0 1px 2px rgba(15,23,42,0.04), 0 4px 8px -2px rgba(15,23,42,0.06)',
        'elev-3': '0 0 0 1px rgba(15,23,42,0.06), 0 2px 4px rgba(15,23,42,0.04), 0 8px 16px -4px rgba(15,23,42,0.08)',
        'elev-4': '0 0 0 1px rgba(15,23,42,0.06), 0 2px 4px rgba(15,23,42,0.04), 0 12px 24px -6px rgba(15,23,42,0.10), 0 24px 48px -12px rgba(15,23,42,0.08)',
        'elev-5': '0 0 0 1px rgba(15,23,42,0.06), 0 4px 8px rgba(15,23,42,0.04), 0 16px 32px -8px rgba(15,23,42,0.12), 0 32px 64px -16px rgba(15,23,42,0.16)',
        'focus': '0 0 0 3px rgba(37,99,235,0.20)',
        'focus-error': '0 0 0 3px rgba(220,38,38,0.20)',
      },
      backgroundImage: {
        'brand': 'linear-gradient(135deg, #2563EB 0%, #06B6D4 100%)',
        'brand-deep': 'linear-gradient(135deg, #1E3A8A 0%, #2563EB 55%, #06B6D4 100%)',
        'glow': 'radial-gradient(60% 60% at 50% 0%, rgba(37,99,235,0.18) 0%, rgba(255,255,255,0) 100%)',
        'progress': 'linear-gradient(90deg, #2563EB 0%, #06B6D4 100%)',
      },
      maxWidth: { container: '1280px' },
    },
  },
  plugins: [require('@tailwindcss/forms')],
};
```

### 6.2 Contoh Penggunaan (Blade)

**Button Primary**

```html
<button type="submit"
  class="inline-flex h-11 items-center justify-center gap-2 rounded-pill bg-primary px-6 text-button-md text-on-primary
         transition hover:bg-primary-deep hover:shadow-elev-2 active:scale-[0.98]
         focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2
         disabled:opacity-50 disabled:pointer-events-none">
  Simpan prospek
</button>
```

**Badge Status**

```html
{{-- Contoh: status "Follow-up" --}}
<span class="inline-flex items-center gap-1.5 rounded-pill bg-primary-soft px-2.5 py-1 text-caption text-primary-ink">
  <span class="h-1.5 w-1.5 rounded-full bg-primary"></span>
  Follow-up
</span>

{{-- Contoh: status "Deal" --}}
<span class="inline-flex items-center gap-1.5 rounded-pill bg-success-soft px-2.5 py-1 text-caption text-success-ink">
  <span class="h-1.5 w-1.5 rounded-full bg-success"></span>
  Deal
</span>
```

**Card Utama (Statistik Dashboard)**

```html
<div class="rounded-lg bg-surface p-xl shadow-elev-1">
  <p class="text-caption text-mute">Total prospek aktif</p>
  <p class="mt-sm bg-brand bg-clip-text text-heading-lg tabular-nums text-transparent">128</p>
  <p class="mt-xs text-body-sm text-body">
    <span class="font-medium text-success">+12</span> dibanding bulan lalu
  </p>
</div>
```

**Input Form**

```html
<div>
  <label for="nama" class="mb-xs block text-body-sm font-medium text-ink">
    Nama prospek <span class="text-error">*</span>
  </label>
  <input id="nama" name="nama" type="text" placeholder="Masukkan nama lengkap"
    class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-md text-ink
           placeholder:text-mute hover:border-mute
           focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0
           @error('nama') border-error focus:border-error focus:shadow-focus-error @enderror">
  @error('nama')
    <p class="mt-xs text-caption text-error">{{ $message }}</p>
  @enderror
</div>
```

**Hero Headline**

```html
<section class="relative overflow-hidden bg-canvas">
  <div class="absolute inset-0 bg-glow"></div>
  <div class="relative mx-auto max-w-container px-4 py-section text-center sm:px-6 lg:px-8">
    <h1 class="font-display text-display-lg text-ink md:text-display-xl">
      Pantau setiap prospek,
      <span class="bg-brand bg-clip-text text-transparent">tutup lebih banyak penjualan.</span>
    </h1>
    <p class="mx-auto mt-lg max-w-prose text-body-md text-body">
      SIMPRO membantu tim dealer mencatat prospek dan riwayat follow-up Phone &amp; Visit dalam satu tempat.
    </p>
    <div class="mt-2xl flex flex-col items-center justify-center gap-md sm:flex-row">
      <a href="{{ route('login') }}"
         class="inline-flex h-11 items-center rounded-pill bg-primary px-6 text-button-md text-on-primary hover:bg-primary-deep">Masuk</a>
      <a href="#fitur"
         class="inline-flex h-11 items-center rounded-pill border border-hairline-strong bg-surface px-6 text-button-md text-ink hover:bg-canvas-soft">Lihat fitur</a>
    </div>
  </div>
</section>
```

---

## 7. Do's and Don'ts

### Do
1. **Gunakan token**, bukan hex mentah, untuk warna, spacing, radius, dan shadow.
2. **Gunakan Inter** untuk seluruh UI dashboard; Plus Jakarta Sans hanya untuk hero/tagline.
3. **Reservasikan `primary`** untuk aksi utama, tautan, dan state aktif; satu tombol Primary per area.
4. **Pakai pemetaan warna status prospek** yang sama di badge, tracker, timeline, dan tabel.
5. **Sediakan state lengkap** (default, hover, active, focus, disabled, error) pada setiap komponen interaktif.
6. **Gunakan focus ring yang jelas** dan pastikan kontras teks minimal WCAG AA (4.5:1).
7. **Pakai bayangan berlapis + hairline ring** (`elev-*`), dan border `hairline` untuk pemisah.
8. **Desain mobile-first**; target sentuh ≥ 44px dan tabel harus punya versi mobile.

### Don't
1. **Jangan** menambah warna di luar palet (mis. ungu, oranye pekat) untuk dekorasi.
2. **Jangan** memakai gradient pada tombol standar, tabel, atau latar halaman penuh.
3. **Jangan** mengandalkan warna saja untuk status; selalu sertakan teks/ikon pada badge.
4. **Jangan** mencampur radius (mis. tombol kotak di samping tombol pill dalam satu grup aksi).
5. **Jangan** memakai `box-shadow` tunggal yang tebal dan gelap atau efek neon/glow berlebihan.
6. **Jangan** memakai lebih dari 2 font family, teks < 12px, atau ALL CAPS untuk teks panjang.
7. **Jangan** menampilkan Font 2 (Plus Jakarta Sans) di dalam area dashboard.
8. **Jangan** menghapus outline fokus tanpa menggantinya dengan ring yang setara.
