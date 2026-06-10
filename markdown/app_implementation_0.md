````md
# Implementation Plan — Pengembangan Multi Calendar: Hijriyah, Jawa, Sunda, dan Masehi

## 1. Tujuan Pengembangan

Mengembangkan landing page dan fitur kalender yang sebelumnya hanya berfokus pada **Kalender Hijriyah**, menjadi sistem **Multi Calendar** yang mendukung beberapa sistem kalender sekaligus.

Sistem kalender yang akan didukung:

- Kalender Masehi
- Kalender Hijriyah
- Kalender Jawa
- Kalender Sunda

Fitur tambahan ini akan diterapkan pada dua area utama:

1. Fitur konversi tanggal.
2. Tampilan tanggal pada kalender utama.

Selain itu, halaman juga akan menggunakan tema warna baru, yaitu:

- **Soft Sage** sebagai warna utama.
- **Soft Gold** sebagai warna ornamen atau aksen dekoratif.

Background square parallax pattern yang sebelumnya dibuat juga akan dikembangkan menjadi animasi parallax yang lebih hidup dan responsif terhadap scroll.

---

## 2. Ruang Lingkup Pengembangan

### Fitur yang Ditambahkan

- Menambahkan pilihan sistem kalender pada form konversi.
- Menambahkan pilihan sumber tanggal dan tujuan tanggal.
- Mendukung konversi dari kalender lain ke Masehi.
- Menampilkan nama hari dari masing-masing sistem kalender.
- Menampilkan nama bulan dari masing-masing sistem kalender.
- Menambahkan Kalender Jawa sebagai pilihan tambahan.
- Menambahkan Kalender Sunda sebagai pilihan tambahan.
- Menambahkan tampilan tanggal multi-calendar pada grid kalender.
- Menambahkan mode tampilan utama berdasarkan sistem kalender yang dipilih.
- Menambahkan tema visual Soft Sage dan Soft Gold.
- Menganimasikan square parallax background pada hero section.

### Fitur yang Belum Wajib Dikerjakan

Karena fokus tahap ini masih pada layout dan perencanaan struktur, beberapa hal berikut belum wajib diimplementasikan secara penuh:

- Akurasi algoritma konversi semua kalender.
- Database tanggal lengkap.
- API backend final.
- Validasi kalender Jawa dan Sunda secara historis.
- Integrasi hari besar atau event adat.
- Penyimpanan preferensi kalender user.

---

## 3. Konsep Utama Multi Calendar

Fitur Multi Calendar memungkinkan user melihat dan mengonversi tanggal dari satu sistem kalender ke sistem kalender lainnya.

Contoh kebutuhan:

```txt
Masehi → Hijriyah
Masehi → Jawa
Masehi → Sunda
Hijriyah → Masehi
Jawa → Masehi
Sunda → Masehi
Jawa → Hijriyah
Sunda → Jawa
````

Pada tahap awal, sistem dapat difokuskan pada konversi ke Masehi sebagai titik tengah.

Contoh pendekatan logika:

```txt
Tanggal Input → Konversi ke Masehi → Konversi ke Kalender Tujuan
```

Dengan pendekatan ini, Masehi dapat digunakan sebagai kalender referensi utama.

---

## 4. Sistem Kalender yang Didukung

### 4.1 Kalender Masehi

Kalender Masehi digunakan sebagai sistem kalender umum.

Informasi yang ditampilkan:

* Tanggal
* Nama hari
* Nama bulan
* Tahun

Contoh:

```txt
Rabu, 10 Juni 2026
```

Struktur data tampilan:

```js
{
  calendar: 'gregorian',
  dayName: 'Rabu',
  day: 10,
  monthName: 'Juni',
  year: 2026
}
```

---

### 4.2 Kalender Hijriyah

Kalender Hijriyah digunakan untuk kebutuhan kalender Islam.

Informasi yang ditampilkan:

* Tanggal Hijriyah
* Nama hari
* Nama bulan Hijriyah
* Tahun Hijriyah

Contoh:

```txt
Rabu, 24 Dzulhijjah 1447 H
```

Contoh nama bulan Hijriyah:

```txt
Muharram
Safar
Rabiul Awal
Rabiul Akhir
Jumadil Awal
Jumadil Akhir
Rajab
Syaban
Ramadhan
Syawal
Dzulqaidah
Dzulhijjah
```

Struktur data tampilan:

```js
{
  calendar: 'hijri',
  dayName: 'Rabu',
  day: 24,
  monthName: 'Dzulhijjah',
  year: 1447,
  suffix: 'H'
}
```

---

### 4.3 Kalender Jawa

Kalender Jawa digunakan sebagai tambahan sistem kalender tradisional.

Informasi yang ditampilkan:

* Tanggal Jawa
* Nama hari
* Nama pasaran
* Nama bulan Jawa
* Tahun Jawa

Contoh:

```txt
Rabu Legi, 24 Besar 1959 J
```

Elemen khas Kalender Jawa:

```txt
Hari umum:
Senin
Selasa
Rabu
Kamis
Jumat
Sabtu
Minggu

Pasaran:
Legi
Pahing
Pon
Wage
Kliwon

