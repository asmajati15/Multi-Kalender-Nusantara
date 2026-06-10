````md
# Implementation Plan — Landing Page Kalender Hijriyah

## 1. Tujuan Halaman

Membuat landing page untuk aplikasi **Kalender Hijriyah** menggunakan Laravel dengan stack UI berbasis **shadcn/ui**.

Landing page ini berfungsi sebagai halaman pengenalan fitur utama aplikasi, yaitu:

- Menampilkan kalender Masehi dan Hijriyah secara berdampingan.
- Menyediakan fitur konversi tanggal Masehi ke Hijriyah.
- Menyediakan tampilan kalender utama yang dapat ditukar antara Masehi dan Hijriyah.
- Memberikan pengalaman visual modern melalui floating navbar, hero section, square parallax pattern, typing animation, dan CTA yang mengarah langsung ke bagian konversi tanggal.

Data kalender belum perlu diintegrasikan pada tahap ini. Fokus implementasi hanya pada **layout**, **struktur halaman**, **komponen UI**, dan **placeholder data**.

---

## 2. Prasyarat

Project Laravel sudah menggunakan shadcn/ui dengan perintah berikut:

```bash
npx shadcn@latest init --preset b4Xd7FUMDY --template laravel --pointer
````

Pastikan komponen shadcn/ui dasar sudah tersedia dan dapat digunakan di project Laravel.

Komponen yang direkomendasikan untuk digunakan:

```bash
npx shadcn@latest add button card input label tabs badge separator
```

Opsional jika dibutuhkan:

```bash
npx shadcn@latest add popover calendar select switch
```

---

## 3. Struktur Halaman

Landing page terdiri dari beberapa section utama:

1. Floating Navbar
2. Hero Section
3. Section Konversi Tanggal Masehi ke Hijriyah
4. Section Preview Kalender Masehi dan Hijriyah
5. Section Toggle Kalender Utama
6. Footer Sederhana

Urutan halaman:

```txt
Floating Navbar
↓
Hero Section
↓
Konversi Tanggal Masehi ke Hijriyah
↓
Preview Kalender
↓
Toggle Tampilan Kalender Utama
↓
Footer
```

---

## 4. Floating Navbar

### Tujuan

Navbar ditampilkan secara floating di bagian atas halaman dengan efek modern, transparan, dan tetap terlihat saat pengguna scroll.

### Elemen Navbar

* Logo / nama aplikasi: `Kalender Hijriyah`
* Menu navigasi:

  * Beranda
  * Konversi
  * Kalender
  * Fitur
* CTA kecil:

  * `Mulai Konversi`

### Perilaku

* Navbar menggunakan posisi `fixed`.
* Berada di tengah secara horizontal.
* Memiliki background semi-transparent.
* Menggunakan efek blur.
* Memiliki border halus.
* CTA `Mulai Konversi` mengarah ke section `#konversi`.

### Contoh struktur layout

```txt
[Kalender Hijriyah]    Beranda | Konversi | Kalender | Fitur    [Mulai Konversi]
```

### Catatan Styling

Gunakan class Tailwind seperti:

```txt
fixed top-4 left-1/2 z-50 -translate-x-1/2
rounded-full border bg-background/80 backdrop-blur-xl
shadow-sm
```

---

## 5. Hero Section

### Tujuan

Hero section menjadi area utama yang memperkenalkan aplikasi dan menjelaskan kemudahan penggunaan Kalender Hijriyah.

### Elemen Hero

* Background square parallax pattern.
* Badge kecil, contoh:

  * `Kalender Islam Digital`
* Heading utama:

  * `Kalender Hijriyah Modern untuk Kebutuhan Harian`
* Layer teks dengan typing animation.
* Deskripsi singkat.
* CTA utama:

  * `Konversi Sekarang`
* CTA sekunder:

  * `Lihat Kalender`
* Preview ringkas kalender atau card tanggal hari ini.

### Copywriting Hero

Judul:

```txt
Kalender Hijriyah Modern untuk Kebutuhan Harian
```

Typing animation dapat menampilkan beberapa teks berikut:

