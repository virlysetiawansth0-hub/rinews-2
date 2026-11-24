======================================================================
FOLDER PENGATURAN UKURAN GAMBAR PER WIDGET — RINEWS.ID (v2.3.3)
Lokasi di Tema WordPress: /rinews/ukuran-gambar-widget/
======================================================================

Folder ini berisi file konfigurasi ukuran gambar untuk masing-masing
widget dan slot foto di tema WordPress RINEWS.ID.

DAFTAR FILE WIDGET DI FOLDER INI:
----------------------------------------------------------------------
01-header-logo-tengah.json       -> Logo Header Tengah (380 x 86 px, contain)
02-iklan-sidebar-indeks.json     -> 3 Space Iklan Sidebar & 3 Iklan Bawah (526 x 393 px)
03-berita-viral.json             -> Kolom Berita Viral (526 x 393 px)
04-slide-berita-utama.json       -> Slide Berita Baru Utama (1052 x 786 px)
05-slide-thumbnail-strip.json    -> Thumbnail Mini di Bawah Slide (263 x 197 px)
06-top3-kategori-teratas.json    -> 3 Kolom Teratas Bawah Slide (526 x 393 px)
07-iklan-leaderboard.json        -> Iklan Panjang Leaderboard (1320 x 160 px)
08-pos-baru-kiri-kanan.json      -> Pos Baru 1 Grup 2 Kolom (20 Berita/Hal, 526 x 393 px)
09-kategori-horizontal.json      -> Blok Kategori Horizontal 4 Kolom (526 x 393 px)
10-kategori-vertikal.json        -> Blok 3 Kolom Vertikal (526 x 393 px)
11-tiktok-video.json             -> Poster Kartu TikTok Video (540 x 840 px, 9:14)
12-youtube-video.json            -> Thumbnail Video Berita YouTube (640 x 360 px, 16:9)
13-live-streaming-portrait.json  -> Frame LIVE Streaming Portrait (540 x 960 px, 9:16)
14-artikel-tunggal.json          -> Foto Utama Halaman Baca Artikel (1052 x 786 px)
15-header-gambar-bawah.json      -> Widget Gambar Bawah Tagline Header Tengah (728 x 120 px)

CARA MENGATUR / MENGUBAH UKURAN GAMBAR MASING-MASING WIDGET:
----------------------------------------------------------------------
Cara 1 (Langsung lewat Workspace Edit File & ZIP sebelum unduh ZIP):
- Buka folder "ukuran-gambar-widget/" di tab "Edit File & ZIP".
- Pilih file widget yang ingin diubah (misalnya 08-pos-baru-kiri-kanan.json),
  lalu ubah "width", "height", "crop" (true/false), dan "object_fit" ("cover"/"contain").
- Klik Simpan Perubahan, lalu klik "Unduh ZIP WP 7.1".

Cara 2 (Langsung di Dashboard WordPress):
- Setelah tema diaktifkan di WordPress, buka menu:
  WP Admin -> Tampilan (Appearance) -> Ukuran Gambar Widget.
- Atur lebar (px), tinggi (px), crop, dan mode penyesuaian per widget,
  lalu klik "Simpan Pengaturan Ukuran Gambar".
- Jika mengubah ukuran gambar untuk foto yang sudah pernah diunggah sebelumnya,
  jalankan plugin "Regenerate Thumbnails" sekali saja di WP Admin.
======================================================================