Bulan Jawa:
Sura
Sapar
Mulud
Bakda Mulud
Jumadil Awal
Jumadil Akhir
Rejeb
Ruwah
Pasa
Sawal
Dulkangidah
Besar
```

Struktur data tampilan:

```js
{
  calendar: 'javanese',
  dayName: 'Rabu',
  marketDay: 'Legi',
  day: 24,
  monthName: 'Besar',
  year: 1959,
  suffix: 'J'
}
```

---

### 4.4 Kalender Sunda

Kalender Sunda digunakan sebagai tambahan sistem kalender tradisional Sunda.

Informasi yang ditampilkan:

* Tanggal Sunda
* Nama hari
* Nama bulan Sunda
* Tahun Sunda

Contoh:

```txt
Rabu, 20 Kasa 1962 S
```

Contoh nama bulan Sunda:

```txt
Kasa
Karo
Katiga
Kapat
Kalima
Kanem
Kapitu
Kawalu
Kasanga
Kadasa
Hapit Lemah
Hapit Kayu
```

Struktur data tampilan:

```js
{
  calendar: 'sundanese',
  dayName: 'Rabu',
  day: 20,
  monthName: 'Kasa',
  year: 1962,
  suffix: 'S'
}
```

````md
## Update — Detail Hari Kalender Sunda

Pada Kalender Sunda, nama hari tidak hanya menggunakan nama hari umum seperti Senin, Selasa, Rabu, dan seterusnya, tetapi menggunakan nama hari Sunda sebagai berikut:

```txt
Radite  = Minggu
Soma    = Senin
Anggara = Selasa
Buda    = Rabu
Respati = Kamis
Sukra   = Jumat
Tumpek  = Sabtu
````

Selain nama hari tersebut, Kalender Sunda juga memiliki sistem **hari pasaran** seperti Kalender Jawa.

Hari pasaran yang digunakan:

```txt
Legi
Pahing
Pon
Wage
Kliwon
```

Contoh tampilan Kalender Sunda:

```txt
Buda Legi, 20 Kasa 1962 S
```

Dengan format:

```txt
[Nama Hari Sunda] [Pasaran], [Tanggal] [Bulan Sunda] [Tahun] S
```

Contoh lain:

```txt
Radite Kliwon, 1 Kasa 1962 S
Soma Legi, 2 Kasa 1962 S
Anggara Pahing, 3 Kasa 1962 S
Buda Pon, 4 Kasa 1962 S
Respati Wage, 5 Kasa 1962 S
Sukra Kliwon, 6 Kasa 1962 S
Tumpek Legi, 7 Kasa 1962 S
```

---

## Update Struktur Data Kalender Sunda

Struktur data Kalender Sunda perlu ditambahkan properti `sundaneseDayName` dan `marketDay`.

Sebelumnya:

```js
{
  calendar: 'sundanese',
  dayName: 'Rabu',
  day: 20,
  monthName: 'Kasa',
  year: 1962,
  suffix: 'S'
}
```

Menjadi:

```js
{
  calendar: 'sundanese',
  dayName: 'Rabu',
  sundaneseDayName: 'Buda',
  marketDay: 'Legi',
  day: 20,
  monthName: 'Kasa',
  year: 1962,
  suffix: 'S',
  formatted: 'Buda Legi, 20 Kasa 1962 S'
}
```

Keterangan field:

```txt
dayName           = nama hari umum dalam Bahasa Indonesia
sundaneseDayName  = nama hari dalam Kalender Sunda
marketDay         = hari pasaran seperti Legi, Pahing, Pon, Wage, Kliwon
day               = tanggal Kalender Sunda
monthName         = nama bulan Kalender Sunda
year              = tahun Kalender Sunda
suffix            = penanda Kalender Sunda
formatted         = format tampilan akhir
```

---

## Update Nama Hari dan Pasaran Sunda

Tambahkan mapping berikut pada data konstanta kalender.

```js
const sundaneseDayNames = {
  sunday: 'Radite',
  monday: 'Soma',
  tuesday: 'Anggara',
  wednesday: 'Buda',
  thursday: 'Respati',
  friday: 'Sukra',
  saturday: 'Tumpek'
}

const sundaneseDayNameMap = {
  Minggu: 'Radite',
  Senin: 'Soma',
  Selasa: 'Anggara',
  Rabu: 'Buda',
  Kamis: 'Respati',
  Jumat: 'Sukra',
  Sabtu: 'Tumpek'
}

const marketDayNames = [
  'Legi',
  'Pahing',
  'Pon',
  'Wage',
  'Kliwon'
]
```

---

## Update Placeholder Data Multi Calendar

Pada bagian `sundanese`, ubah struktur data menjadi seperti berikut:

```js
sundanese: {
  dayName: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'][index % 7],
  sundaneseDayName: ['Soma', 'Anggara', 'Buda', 'Respati', 'Sukra', 'Tumpek', 'Radite'][index % 7],
  marketDay: ['Legi', 'Pahing', 'Pon', 'Wage', 'Kliwon'][index % 5],
  day: index + 8 <= 30 ? index + 8 : index - 21,
  monthName: index + 8 <= 30 ? 'Kasa' : 'Karo',
  year: index + 8 <= 30 ? 1962 : 1963,
  formatted: `${['Soma', 'Anggara', 'Buda', 'Respati', 'Sukra', 'Tumpek', 'Radite'][index % 7]} ${['Legi', 'Pahing', 'Pon', 'Wage', 'Kliwon'][index % 5]}, ${index + 8 <= 30 ? index + 8 : index - 21} ${index + 8 <= 30 ? 'Kasa' : 'Karo'}`
}
```

Catatan:

```txt
Data pasaran pada contoh di atas masih dummy untuk kebutuhan layout.
Urutan pasaran sebenarnya harus dihitung berdasarkan algoritma kalender yang valid pada tahap implementasi konversi.
```

---

## Update Tampilan Mode Sunda

Pada bagian contoh mode Sunda, ubah menjadi:

```txt
+--------------------------+
| 20                       |
| Kasa                     |
| Buda Legi                |
| Masehi: 10 Juni          |
| Hijriyah: 24 Dzulhijjah  |
| Jawa: 24 Besar           |
+--------------------------+
```