```txt
Konversi tanggal Masehi ke Hijriyah dengan mudah.
Lihat tanggal Hijriyah dan Masehi secara berdampingan.
Tukar tampilan kalender utama sesuai kebutuhan.
Gunakan kalender Islam digital yang rapi dan praktis.
```

Deskripsi:

```txt
Pantau tanggal Masehi dan Hijriyah dalam satu tampilan modern. Cocok untuk kebutuhan ibadah, agenda harian, perencanaan acara Islam, dan pengecekan tanggal penting.
```

CTA:

```txt
Konversi Sekarang
Lihat Kalender
```

### Background Square Parallax Pattern

Buat background berupa grid kotak-kotak halus menggunakan CSS.

Contoh pendekatan:

```css
background-image:
  linear-gradient(to right, rgba(120, 120, 120, 0.12) 1px, transparent 1px),
  linear-gradient(to bottom, rgba(120, 120, 120, 0.12) 1px, transparent 1px);
background-size: 48px 48px;
```

Tambahkan efek layer gradient:

```txt
absolute inset-0 bg-gradient-to-b from-background/20 via-background/70 to-background
```

### Catatan Parallax

Untuk tahap awal, efek parallax cukup dibuat secara visual menggunakan:

* `background-attachment: fixed`, atau
* transform ringan berdasarkan scroll menggunakan JavaScript sederhana, atau
* pseudo-element background yang bergerak perlahan.

Karena fokus saat ini adalah layout, efek parallax boleh dibuat sebagai dekorasi statis terlebih dahulu.

---

## 6. Typing Animation

### Tujuan

Typing animation digunakan untuk menampilkan fitur dan kemudahan aplikasi secara dinamis di hero section.

### Teks Typing

Gunakan array teks:

```js
const typingTexts = [
  'Konversi tanggal Masehi ke Hijriyah dengan mudah.',
  'Lihat kalender Masehi dan Hijriyah secara berdampingan.',
  'Tukar kalender utama antara Masehi dan Hijriyah.',
  'Pantau tanggal penting Islam dalam tampilan modern.',
]
```

### Perilaku

* Teks diketik satu per satu.
* Setelah selesai, teks berhenti sebentar.
* Kemudian teks dihapus.
* Lanjut ke teks berikutnya.
* Loop terus menerus.

### Catatan Implementasi

Jika project menggunakan React/Inertia pada Laravel, typing animation dapat dibuat sebagai komponen client-side.

Jika halaman masih Blade biasa, typing animation dapat dibuat dengan JavaScript vanilla.

---

## 7. Section Konversi Tanggal Masehi ke Hijriyah

### ID Section

Gunakan ID berikut agar CTA dari hero dapat scroll langsung ke section ini:

```html
<section id="konversi">
```

### Tujuan

Menampilkan form layout untuk konversi tanggal Masehi ke Hijriyah.

### Elemen Section

* Judul section:

  * `Konversi Tanggal Masehi ke Hijriyah`
* Deskripsi:

  * `Pilih tanggal Masehi, lalu lihat hasil konversi tanggal Hijriyah secara langsung.`
* Input tanggal Masehi.
* Tombol:

  * `Konversi Tanggal`
* Card hasil konversi.
* Placeholder hasil karena data belum disiapkan.

### Placeholder Hasil

```txt
Senin, 10 Juni 2026
24 Dzulhijjah 1447 H
```

Atau:

```txt
Hasil konversi akan ditampilkan di sini.
```

### Layout

Gunakan card dua kolom pada desktop:

```txt
[Form Konversi]     [Hasil Konversi]
```

Pada mobile:

```txt
[Form Konversi]
[Hasil Konversi]
```

### Komponen shadcn/ui yang digunakan

* Card
* Input
* Label
* Button
* Badge

---

## 8. Section Preview Kalender Masehi dan Hijriyah

### Tujuan

Menampilkan tampilan kalender Masehi yang setiap tanggalnya disandingkan dengan tanggal Hijriyah.

### Konsep Tampilan

Kalender utama menampilkan bulan Masehi, misalnya:

```txt
Juni 2026
```

Setiap cell tanggal menampilkan:

```txt
10
24 Dzulhijjah
```

Tanggal Masehi dibuat lebih dominan, sedangkan tanggal Hijriyah lebih kecil.

### Contoh Cell Kalender