Contoh card hasil Kalender Sunda:

```txt
+--------------------------------------------------+
| Sunda                                            |
| Buda Legi, 20 Kasa 1962 S                        |
+--------------------------------------------------+
```

---

## Update Acceptance Criteria

Tambahkan acceptance criteria berikut:

```txt
- Kalender Sunda menampilkan nama hari Sunda, seperti Radite, Soma, Anggara, Buda, Respati, Sukra, dan Tumpek.
- Kalender Sunda memiliki informasi hari pasaran seperti Kalender Jawa.
- Hasil Kalender Sunda menampilkan format nama hari Sunda + pasaran.
- Grid kalender mode Sunda menampilkan nama hari Sunda dan pasaran.
```

```
```

---

## 5. Pengembangan Form Konversi

### Tujuan

Form konversi tidak hanya mendukung Masehi ke Hijriyah, tetapi mendukung pilihan kalender sumber dan kalender tujuan.

### Elemen Form Baru

Form konversi terdiri dari:

* Pilihan kalender asal.
* Input tanggal berdasarkan kalender asal.
* Pilihan kalender tujuan.
* Tombol konversi.
* Card hasil konversi.
* Ringkasan detail nama hari dan bulan dari masing-masing kalender.

### Field Form

```txt
Kalender Asal
Tanggal
Bulan
Tahun
Kalender Tujuan
Button Konversi
```

### Pilihan Kalender Asal

```txt
Masehi
Hijriyah
Jawa
Sunda
```

### Pilihan Kalender Tujuan

```txt
Masehi
Hijriyah
Jawa
Sunda
```

### Contoh Layout Form

```txt
+--------------------------------------------------+
| Konversi Multi Calendar                          |
|                                                  |
| Kalender Asal                                    |
| [Masehi v]                                       |
|                                                  |
| Tanggal / Bulan / Tahun                          |
| [10] [Juni v] [2026]                             |
|                                                  |
| Kalender Tujuan                                  |
| [Hijriyah v]                                     |
|                                                  |
| [Konversi Tanggal]                               |
+--------------------------------------------------+
```

### Contoh Card Hasil

```txt
+--------------------------------------------------+
| Hasil Konversi                                   |
|                                                  |
| Masehi                                           |
| Rabu, 10 Juni 2026                               |
|                                                  |
| Hijriyah                                         |
| Rabu, 24 Dzulhijjah 1447 H                       |
|                                                  |
| Jawa                                             |
| Rabu Legi, 24 Besar 1959 J                       |
|                                                  |
| Sunda                                            |
| Rabu, 20 Kasa 1962 S                             |
+--------------------------------------------------+
```

### Catatan Penting

Walaupun user hanya memilih satu kalender tujuan, hasil konversi dapat tetap menampilkan ringkasan kalender lain sebagai informasi tambahan.

Rekomendasi:

```txt
Hasil utama:
Kalender tujuan yang dipilih user.

Informasi tambahan:
Kalender lain ditampilkan dalam card kecil.
```

---

## 6. Konversi dari Kalender Lain ke Masehi

### Tujuan

User dapat memasukkan tanggal dari kalender selain Masehi, lalu mengonversinya ke Masehi.

Contoh:

```txt
Hijriyah → Masehi
Jawa → Masehi
Sunda → Masehi
```

### Alur Konversi

```txt
User memilih kalender asal
↓
User mengisi tanggal, bulan, tahun sesuai kalender asal
↓
User memilih kalender tujuan
↓
Sistem mengubah tanggal asal ke Masehi
↓
Sistem mengubah tanggal Masehi ke kalender tujuan
↓
Sistem menampilkan hasil
```

### Konsep Masehi Sebagai Kalender Referensi

Gunakan Masehi sebagai titik tengah konversi.

```txt
Kalender Asal → Masehi → Kalender Tujuan
```

Contoh:

```txt
Jawa → Masehi → Hijriyah
Sunda → Masehi → Jawa
Hijriyah → Masehi → Sunda
```

### Struktur Service yang Disarankan

```txt
CalendarConversionService
├── convertToGregorian()
├── convertFromGregorian()
├── convertBetweenCalendars()
├── getCalendarMonthNames()
├── getCalendarDayNames()
└── formatCalendarDate()
```

Contoh interface service:

```php
interface CalendarConverterInterface
{
    public function toGregorian(array $date): array;

    public function fromGregorian(array $date): array;

    public function getMonthNames(): array;

    public function getDayNames(): array;

    public function format(array $date): string;
}
```

---

## 7. Tampilan Tanggal Multi Calendar pada Kalender

### Tujuan

Grid kalender tidak hanya menampilkan Masehi dan Hijriyah, tetapi dapat menampilkan kalender tambahan seperti Jawa dan Sunda.

### Mode Tampilan

User dapat memilih tampilan utama:

```txt
Masehi
Hijriyah
Jawa
Sunda
```

Kalender utama akan ditampilkan paling dominan, sedangkan kalender lainnya menjadi detail pendamping.

### Contoh Mode Masehi

```txt
+--------------------------+
| 10                       |
| Rabu                     |
| Hijriyah: 24 Dzulhijjah  |
| Jawa: 24 Besar           |
| Sunda: 20 Kasa           |
+--------------------------+
```

### Contoh Mode Hijriyah

```txt
+--------------------------+
| 24                       |
| Dzulhijjah               |
| Masehi: 10 Juni          |
| Jawa: 24 Besar           |
| Sunda: 20 Kasa           |
+--------------------------+
```

### Contoh Mode Jawa

```txt
+--------------------------+
| 24                       |
| Besar                    |
| Rabu Legi                |
| Masehi: 10 Juni          |
| Hijriyah: 24 Dzulhijjah  |
| Sunda: 20 Kasa           |
+--------------------------+
```

### Contoh Mode Sunda

```txt
+--------------------------+
| 20                       |
| Kasa                     |
| Rabu                     |
| Masehi: 10 Juni          |
| Hijriyah: 24 Dzulhijjah  |
| Jawa: 24 Besar           |
+--------------------------+
```

---

## 8. Dropdown Pilihan Tampilan Kalender

### Tujuan

Mengganti tombol toggle sederhana menjadi dropdown pilihan kalender utama.

### Elemen UI

Gunakan komponen `Select` dari shadcn/ui.

Pilihan:

```txt
Masehi sebagai kalender utama
Hijriyah sebagai kalender utama
Jawa sebagai kalender utama
Sunda sebagai kalender utama
```

### State yang Dibutuhkan

```js
const [mainCalendar, setMainCalendar] = useState('gregorian')
```

Nilai state:

```txt
gregorian
hijri
javanese
sundanese
```

### Contoh Layout Kontrol

```txt
Tampilan Kalender Utama
[Masehi v]
```

Atau:

```txt
Lihat berdasarkan: [Kalender Jawa v]
```

---

## 9. Tampilan Nama Hari dan Bulan

### Tujuan

Setiap sistem kalender harus menampilkan nama hari dan nama bulan sesuai konteks kalendernya.

### Informasi yang Ditampilkan per Kalender

#### Masehi

```txt
Rabu, 10 Juni 2026
```

#### Hijriyah

```txt
Rabu, 24 Dzulhijjah 1447 H
```

#### Jawa

```txt
Rabu Legi, 24 Besar 1959 J
```

#### Sunda

```txt
Rabu, 20 Kasa 1962 S
```

### Detail Data yang Dibutuhkan

Setiap item tanggal minimal memiliki struktur berikut:

```js
{
  gregorian: {
    dayName: 'Rabu',
    day: 10,
    monthName: 'Juni',
    year: 2026,
    formatted: 'Rabu, 10 Juni 2026'
  },
  hijri: {
    dayName: 'Rabu',
    day: 24,
    monthName: 'Dzulhijjah',
    year: 1447,
    formatted: 'Rabu, 24 Dzulhijjah 1447 H'
  },
  javanese: {
    dayName: 'Rabu',
    marketDay: 'Legi',
    day: 24,
    monthName: 'Besar',
    year: 1959,
    formatted: 'Rabu Legi, 24 Besar 1959 J'
  },
  sundanese: {
    dayName: 'Rabu',
    day: 20,
    monthName: 'Kasa',
    year: 1962,
    formatted: 'Rabu, 20 Kasa 1962 S'
  }
}
```

---

## 10. Perubahan Hero Section

### Tujuan

Hero section diperbarui agar menjelaskan bahwa aplikasi mendukung multi calendar.

### Heading Baru

```txt
Satu Kalender untuk Masehi, Hijriyah, Jawa, dan Sunda
```

### Subheading

```txt
Konversi dan bandingkan berbagai sistem penanggalan dalam satu tampilan modern yang mudah digunakan.
```

### Typing Animation Baru

Typing animation perlu menampilkan informasi fitur multi-calendar.

Gunakan array teks berikut:

```js
const typingTexts = [
  'Konversi Masehi ke Hijriyah, Jawa, dan Sunda.',
  'Ubah tanggal Hijriyah, Jawa, atau Sunda kembali ke Masehi.',
  'Lihat nama hari dan bulan dari setiap sistem kalender.',
  'Tampilkan kalender utama sesuai sistem tanggal pilihanmu.',
  'Bandingkan beberapa kalender dalam satu tampilan rapi.'
]
```

### CTA Baru

```txt
Mulai Konversi
Lihat Multi Calendar
```

CTA `Mulai Konversi` mengarah ke:

```txt
#konversi
```

CTA `Lihat Multi Calendar` mengarah ke:

```txt
#multi-calendar
```

---

## 11. Tema Warna Soft Sage dan Soft Gold

### Tujuan

Membuat tampilan lebih lembut, elegan, dan tetap memiliki nuansa Islami/tradisional tanpa terlihat terlalu berat.

### Warna Utama — Soft Sage

Soft Sage digunakan sebagai warna utama untuk:

* CTA utama.
* Badge.
* Border aktif.
* Highlight section.
* Icon utama.
* Background dekoratif lembut.

Rekomendasi warna:

```css
--sage-50: #F6F8F3;
--sage-100: #E8EDE0;
--sage-200: #D5DDC8;
--sage-300: #B9C7A8;
--sage-400: #9CAF88;
--sage-500: #7F946D;
--sage-600: #647754;
--sage-700: #4F5F43;
--sage-800: #3F4C37;
--sage-900: #34402F;
```

### Warna Ornamen — Soft Gold

Soft Gold digunakan sebagai aksen dekoratif untuk:

* Ornamen kecil.
* Icon fitur.
* Border card tertentu.
* Highlight tanggal hari ini.
* Decorative glow.
* Underline heading.
* Detail kalender pendamping.

Rekomendasi warna:

```css
--gold-50: #FFF9EA;
--gold-100: #FCEFC7;
--gold-200: #F7DD93;
--gold-300: #EFC765;
--gold-400: #E3AD3F;
--gold-500: #C9902E;
--gold-600: #A96F24;
--gold-700: #87531F;
--gold-800: #70441F;
--gold-900: #603A1E;
```

### Neutral Background

Gunakan background yang lembut:

```css
--soft-background: #FBFBF7;
--soft-card: #FFFFFF;
--soft-muted: #F3F1E8;
```