```txt
+----------------+
| 10             |
| 24 Dzulhijjah  |
+----------------+
```

### Elemen Section

* Judul:

  * `Kalender Masehi dan Hijriyah Berdampingan`
* Deskripsi:

  * `Lihat tanggal Masehi dan Hijriyah dalam satu tampilan kalender yang mudah dibaca.`
* Header bulan:

  * Tombol bulan sebelumnya
  * Nama bulan dan tahun
  * Tombol bulan berikutnya
* Grid hari:

  * Sen
  * Sel
  * Rab
  * Kam
  * Jum
  * Sab
  * Min
* Grid tanggal kalender.
* Placeholder data tanggal.

### Placeholder Data

Karena data belum disiapkan, gunakan data dummy seperti:

```js
const calendarDays = [
  { gregorian: 1, hijri: '15 Dzulhijjah' },
  { gregorian: 2, hijri: '16 Dzulhijjah' },
  { gregorian: 3, hijri: '17 Dzulhijjah' },
  { gregorian: 4, hijri: '18 Dzulhijjah' },
  { gregorian: 5, hijri: '19 Dzulhijjah' },
]
```

Nantinya data ini dapat diganti dari API atau service konversi tanggal.

---

## 9. Tombol Tukar Kalender Utama

### Tujuan

Memberikan opsi kepada pengguna untuk menukar tampilan utama kalender.

Default:

```txt
Kalender utama: Masehi
Tanggal pendamping: Hijriyah
```

Setelah ditukar:

```txt
Kalender utama: Hijriyah
Tanggal pendamping: Masehi
```

### Elemen UI

Gunakan tombol atau switch:

```txt
[Tukar ke Kalender Hijriyah]
```

Jika mode sudah Hijriyah:

```txt
[Tukar ke Kalender Masehi]
```

### Contoh Tampilan Mode Masehi

```txt
10
24 Dzulhijjah
```

### Contoh Tampilan Mode Hijriyah

```txt
24
10 Juni
```

### State yang Dibutuhkan

```js
const [calendarMode, setCalendarMode] = useState('gregorian')
```

Nilai yang digunakan:

```txt
gregorian
hijri
```

### Perilaku

Jika `calendarMode === 'gregorian'`:

* Tanggal utama adalah Masehi.
* Tanggal kecil adalah Hijriyah.

Jika `calendarMode === 'hijri'`:

* Tanggal utama adalah Hijriyah.
* Tanggal kecil adalah Masehi.

---

## 10. Section Fitur

### Tujuan

Menjelaskan fitur utama aplikasi secara ringkas.

### Fitur yang Ditampilkan

1. Konversi Tanggal Cepat
2. Kalender Ganda
3. Mode Kalender Hijriyah
4. Tampilan Responsif

### Copywriting Fitur

#### Konversi Tanggal Cepat

```txt
Ubah tanggal Masehi ke Hijriyah dengan tampilan yang sederhana dan mudah dipahami.
```

#### Kalender Ganda

```txt
Tanggal Masehi dan Hijriyah ditampilkan berdampingan dalam satu grid kalender.
```

#### Mode Kalender Hijriyah

```txt
Tukar kalender utama menjadi Hijriyah untuk kebutuhan ibadah dan agenda Islam.
```

#### Tampilan Responsif

```txt
Layout dirancang agar nyaman digunakan di desktop, tablet, maupun perangkat mobile.
```

### Komponen shadcn/ui

Gunakan Card untuk setiap fitur.

Layout desktop:

```txt
[Card Fitur] [Card Fitur] [Card Fitur] [Card Fitur]
```

Layout mobile:

```txt
[Card Fitur]
[Card Fitur]
[Card Fitur]
[Card Fitur]
```

---

## 11. Footer

### Tujuan

Footer dibuat sederhana sebagai penutup halaman.

### Elemen Footer

* Nama aplikasi:

  * `Kalender Hijriyah`
* Deskripsi singkat:

  * `Kalender Islam digital untuk membantu melihat dan mengonversi tanggal Hijriyah dengan mudah.`
* Copyright:

  * `© 2026 Kalender Hijriyah. All rights reserved.`
* Link sederhana:

  * Beranda
  * Konversi
  * Kalender

### Layout Footer