### Contoh Penggunaan Tailwind Arbitrary Color

```txt
bg-[#F6F8F3]
text-[#34402F]
border-[#D5DDC8]
bg-[#7F946D]
text-[#FFF9EA]
ring-[#EFC765]
```

### Contoh Mapping Visual

```txt
Primary Button:
Background: Sage 500
Text: Gold 50
Hover: Sage 600

Secondary Button:
Background: Transparent
Border: Sage 200
Text: Sage 800

Ornament:
Gold 300 / Gold 400

Card:
Background: White
Border: Sage 100

Hero Background:
Base: Soft Background
Pattern: Sage 200 with low opacity
Glow: Gold 200 with low opacity
```

---

## 12. Update Desain Komponen

### Floating Navbar

Gunakan style:

```txt
bg-white/75
border-[#D5DDC8]
backdrop-blur-xl
shadow-sm
```

CTA navbar:

```txt
bg-[#7F946D]
text-[#FFF9EA]
hover:bg-[#647754]
```

Ornamen kecil pada logo:

```txt
bg-[#EFC765]
```

---

### Hero Section

Background:

```txt
bg-[#FBFBF7]
```

Square pattern:

```css
background-image:
  linear-gradient(to right, rgba(127, 148, 109, 0.13) 1px, transparent 1px),
  linear-gradient(to bottom, rgba(127, 148, 109, 0.13) 1px, transparent 1px);
background-size: 48px 48px;
```

Gold glow layer:

```txt
absolute -top-24 left-1/2 h-72 w-72 rounded-full bg-[#F7DD93]/30 blur-3xl
```

Sage glow layer:

```txt
absolute bottom-10 right-10 h-80 w-80 rounded-full bg-[#D5DDC8]/40 blur-3xl
```

---

### Conversion Card

Gunakan tampilan lembut:

```txt
rounded-3xl
border border-[#D5DDC8]
bg-white/85
shadow-sm
backdrop-blur
```

Tombol utama:

```txt
bg-[#7F946D] text-[#FFF9EA] hover:bg-[#647754]
```

Card hasil utama:

```txt
border-[#EFC765]/60
bg-[#FFF9EA]/70
```

---

### Calendar Grid

Tanggal utama:

```txt
text-[#34402F]
```

Tanggal pendamping:

```txt
text-[#647754]
```

Ornamen kalender:

```txt
border-[#F7DD93]/70
```

Hari aktif / tanggal hari ini:

```txt
bg-[#FFF9EA]
ring-1 ring-[#EFC765]
```

---

## 13. Animasi Square Parallax Pattern

### Tujuan

Membuat background square pattern pada hero terasa lebih dinamis tanpa mengganggu keterbacaan teks.

### Konsep Animasi

Background square pattern dibuat dalam beberapa layer:

1. Layer grid utama.
2. Layer grid kedua dengan opacity lebih rendah.
3. Layer glow Soft Gold.
4. Layer glow Soft Sage.
5. Layer teks hero di atas semua layer.

### Struktur Layer

```txt
Hero Section
├── Base background Soft Background
├── Animated square grid layer
├── Secondary slower grid layer
├── Soft Gold glow
├── Soft Sage glow
└── Hero content
```

### CSS Keyframes

Tambahkan CSS berikut:

```css
@keyframes sage-grid-drift {
  0% {
    transform: translate3d(0, 0, 0);
  }

  50% {
    transform: translate3d(-24px, 18px, 0);
  }

  100% {
    transform: translate3d(0, 0, 0);
  }
}

@keyframes sage-grid-drift-slow {
  0% {
    transform: translate3d(0, 0, 0) scale(1);
  }

  50% {
    transform: translate3d(32px, -20px, 0) scale(1.03);
  }

  100% {
    transform: translate3d(0, 0, 0) scale(1);
  }
}

@keyframes soft-glow-float {
  0% {
    transform: translate3d(0, 0, 0);
    opacity: 0.45;
  }

  50% {
    transform: translate3d(18px, -24px, 0);
    opacity: 0.65;
  }

  100% {
    transform: translate3d(0, 0, 0);
    opacity: 0.45;
  }
}
```

### Class CSS untuk Pattern

```css
.sage-square-pattern {
  background-image:
    linear-gradient(to right, rgba(127, 148, 109, 0.13) 1px, transparent 1px),
    linear-gradient(to bottom, rgba(127, 148, 109, 0.13) 1px, transparent 1px);
  background-size: 48px 48px;
  animation: sage-grid-drift 16s ease-in-out infinite;
  will-change: transform;
}

.sage-square-pattern-slow {
  background-image:
    linear-gradient(to right, rgba(239, 199, 101, 0.12) 1px, transparent 1px),
    linear-gradient(to bottom, rgba(239, 199, 101, 0.12) 1px, transparent 1px);
  background-size: 96px 96px;
  animation: sage-grid-drift-slow 24s ease-in-out infinite;
  will-change: transform;
}

.soft-glow-float {
  animation: soft-glow-float 12s ease-in-out infinite;
  will-change: transform, opacity;
}
```

### Contoh Struktur HTML/JSX Hero

```jsx
<section className="relative min-h-screen overflow-hidden bg-[#FBFBF7]">
  <div className="sage-square-pattern absolute inset-0 opacity-80" />
  <div className="sage-square-pattern-slow absolute inset-0 opacity-70" />

  <div className="soft-glow-float absolute -top-24 left-1/2 h-72 w-72 rounded-full bg-[#F7DD93]/30 blur-3xl" />
  <div className="soft-glow-float absolute bottom-10 right-10 h-80 w-80 rounded-full bg-[#D5DDC8]/40 blur-3xl" />

  <div className="absolute inset-0 bg-gradient-to-b from-[#FBFBF7]/40 via-[#FBFBF7]/70 to-[#FBFBF7]" />

  <div className="relative z-10">
    {/* Hero content */}
  </div>
</section>
```