```txt
Kalender Hijriyah
Kalender Islam digital untuk kebutuhan harian.

Beranda | Konversi | Kalender

© 2026 Kalender Hijriyah. All rights reserved.
```

---

## 12. Rekomendasi Komponen

Komponen yang sebaiknya dibuat agar struktur lebih rapi:

```txt
resources/js/Components/Landing/FloatingNavbar.jsx
resources/js/Components/Landing/HeroSection.jsx
resources/js/Components/Landing/TypingText.jsx
resources/js/Components/Landing/ConversionSection.jsx
resources/js/Components/Landing/CalendarPreview.jsx
resources/js/Components/Landing/FeatureSection.jsx
resources/js/Components/Landing/Footer.jsx
```

Jika project menggunakan Blade tanpa React, struktur dapat disesuaikan menjadi partial Blade:

```txt
resources/views/components/landing/floating-navbar.blade.php
resources/views/components/landing/hero-section.blade.php
resources/views/components/landing/conversion-section.blade.php
resources/views/components/landing/calendar-preview.blade.php
resources/views/components/landing/feature-section.blade.php
resources/views/components/landing/footer.blade.php
```

---

## 13. Rekomendasi Route

Tambahkan route landing page:

```php
Route::get('/', function () {
    return view('landing.kalender-hijriyah');
})->name('landing.kalender-hijriyah');
```

Jika menggunakan Inertia:

```php
Route::get('/', function () {
    return Inertia::render('Landing/KalenderHijriyah');
})->name('landing.kalender-hijriyah');
```

---

## 14. Rekomendasi Layout Halaman

Jika menggunakan React/Inertia, halaman utama dapat memiliki struktur seperti ini:

```jsx
export default function KalenderHijriyahLanding() {
  return (
    <main className="min-h-screen bg-background text-foreground">
      <FloatingNavbar />

      <HeroSection />

      <ConversionSection />

      <CalendarPreview />

      <FeatureSection />

      <Footer />
    </main>
  )
}
```

Jika menggunakan Blade:

```blade
<main class="min-h-screen bg-background text-foreground">
    @include('components.landing.floating-navbar')

    @include('components.landing.hero-section')

    @include('components.landing.conversion-section')

    @include('components.landing.calendar-preview')

    @include('components.landing.feature-section')

    @include('components.landing.footer')
</main>
```

---

## 15. Detail Visual Design

### Warna

Gunakan warna bawaan dari shadcn/ui agar konsisten:

```txt
background
foreground
muted
muted-foreground
primary
primary-foreground
border
card
card-foreground
```

### Style Umum

Gunakan pendekatan clean, modern, dan Islami tanpa terlalu banyak ornamen.

Rekomendasi visual:

* Background netral.
* Grid pattern halus.
* Card rounded.
* Shadow lembut.
* Border tipis.
* CTA jelas.
* Spacing lega.
* Typography besar pada hero.

### Typography

Hero heading:

```txt
text-4xl md:text-6xl font-bold tracking-tight
```

Deskripsi:

```txt
text-base md:text-lg text-muted-foreground
```

Section title:

```txt
text-2xl md:text-4xl font-bold tracking-tight
```

---

## 16. Responsive Design

### Desktop

* Navbar horizontal.
* Hero dua kolom.
* Conversion section dua kolom.
* Calendar grid penuh.
* Feature section empat kolom.

### Tablet

* Navbar tetap ringkas.
* Hero dapat tetap dua kolom atau satu kolom.
* Conversion section bisa dua kolom.
* Feature section dua kolom.

### Mobile

* Navbar lebih compact.
* Menu navigasi dapat disembunyikan.
* Hero satu kolom.
* CTA dibuat stacked.
* Conversion section satu kolom.
* Calendar tetap tujuh kolom tetapi cell lebih kecil.
* Feature section satu kolom.

---

## 17. Interaksi yang Dibutuhkan

### Smooth Scroll

CTA `Konversi Sekarang` mengarah ke:

```txt
#konversi
```

CTA `Lihat Kalender` mengarah ke:

```txt
#kalender
```

Gunakan CSS:

```css
html {
  scroll-behavior: smooth;
}
```

### Toggle Kalender Utama

Tombol toggle mengubah state:

```txt
Masehi → Hijriyah
Hijriyah → Masehi
```

### Typing Animation

Typing animation berjalan otomatis di hero section.

### Calendar Navigation

Tombol bulan sebelumnya dan berikutnya cukup dibuat sebagai layout terlebih dahulu.

Belum perlu mengambil data kalender asli.

---

## 18. Placeholder Data Kalender

Gunakan data dummy terlebih dahulu:

```js
const calendarDays = Array.from({ length: 35 }).map((_, index) => ({
  gregorianDay: index + 1 <= 30 ? index + 1 : '',
  gregorianMonth: 'Juni',
  hijriDay: index + 15 <= 30 ? index + 15 : index - 14,
  hijriMonth: index + 15 <= 30 ? 'Dzulhijjah' : 'Muharram',
}))
```

Data ini hanya digunakan untuk kebutuhan layout.

Nantinya dapat diganti dengan:

* API internal Laravel.
* Library konversi tanggal.
* Database kalender Hijriyah.
* Service eksternal.

---

## 19. Acceptance Criteria

Landing page dianggap selesai pada tahap layout jika memenuhi kriteria berikut:

* Floating navbar tampil di bagian atas halaman.
* Hero section memiliki background square pattern.
* Hero memiliki typing animation.
* CTA `Konversi Sekarang` mengarah ke section konversi.
* Section konversi tanggal sudah memiliki input, tombol, dan card hasil.
* Section kalender menampilkan tanggal Masehi dan Hijriyah berdampingan.
* Terdapat tombol untuk menukar kalender utama menjadi Hijriyah.
* Mode kalender utama dapat berubah secara visual.
* Footer sederhana sudah tersedia.
* Layout responsif di desktop dan mobile.
* Tidak wajib menggunakan data kalender asli.
* Tidak wajib melakukan proses konversi tanggal asli.
* Tidak wajib menyimpan data ke database.

---

## 20. Scope yang Tidak Dikerjakan pada Tahap Ini

Beberapa hal berikut tidak perlu dikerjakan pada tahap layout:

* Integrasi data Hijriyah asli.
* Algoritma konversi tanggal.
* Penyimpanan data kalender ke database.
* API backend konversi tanggal.
* Validasi akurasi kalender Hijriyah.
* Integrasi event Islam.
* Sistem pengingat tanggal penting.
* Multi bahasa.
* Dark mode custom.
* Authentication user.

---

## 21. Tahapan Implementasi

### Tahap 1 — Setup Komponen

* Pastikan shadcn/ui sudah berjalan.
* Tambahkan komponen yang dibutuhkan.
* Siapkan route landing page.
* Siapkan file halaman utama.

### Tahap 2 — Buat Struktur Landing Page

* Buat komponen Floating Navbar.
* Buat komponen Hero Section.
* Buat komponen Conversion Section.
* Buat komponen Calendar Preview.
* Buat komponen Feature Section.
* Buat komponen Footer.

### Tahap 3 — Implementasi Visual

* Tambahkan square background pattern pada hero.
* Tambahkan gradient layer.
* Tambahkan styling card.
* Tambahkan spacing antar section.
* Rapikan responsive design.

### Tahap 4 — Implementasi Interaksi Dasar

* Tambahkan smooth scroll.
* Tambahkan typing animation.
* Tambahkan toggle kalender utama.
* Tambahkan placeholder calendar navigation.

### Tahap 5 — Review Layout

* Cek tampilan desktop.
* Cek tampilan tablet.
* Cek tampilan mobile.
* Pastikan CTA bekerja.
* Pastikan toggle kalender berubah secara visual.
* Pastikan halaman tetap rapi tanpa data asli.

---

## 22. Catatan Pengembangan Lanjutan

Setelah layout selesai, tahap berikutnya dapat dilanjutkan ke:

* Integrasi library konversi tanggal Masehi ke Hijriyah.
* Pembuatan API Laravel untuk konversi tanggal.
* Pembuatan data kalender bulanan.
* Penambahan daftar hari besar Islam.
* Penambahan fitur pencarian tanggal.
* Penambahan mode print kalender.
* Penambahan event/reminder tanggal penting.
* Penambahan dukungan dark mode yang lebih matang.

```
```