### Catatan Performa

Tambahkan fallback untuk user yang mengaktifkan reduced motion.

```css
@media (prefers-reduced-motion: reduce) {
  .sage-square-pattern,
  .sage-square-pattern-slow,
  .soft-glow-float {
    animation: none;
  }
}
```

---

## 14. Scroll-Based Parallax Optional

Selain animasi CSS otomatis, parallax juga dapat dibuat merespons scroll.

### State Scroll

```js
const [scrollY, setScrollY] = useState(0)

useEffect(() => {
  const handleScroll = () => setScrollY(window.scrollY)

  window.addEventListener('scroll', handleScroll, { passive: true })

  return () => window.removeEventListener('scroll', handleScroll)
}, [])
```

### Contoh Penggunaan Transform

```jsx
<div
  className="sage-square-pattern absolute inset-0 opacity-80"
  style={{
    transform: `translateY(${scrollY * 0.08}px)`
  }}
/>

<div
  className="sage-square-pattern-slow absolute inset-0 opacity-70"
  style={{
    transform: `translateY(${scrollY * -0.05}px)`
  }}
/>
```

### Catatan

Jika menggunakan scroll-based parallax, pastikan tidak terlalu berat di perangkat mobile.

Rekomendasi:

* Gunakan CSS animation terlebih dahulu.
* Tambahkan scroll parallax hanya jika performa tetap stabil.
* Gunakan `requestAnimationFrame` jika scroll event terasa berat.

---

## 15. Update Section Konversi

### Judul Baru

```txt
Konversi Tanggal Antar Kalender
```

### Deskripsi Baru

```txt
Pilih sistem kalender asal dan tujuan untuk mengonversi tanggal Masehi, Hijriyah, Jawa, atau Sunda dalam satu tempat.
```

### Layout Baru

```txt
+-------------------------------------------------------------+
| Konversi Tanggal Antar Kalender                             |
|                                                             |
| [Kalender Asal] [Tanggal] [Bulan] [Tahun] [Kalender Tujuan] |
|                                                             |
| [Konversi Sekarang]                                         |
+-------------------------------------------------------------+

+-------------------------------------------------------------+
| Hasil Konversi Utama                                        |
| Hijriyah                                                    |
| Rabu, 24 Dzulhijjah 1447 H                                  |
+-------------------------------------------------------------+

+----------------+ +----------------+ +----------------+
| Masehi         | | Jawa           | | Sunda          |
| Rabu, 10 Juni  | | Rabu Legi,     | | Rabu, 20 Kasa |
| 2026           | | 24 Besar       | | 1962 S         |
+----------------+ +----------------+ +----------------+
```

### Komponen shadcn/ui

Gunakan:

```txt
Card
Button
Input
Label
Select
Badge
Separator
Tabs
```

---

## 16. Update Section Kalender

### ID Section

```html
<section id="multi-calendar">
```

### Judul Baru

```txt
Tampilan Multi Calendar
```

### Deskripsi Baru

```txt
Bandingkan tanggal Masehi, Hijriyah, Jawa, dan Sunda dalam satu grid kalender yang rapi.
```

### Kontrol Tampilan

Tambahkan:

```txt
Kalender Utama: [Masehi v]
Tampilkan Detail: [Hijriyah] [Jawa] [Sunda]
```

### Opsi Detail Kalender

User dapat memilih kalender pendamping mana saja yang tampil.

Default:

```txt
Hijriyah: aktif
Jawa: aktif
Sunda: aktif
```

State yang dibutuhkan:

```js
const [mainCalendar, setMainCalendar] = useState('gregorian')

const [visibleCalendars, setVisibleCalendars] = useState({
  hijri: true,
  javanese: true,
  sundanese: true
})
```

Jika `mainCalendar` adalah `hijri`, maka detail pendamping default dapat menjadi:

```js
{
  gregorian: true,
  javanese: true,
  sundanese: true
}
```

---

## 17. Placeholder Data Multi Calendar

Gunakan data dummy untuk layout awal.

```js
const multiCalendarDays = Array.from({ length: 35 }).map((_, index) => ({
  id: index + 1,
  gregorian: {
    dayName: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'][index % 7],
    day: index + 1 <= 30 ? index + 1 : '',
    monthName: 'Juni',
    year: 2026,
    formatted: `${index + 1 <= 30 ? index + 1 : ''} Juni 2026`
  },
  hijri: {
    dayName: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'][index % 7],
    day: index + 15 <= 30 ? index + 15 : index - 14,
    monthName: index + 15 <= 30 ? 'Dzulhijjah' : 'Muharram',
    year: index + 15 <= 30 ? 1447 : 1448,
    formatted: `${index + 15 <= 30 ? index + 15 : index - 14} ${index + 15 <= 30 ? 'Dzulhijjah' : 'Muharram'}`
  },
  javanese: {
    dayName: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'][index % 7],
    marketDay: ['Legi', 'Pahing', 'Pon', 'Wage', 'Kliwon'][index % 5],
    day: index + 12 <= 30 ? index + 12 : index - 17,
    monthName: index + 12 <= 30 ? 'Besar' : 'Sura',
    year: index + 12 <= 30 ? 1959 : 1960,
    formatted: `${['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'][index % 7]} ${['Legi', 'Pahing', 'Pon', 'Wage', 'Kliwon'][index % 5]}, ${index + 12 <= 30 ? index + 12 : index - 17} ${index + 12 <= 30 ? 'Besar' : 'Sura'}`
  },
  sundanese: {
    dayName: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'][index % 7],
    day: index + 8 <= 30 ? index + 8 : index - 21,
    monthName: index + 8 <= 30 ? 'Kasa' : 'Karo',
    year: index + 8 <= 30 ? 1962 : 1963,
    formatted: `${index + 8 <= 30 ? index + 8 : index - 21} ${index + 8 <= 30 ? 'Kasa' : 'Karo'}`
  }
}))
```

Catatan:

```txt
Data di atas hanya dummy untuk kebutuhan layout.
Tanggal, bulan, tahun, pasaran, dan hasil konversi belum mewakili data kalender sebenarnya.
```

---

## 18. Format Display Berdasarkan Kalender Utama

Buat helper function untuk menentukan tampilan utama.

```js
function getMainCalendarDisplay(day, mainCalendar) {
  const selected = day[mainCalendar]

  if (!selected) {
    return null
  }

  return {
    day: selected.day,
    dayName: selected.dayName,
    monthName: selected.monthName,
    year: selected.year,
    formatted: selected.formatted,
    marketDay: selected.marketDay || null
  }
}
```

Buat helper untuk menampilkan label kalender:

```js
const calendarLabels = {
  gregorian: 'Masehi',
  hijri: 'Hijriyah',
  javanese: 'Jawa',
  sundanese: 'Sunda'
}
```

Buat helper untuk suffix:

```js
const calendarSuffixes = {
  gregorian: 'M',
  hijri: 'H',
  javanese: 'J',
  sundanese: 'S'
}
```

---

## 19. Rekomendasi Struktur Komponen

Jika menggunakan React/Inertia, struktur komponen dapat dibuat seperti berikut:

```txt
resources/js/Components/Landing/FloatingNavbar.jsx
resources/js/Components/Landing/HeroSection.jsx
resources/js/Components/Landing/TypingText.jsx
resources/js/Components/Landing/AnimatedParallaxPattern.jsx
resources/js/Components/Landing/MultiCalendarConversion.jsx
resources/js/Components/Landing/MultiCalendarPreview.jsx
resources/js/Components/Landing/CalendarModeSelector.jsx
resources/js/Components/Landing/CalendarDayCell.jsx
resources/js/Components/Landing/FeatureSection.jsx
resources/js/Components/Landing/Footer.jsx
```

Jika menggunakan Blade, struktur partial dapat dibuat seperti berikut:

```txt
resources/views/components/landing/floating-navbar.blade.php
resources/views/components/landing/hero-section.blade.php
resources/views/components/landing/animated-parallax-pattern.blade.php
resources/views/components/landing/multi-calendar-conversion.blade.php
resources/views/components/landing/multi-calendar-preview.blade.php
resources/views/components/landing/feature-section.blade.php
resources/views/components/landing/footer.blade.php
```

---

## 20. Rekomendasi Struktur Service Backend untuk Tahap Lanjutan

Walaupun tahap ini masih layout, struktur backend dapat direncanakan dari awal.

```txt
app/Services/Calendar/
├── CalendarConversionService.php
├── Contracts/
│   └── CalendarConverterInterface.php
├── Converters/
│   ├── GregorianCalendarConverter.php
│   ├── HijriCalendarConverter.php
│   ├── JavaneseCalendarConverter.php
│   └── SundaneseCalendarConverter.php
└── DTO/
    ├── CalendarDateData.php
    └── CalendarConversionResult.php
```

### CalendarConversionService

Bertugas mengatur konversi antar kalender.

```php
class CalendarConversionService
{
    public function convert(array $payload): array
    {
        // 1. Ambil kalender asal
        // 2. Ubah ke Masehi
        // 3. Ubah dari Masehi ke kalender tujuan
        // 4. Format hasil
        // 5. Return hasil utama dan hasil kalender lain
    }
}
```

---

## 21. Rekomendasi Endpoint Tahap Lanjutan

Endpoint untuk konversi:

```txt
POST /api/calendar/convert
```

Payload:

```json
{
  "source_calendar": "javanese",
  "target_calendar": "hijri",
  "day": 24,
  "month": "Besar",
  "year": 1959,
  "market_day": "Legi"
}
```

Response:

```json
{
  "source": {
    "calendar": "javanese",
    "formatted": "Rabu Legi, 24 Besar 1959 J"
  },
  "target": {
    "calendar": "hijri",
    "formatted": "Rabu, 24 Dzulhijjah 1447 H"
  },
  "all_calendars": {
    "gregorian": {
      "formatted": "Rabu, 10 Juni 2026"
    },
    "hijri": {
      "formatted": "Rabu, 24 Dzulhijjah 1447 H"
    },
    "javanese": {
      "formatted": "Rabu Legi, 24 Besar 1959 J"
    },
    "sundanese": {
      "formatted": "Rabu, 20 Kasa 1962 S"
    }
  }
}
```

Endpoint untuk kalender bulanan:

```txt
GET /api/calendar/month
```

Query:

```txt
?calendar=gregorian&month=6&year=2026&include=hijri,javanese,sundanese
```

---

## 22. Update Section Fitur

Tambahkan fitur baru berikut.

### Multi Calendar

```txt
Bandingkan tanggal Masehi, Hijriyah, Jawa, dan Sunda dalam satu tampilan.
```

### Konversi Dua Arah

```txt
Konversi tanggal dari Masehi ke kalender lain, atau dari kalender lain kembali ke Masehi.
```

### Nama Hari dan Bulan Lengkap

```txt
Setiap tanggal dilengkapi nama hari dan nama bulan sesuai sistem kalender yang digunakan.
```

### Kalender Jawa dan Sunda

```txt
Dukung penanggalan tradisional Nusantara seperti Kalender Jawa dan Kalender Sunda.
```

### Tampilan Kalender Fleksibel

```txt
Pilih kalender utama sesuai kebutuhan, lalu tampilkan kalender lainnya sebagai pendamping.
```

### Tema Soft Sage

```txt
Tampilan lembut dengan palet Soft Sage dan ornamen Soft Gold yang elegan.
```

---

## 23. Update Footer

Footer dapat diperbarui menjadi:

```txt
Multi Calendar Nusantara
Kalender digital untuk melihat dan mengonversi tanggal Masehi, Hijriyah, Jawa, dan Sunda.

Beranda | Konversi | Multi Calendar | Fitur

© 2026 Multi Calendar Nusantara. All rights reserved.
```

---

## 24. Acceptance Criteria

Pengembangan tahap ini dianggap selesai jika memenuhi kriteria berikut:

* Form konversi memiliki pilihan kalender asal.
* Form konversi memiliki pilihan kalender tujuan.
* Kalender yang tersedia meliputi Masehi, Hijriyah, Jawa, dan Sunda.
* User dapat memilih konversi dari kalender selain Masehi ke Masehi.
* User dapat memilih konversi dari Masehi ke kalender lain.
* Hasil konversi menampilkan nama hari.
* Hasil konversi menampilkan nama bulan.
* Hasil konversi dapat menampilkan ringkasan semua sistem kalender.
* Grid kalender memiliki pilihan kalender utama.
* Kalender utama dapat dipilih dari Masehi, Hijriyah, Jawa, dan Sunda.
* Tanggal pendamping dapat menampilkan kalender lainnya.
* Kalender Jawa menampilkan informasi pasaran.
* Tema warna menggunakan Soft Sage sebagai warna utama.
* Ornamen menggunakan Soft Gold.
* Hero section memiliki animated square parallax pattern.
* Animasi memiliki fallback untuk reduced motion.
* Layout tetap responsif di desktop dan mobile.
* Data boleh masih dummy.
* Algoritma konversi asli belum wajib selesai.

---

## 25. Scope yang Tidak Wajib pada Tahap Ini

Hal berikut tidak wajib dikerjakan pada tahap layout:

* Akurasi final konversi Kalender Jawa.
* Akurasi final konversi Kalender Sunda.
* Koreksi kalender berdasarkan lokasi.
* Koreksi kalender berdasarkan rukyat.
* Perhitungan weton lengkap.
* Kalender event adat.
* Kalender hari besar Islam.
* Database historis kalender.
* Penyimpanan preferensi pengguna.
* Fitur export atau print kalender.
* Mode offline.
* PWA.

---

## 26. Tahapan Implementasi

### Tahap 1 — Update Copywriting dan Navigasi

* Ubah branding dari Kalender Hijriyah menjadi Multi Calendar Nusantara.
* Update hero heading.
* Update typing animation.
* Update CTA hero.
* Update link navbar ke section baru.

### Tahap 2 — Update Theme Color

* Terapkan warna Soft Sage sebagai warna utama.
* Terapkan Soft Gold sebagai aksen.
* Update button, badge, card, border, dan calendar highlight.
* Pastikan kontras teks tetap terbaca.

### Tahap 3 — Animated Parallax Pattern

* Pisahkan background pattern menjadi komponen sendiri.
* Tambahkan layer grid Sage.
* Tambahkan layer grid Gold.
* Tambahkan glow Sage.
* Tambahkan glow Gold.
* Tambahkan CSS keyframes.
* Tambahkan fallback `prefers-reduced-motion`.

### Tahap 4 — Update Conversion Section

* Tambahkan dropdown kalender asal.
* Tambahkan dropdown kalender tujuan.
* Tambahkan field tanggal, bulan, dan tahun dinamis.
* Tambahkan placeholder hasil utama.
* Tambahkan card ringkasan semua kalender.
* Pastikan layout responsive.

### Tahap 5 — Update Calendar Preview

* Ubah toggle menjadi select kalender utama.
* Tambahkan opsi Masehi, Hijriyah, Jawa, dan Sunda.
* Tambahkan detail kalender pendamping.
* Tambahkan checkbox atau switch untuk menampilkan atau menyembunyikan kalender pendamping.
* Buat cell kalender yang dapat berubah berdasarkan mode utama.

### Tahap 6 — Update Feature Section

* Tambahkan fitur Multi Calendar.
* Tambahkan fitur Kalender Jawa.
* Tambahkan fitur Kalender Sunda.
* Tambahkan fitur Konversi Dua Arah.
* Tambahkan fitur Nama Hari dan Bulan.
* Tambahkan fitur Tema Soft Sage.

### Tahap 7 — Review Layout

* Cek hero di desktop dan mobile.
* Cek readability typing animation.
* Cek form konversi.
* Cek grid kalender.
* Cek warna Soft Sage dan Soft Gold.
* Cek performa animasi parallax.
* Cek fallback reduced motion.
* Cek responsivitas semua section.

---

## 27. Catatan Pengembangan Lanjutan

Setelah layout multi calendar selesai, tahap berikutnya dapat dilanjutkan ke:

* Riset library atau algoritma konversi kalender.
* Implementasi konversi Masehi ↔ Hijriyah.
* Implementasi konversi Masehi ↔ Jawa.
* Implementasi konversi Masehi ↔ Sunda.
* Validasi nama bulan dan hari.
* Validasi pasaran Jawa.
* Pembuatan API Laravel.
* Pembuatan unit test untuk konversi tanggal.
* Penambahan hari besar Islam.
* Penambahan hari penting adat Jawa dan Sunda.
* Penambahan pengaturan preferensi user.

```
```
