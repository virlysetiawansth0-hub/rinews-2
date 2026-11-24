<?php
/**
 * RINEWS.ID — Pengelola Ukuran Gambar Per Widget (Folder /ukuran-gambar-widget/)
 * Membaca file konfigurasi JSON di folder /ukuran-gambar-widget/ untuk masing-masing widget,
 * mendaftarkan add_image_size() ke WordPress, mencetak CSS Custom Properties otomatis,
 * serta menyediakan menu Admin WP (Tampilan -> Ukuran Gambar Widget).
 *
 * @package RINEWS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fallback bawaan apabila file JSON di folder /ukuran-gambar-widget/ tidak dapat dibaca.
 *
 * @return array<string, array<string, mixed>>
 */
function rinews_get_fallback_widget_image_sizes() {
	return array(
		'header_logo'         => array(
			'key'           => 'header_logo',
			'label'         => '01. Header — Logo Tengah Portal',
			'widget'        => 'RINEWS_Widget_Header_Logo',
			'wp_size'       => 'rinews-header-logo',
			'width'         => 380,
			'height'        => 86,
			'crop'          => false,
			'object_fit'    => 'contain',
			'css_ratio_var' => '--rn-ratio-header-logo',
			'css_maxh_var'  => '--rn-logo-max-h',
			'notes'         => 'Tinggi maksimal logo header tengah dalam pixel (default 86px, lebar maks 380px).',
		),
		'iklan_sidebar'       => array(
			'key'           => 'iklan_sidebar',
			'label'         => '02. Ruang Gambar Iklan (3 Indeks Kiri & 3 Bawah)',
			'widget'        => 'RINEWS_Widget_Iklan_Gambar (sidebar)',
			'wp_size'       => 'rinews-iklan-sidebar',
			'width'         => 526,
			'height'        => 393,
			'crop'          => true,
			'object_fit'    => 'cover',
			'css_ratio_var' => '--rn-ratio-iklan-sidebar',
			'notes'         => 'Ukuran kartu iklan kolom Indeks Pencarian dan 3 Iklan Bawah sebelum footer.',
		),
		'berita_viral'        => array(
			'key'           => 'berita_viral',
			'label'         => '03. Kolom Berita Viral (Pembaca Terbanyak)',
			'widget'        => 'RINEWS_Widget_Berita_Viral',
			'wp_size'       => 'rinews-berita-viral',
			'width'         => 526,
			'height'        => 393,
			'crop'          => true,
			'object_fit'    => 'cover',
			'css_ratio_var' => '--rn-ratio-viral',
			'notes'         => 'Ukuran foto berita pada kolom Berita Viral di hero tengah.',
		),
		'slide_utama'         => array(
			'key'           => 'slide_utama',
			'label'         => '04. Slide Berita Baru Utama (6 Pos Teratas)',
			'widget'        => 'RINEWS_Widget_Slide_Berita_Baru',
			'wp_size'       => 'rinews-slide',
			'width'         => 1052,
			'height'        => 786,
			'crop'          => true,
			'object_fit'    => 'cover',
			'css_ratio_var' => '--rn-ratio-slide',
			'notes'         => 'Resolusi 2x dari rasio 526:393 untuk layar slide utama agar tetap tajam.',
		),
		'slide_thumb'         => array(
			'key'           => 'slide_thumb',
			'label'         => '05. Thumbnail Strip di Bawah Slide Utama',
			'widget'        => 'RINEWS_Widget_Slide_Berita_Baru (thumbs)',
			'wp_size'       => 'rinews-thumb',
			'width'         => 263,
			'height'        => 197,
			'crop'          => true,
			'object_fit'    => 'cover',
			'css_ratio_var' => '--rn-ratio-slide-thumb',
			'notes'         => 'Thumbnail navigasi 6 slide di bawah stage slide utama.',
		),
		'top3_teratas'        => array(
			'key'           => 'top3_teratas',
			'label'         => '06. 3 Kolom Teratas Bawah Slide (Internasional, Nasional, Regional)',
			'widget'        => 'RINEWS_Widget_Slide_Berita_Baru (top3)',
			'wp_size'       => 'rinews-top3',
			'width'         => 526,
			'height'        => 393,
			'crop'          => true,
			'object_fit'    => 'cover',
			'css_ratio_var' => '--rn-ratio-top3',
			'notes'         => 'Tiga kotak berita teratas tepat di bawah slider hero.',
		),
		'iklan_leaderboard'   => array(
			'key'           => 'iklan_leaderboard',
			'label'         => '07. Iklan Panjang Horizontal (Leaderboard 1, 2 & Bawah)',
			'widget'        => 'RINEWS_Widget_Iklan_Gambar (leaderboard)',
			'wp_size'       => 'rinews-iklan-leaderboard',
			'width'         => 1320,
			'height'        => 160,
			'crop'          => true,
			'object_fit'    => 'cover',
			'css_ratio_var' => '--rn-ratio-leaderboard',
			'css_minh_var'  => '--rn-leaderboard-minh',
			'notes'         => 'Banner iklan panjang horizontal lebar penuh kontainer.',
		),
		'pos_baru'            => array(
			'key'           => 'pos_baru',
			'label'         => '08. Pos Baru — 1 Grup Kiri & Kanan (20 Berita/Hal)',
			'widget'        => 'RINEWS_Widget_Kategori_Berita (grid_pos_baru)',
			'wp_size'       => 'rinews-posbaru',
			'width'         => 526,
			'height'        => 393,
			'crop'          => true,
			'object_fit'    => 'cover',
			'css_ratio_var' => '--rn-ratio-posbaru',
			'css_maxw_var'  => '--rn-posbaru-maxw',
			'notes'         => 'Foto berita Pos Baru 1 grup 2 kolom (20 berita per halaman, Terbaru hingga Terlama) posisi tengah atas, judul di bawah & 3 kalimat artikel + Selengkapnya....',
		),
		'kategori_horizontal' => array(
			'key'           => 'kategori_horizontal',
			'label'         => '09. Blok Kategori Horizontal (4 Kolom: Internasional, Nasional, dll)',
			'widget'        => 'RINEWS_Widget_Kategori_Berita (horizontal)',
			'wp_size'       => 'rinews-kategori-horizontal',
			'width'         => 526,
			'height'        => 393,
			'crop'          => true,
			'object_fit'    => 'cover',
			'css_ratio_var' => '--rn-ratio-kat-horiz',
			'notes'         => 'Kartu foto 4 kolom untuk kategori Internasional, Nasional, Regional, Buser Berita, Olah Raga, Bisnis.',
		),
		'kategori_vertikal'   => array(
			'key'           => 'kategori_vertikal',
			'label'         => '10. Blok 3 Kolom Vertikal (Ilmu Pengetahuan, Hiburan, Budaya)',
			'widget'        => 'RINEWS_Widget_Kategori_Berita (vertical)',
			'wp_size'       => 'rinews-kategori-vertikal',
			'width'         => 526,
			'height'        => 393,
			'crop'          => true,
			'object_fit'    => 'cover',
			'css_ratio_var' => '--rn-ratio-kat-vert',
			'notes'         => 'Kartu foto berita vertikal pada kolom Ilmu Pengetahuan, Hiburan, dan Budaya.',
		),
		'tiktok_video'        => array(
			'key'           => 'tiktok_video',
			'label'         => '11. Integrasi Video TikTok (Poster 4 Tayangan Teratas)',
			'widget'        => 'RINEWS_Widget_TikTok_Feed',
			'wp_size'       => 'rinews-tiktok-poster',
			'width'         => 540,
			'height'        => 840,
			'crop'          => true,
			'object_fit'    => 'cover',
			'css_ratio_var' => '--rn-ratio-tiktok',
			'notes'         => 'Poster vertikal kartu Video TikTok (rasio default 9:14 / 540 x 840 px).',
		),
		'youtube_video'       => array(
			'key'           => 'youtube_video',
			'label'         => '12. Integrasi Video Berita YouTube (Thumbnail 16:9)',
			'widget'        => 'RINEWS_Widget_YouTube_Feed',
			'wp_size'       => 'rinews-youtube-thumb',
			'width'         => 640,
			'height'        => 360,
			'crop'          => true,
			'object_fit'    => 'cover',
			'css_ratio_var' => '--rn-ratio-youtube',
			'notes'         => 'Thumbnail video YouTube layar lebar 16:9 (default 640 x 360 px).',
		),
		'live_streaming'      => array(
			'key'           => 'live_streaming',
			'label'         => '13. LIVE Streaming Portrait & TikTok Live (Rasio 9:16)',
			'widget'        => 'RINEWS_Widget_Live_Streaming',
			'wp_size'       => 'rinews-live-portrait',
			'width'         => 540,
			'height'        => 960,
			'crop'          => true,
			'object_fit'    => 'cover',
			'css_ratio_var' => '--rn-ratio-live',
			'notes'         => 'Rasio frame siaran langsung Portrait TikTok Live di footer tengah atas copyright (default 9:16).',
		),
		'artikel_single'      => array(
			'key'           => 'artikel_single',
			'label'         => '14. Halaman Baca Artikel Tunggal (Featured Image)',
			'widget'        => 'Single Post (single.php)',
			'wp_size'       => 'rinews-single',
			'width'         => 1052,
			'height'        => 786,
			'crop'          => true,
			'object_fit'    => 'cover',
			'css_ratio_var' => '--rn-ratio-single',
			'css_maxw_var'  => '--rn-single-maxw',
			'notes'         => 'Foto utama artikel pada halaman baca berita tunggal (single.php).',
		),
		'header_bottom_image' => array(
			'key'           => 'header_bottom_image',
			'label'         => '15. Header — Widget Gambar Di Bawah Tagline (Tengah)',
			'widget'        => 'RINEWS_Widget_Header_Image',
			'wp_size'       => 'rinews-header-banner',
			'width'         => 728,
			'height'        => 120,
			'crop'          => true,
			'object_fit'    => 'cover',
			'css_ratio_var' => '--rn-ratio-header-banner',
			'css_maxw_var'  => '--rn-header-banner-maxw',
			'notes'         => 'Widget gambar posisi tengah tepat di bawah tulisan REPUBLIC OF INDONESIA NEWS MEDIA (default 728 x 120 px).',
		),
	);
}

/**
 * Baca konfigurasi dasar dari folder /rinews/ukuran-gambar-widget/*.json
 *
 * @return array<string, array<string, mixed>>
 */
function rinews_read_widget_image_sizes_folder() {
	$defaults = rinews_get_fallback_widget_image_sizes();
	$dir      = RINEWS_DIR . '/ukuran-gambar-widget';

	if ( ! is_dir( $dir ) ) {
		return $defaults;
	}

	$files = glob( $dir . '/*.json' );
	if ( ! is_array( $files ) || empty( $files ) ) {
		return $defaults;
	}
	sort( $files, SORT_NATURAL );

	$loaded = array();
	foreach ( $files as $file ) {
		if ( ! is_readable( $file ) ) {
			continue;
		}
		$raw = file_get_contents( $file );
		if ( false === $raw ) {
			continue;
		}
		$decoded = json_decode( $raw, true );
		if ( ! is_array( $decoded ) || empty( $decoded['key'] ) ) {
			continue;
		}
		$key = sanitize_key( (string) $decoded['key'] );
		if ( '' === $key ) {
			continue;
		}
		$base          = isset( $defaults[ $key ] ) ? $defaults[ $key ] : array();
		$merged        = array_merge( $base, $decoded );
		$merged['key'] = $key;
		$merged['file'] = basename( $file );
		$loaded[ $key ] = $merged;
	}

	if ( empty( $loaded ) ) {
		return $defaults;
	}

	foreach ( $defaults as $k => $v ) {
		if ( ! isset( $loaded[ $k ] ) ) {
			$loaded[ $k ] = $v;
		}
	}

	return $loaded;
}

/**
 * Ambil konfigurasi aktif ukuran gambar per widget (folder JSON + opsi WP Admin).
 *
 * @return array<string, array<string, mixed>>
 */
function rinews_get_widget_image_sizes_config() {
	$items     = rinews_read_widget_image_sizes_folder();
	$overrides = get_option( 'rinews_widget_image_sizes', array() );

	if ( is_array( $overrides ) ) {
		foreach ( $overrides as $key => $ov ) {
			if ( ! isset( $items[ $key ] ) || ! is_array( $ov ) ) {
				continue;
			}
			if ( isset( $ov['width'] ) ) {
				$items[ $key ]['width'] = max( 40, min( 2560, (int) $ov['width'] ) );
			}
			if ( isset( $ov['height'] ) ) {
				$items[ $key ]['height'] = max( 30, min( 2560, (int) $ov['height'] ) );
			}
			if ( isset( $ov['crop'] ) ) {
				$items[ $key ]['crop'] = (bool) $ov['crop'];
			}
			if ( isset( $ov['object_fit'] ) && in_array( $ov['object_fit'], array( 'cover', 'contain' ), true ) ) {
				$items[ $key ]['object_fit'] = $ov['object_fit'];
			}
		}
	}

	return $items;
}

/**
 * Daftarkan seluruh ukuran gambar widget ke WordPress via add_image_size().
 */
function rinews_register_widget_image_sizes() {
	$config = rinews_get_widget_image_sizes_config();

	// Ukuran dasar kompatibilitas mundur tema
	$card_w = isset( $config['iklan_sidebar']['width'] ) ? (int) $config['iklan_sidebar']['width'] : 526;
	$card_h = isset( $config['iklan_sidebar']['height'] ) ? (int) $config['iklan_sidebar']['height'] : 393;
	$card_c = isset( $config['iklan_sidebar']['crop'] ) ? (bool) $config['iklan_sidebar']['crop'] : true;
	add_image_size( 'rinews-card', $card_w, $card_h, $card_c );

	foreach ( $config as $item ) {
		$slug = ! empty( $item['wp_size'] ) ? sanitize_key( (string) $item['wp_size'] ) : '';
		$w    = isset( $item['width'] ) ? max( 40, (int) $item['width'] ) : 526;
		$h    = isset( $item['height'] ) ? max( 30, (int) $item['height'] ) : 393;
		$crop = isset( $item['crop'] ) ? (bool) $item['crop'] : true;
		if ( '' !== $slug ) {
			add_image_size( $slug, $w, $h, $crop );
		}
	}
}

/**
 * Ambil nama wp_size WordPress yang terkonfigurasi untuk sebuah widget key.
 *
 * @param string $widget_key Key widget di folder ukuran-gambar-widget.
 * @return string
 */
function rinews_get_widget_image_size_slug( $widget_key ) {
	$config = rinews_get_widget_image_sizes_config();
	if ( isset( $config[ $widget_key ]['wp_size'] ) && '' !== (string) $config[ $widget_key ]['wp_size'] ) {
		return sanitize_key( (string) $config[ $widget_key ]['wp_size'] );
	}
	return 'rinews-card';
}

/**
 * Periksa apakah dimensi target gambar termasuk ukuran yang dikelola tema RINEWS.
 *
 * @param int $dest_w Lebar target.
 * @param int $dest_h Tinggi target.
 * @return bool
 */
function rinews_is_managed_image_dimensions( $dest_w, $dest_h ) {
	$dw = (int) $dest_w;
	$dh = (int) $dest_h;

	$base_pairs = array(
		array( 526, 393 ),
		array( 1052, 786 ),
		array( 263, 197 ),
	);
	foreach ( $base_pairs as $pair ) {
		if ( $dw === $pair[0] && $dh === $pair[1] ) {
			return true;
		}
	}

	$config = rinews_get_widget_image_sizes_config();
	foreach ( $config as $item ) {
		if ( empty( $item['crop'] ) ) {
			continue;
		}
		if ( $dw === (int) $item['width'] && $dh === (int) $item['height'] ) {
			return true;
		}
	}

	return false;
}

/**
 * Tampilkan semua ukuran gambar widget di pemilih ukuran Pustaka Media & Gutenberg.
 *
 * @param array<string, string> $sizes Daftar ukuran bawaan WordPress.
 * @return array<string, string>
 */
function rinews_widget_image_size_names_choose( $sizes ) {
	$config = rinews_get_widget_image_sizes_config();
	$custom = array(
		'rinews-card' => __( 'RINEWS Standar Kartu (526 x 393 px)', 'rinews' ),
	);

	foreach ( $config as $item ) {
		$slug = ! empty( $item['wp_size'] ) ? sanitize_key( (string) $item['wp_size'] ) : '';
		if ( '' === $slug ) {
			continue;
		}
		$custom[ $slug ] = sprintf(
			'%s (%dx%d px)',
			(string) $item['label'],
			(int) $item['width'],
			(int) $item['height']
		);
	}

	return array_merge( $sizes, $custom );
}
add_filter( 'image_size_names_choose', 'rinews_widget_image_size_names_choose' );

/**
 * Cetak CSS Custom Properties ukuran & rasio masing-masing widget pada <head>.
 */
function rinews_output_widget_image_css_vars() {
	$config = rinews_get_widget_image_sizes_config();
	$lines  = array();

	foreach ( $config as $item ) {
		$w   = isset( $item['width'] ) ? max( 40, (int) $item['width'] ) : 526;
		$h   = isset( $item['height'] ) ? max( 30, (int) $item['height'] ) : 393;
		$fit = isset( $item['object_fit'] ) && 'contain' === $item['object_fit'] ? 'contain' : 'cover';

		if ( ! empty( $item['css_ratio_var'] ) ) {
			$ratio_var = preg_replace( '/[^a-zA-Z0-9_-]/', '', (string) $item['css_ratio_var'] );
			$fit_var   = str_replace( '--rn-ratio-', '--rn-fit-', $ratio_var );
			$lines[]   = $ratio_var . ': ' . $w . ' / ' . $h . ';';
			$lines[]   = $fit_var . ': ' . $fit . ';';
		}

		if ( ! empty( $item['css_maxh_var'] ) ) {
			$maxh_var = preg_replace( '/[^a-zA-Z0-9_-]/', '', (string) $item['css_maxh_var'] );
			$lines[]  = $maxh_var . ': ' . max( 36, min( 240, $h ) ) . 'px;';
		}

		if ( ! empty( $item['css_maxw_var'] ) ) {
			$maxw_var = preg_replace( '/[^a-zA-Z0-9_-]/', '', (string) $item['css_maxw_var'] );
			$lines[]  = $maxw_var . ': ' . max( 200, min( 1360, $w ) ) . 'px;';
		}

		if ( ! empty( $item['css_minh_var'] ) ) {
			$minh_var = preg_replace( '/[^a-zA-Z0-9_-]/', '', (string) $item['css_minh_var'] );
			$min_h_px = max( 60, min( 360, (int) round( $h * 0.55 ) ) );
			$lines[]  = $minh_var . ': ' . $min_h_px . 'px;';
		}
	}

	if ( empty( $lines ) ) {
		return;
	}
	?>
	<style id="rinews-widget-image-size-vars">
		:root {
			<?php echo implode( "\n\t\t\t", $lines ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		}
	</style>
	<?php
}
add_action( 'wp_head', 'rinews_output_widget_image_css_vars', 25 );

/**
 * Halaman Admin WordPress: Tampilan -> Ukuran Gambar Widget.
 */
function rinews_register_widget_image_sizes_admin_page() {
	add_theme_page(
		__( 'Ukuran Gambar Widget RINEWS.ID', 'rinews' ),
		__( 'Ukuran Gambar Widget', 'rinews' ),
		'manage_options',
		'rinews-widget-image-sizes',
		'rinews_render_widget_image_sizes_admin_page'
	);
}
add_action( 'admin_menu', 'rinews_register_widget_image_sizes_admin_page' );

function rinews_render_widget_image_sizes_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$notice = '';

	if ( isset( $_POST['rinews_reset_widget_sizes'] ) ) {
		check_admin_referer( 'rinews_widget_sizes_save_action', 'rinews_widget_sizes_nonce' );
		delete_option( 'rinews_widget_image_sizes' );
		$notice = __( 'Pengaturan ukuran gambar widget berhasil dikembalikan ke bawaan folder /ukuran-gambar-widget/*.json.', 'rinews' );
	} elseif ( isset( $_POST['rinews_save_widget_sizes'] ) ) {
		check_admin_referer( 'rinews_widget_sizes_save_action', 'rinews_widget_sizes_nonce' );
		$folder_defaults = rinews_read_widget_image_sizes_folder();
		$raw_input       = isset( $_POST['rn_sizes'] ) && is_array( $_POST['rn_sizes'] ) ? wp_unslash( $_POST['rn_sizes'] ) : array();
		$sanitized       = array();

		foreach ( $folder_defaults as $key => $def ) {
			$row = isset( $raw_input[ $key ] ) && is_array( $raw_input[ $key ] ) ? $raw_input[ $key ] : array();
			$fit = isset( $row['object_fit'] ) && 'contain' === $row['object_fit'] ? 'contain' : 'cover';

			$sanitized[ $key ] = array(
				'width'      => isset( $row['width'] ) ? max( 40, min( 2560, (int) $row['width'] ) ) : (int) $def['width'],
				'height'     => isset( $row['height'] ) ? max( 30, min( 2560, (int) $row['height'] ) ) : (int) $def['height'],
				'crop'       => ! empty( $row['crop'] ) ? 1 : 0,
				'object_fit' => $fit,
			);
		}

		update_option( 'rinews_widget_image_sizes', $sanitized );
		$notice = __( 'Pengaturan ukuran gambar masing-masing widget berhasil disimpan! Foto unggahan baru & tampilan frontend langsung menyesuaikan ukuran baru.', 'rinews' );
	}

	$items = rinews_get_widget_image_sizes_config();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Pengaturan Ukuran Gambar Masing-Masing Widget — RINEWS.ID', 'rinews' ); ?></h1>
		<p>
			<?php
			printf(
				/* translators: %s: relative folder path inside theme */
				esc_html__( 'Konfigurasi bawaan dibaca otomatis dari folder tema %s. Anda dapat menyesuaikan Lebar (px), Tinggi (px), Crop otomatis, dan Mode Penyesuaian (Cover/Contain) untuk setiap widget di bawah ini.', 'rinews' ),
				'<code>/ukuran-gambar-widget/*.json</code>'
			);
			?>
		</p>

		<?php if ( '' !== $notice ) : ?>
			<div class="notice notice-success is-dismissible"><p><strong><?php echo esc_html( $notice ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post">
			<?php wp_nonce_field( 'rinews_widget_sizes_save_action', 'rinews_widget_sizes_nonce' ); ?>
			<table class="widefat striped" style="max-width:1160px; margin-top:12px;">
				<thead>
					<tr>
						<th style="width:26%;"><?php esc_html_e( 'Widget & File JSON', 'rinews' ); ?></th>
						<th style="width:14%;"><?php esc_html_e( 'Slug WP', 'rinews' ); ?></th>
						<th style="width:10%;"><?php esc_html_e( 'Lebar (px)', 'rinews' ); ?></th>
						<th style="width:10%;"><?php esc_html_e( 'Tinggi (px)', 'rinews' ); ?></th>
						<th style="width:9%;"><?php esc_html_e( 'Crop', 'rinews' ); ?></th>
						<th style="width:13%;"><?php esc_html_e( 'Mode Gambar', 'rinews' ); ?></th>
						<th><?php esc_html_e( 'Keterangan', 'rinews' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $items as $key => $it ) : ?>
						<tr>
							<td>
								<strong><?php echo esc_html( (string) $it['label'] ); ?></strong><br />
								<code style="font-size:11px;"><?php echo esc_html( isset( $it['file'] ) ? 'ukuran-gambar-widget/' . $it['file'] : $key ); ?></code>
							</td>
							<td><code><?php echo esc_html( (string) $it['wp_size'] ); ?></code></td>
							<td>
								<input type="number" min="40" max="2560" name="rn_sizes[<?php echo esc_attr( $key ); ?>][width]" value="<?php echo (int) $it['width']; ?>" class="small-text" />
							</td>
							<td>
								<input type="number" min="30" max="2560" name="rn_sizes[<?php echo esc_attr( $key ); ?>][height]" value="<?php echo (int) $it['height']; ?>" class="small-text" />
							</td>
							<td>
								<label>
									<input type="checkbox" name="rn_sizes[<?php echo esc_attr( $key ); ?>][crop]" value="1" <?php checked( ! empty( $it['crop'] ) ); ?> />
									<?php esc_html_e( 'Potong', 'rinews' ); ?>
								</label>
							</td>
							<td>
								<select name="rn_sizes[<?php echo esc_attr( $key ); ?>][object_fit]">
									<option value="cover" <?php selected( isset( $it['object_fit'] ) ? $it['object_fit'] : 'cover', 'cover' ); ?>>Penuh (cover)</option>
									<option value="contain" <?php selected( isset( $it['object_fit'] ) ? $it['object_fit'] : 'cover', 'contain' ); ?>>Utuh (contain)</option>
								</select>
							</td>
							<td style="font-size:12px; color:#475569;"><?php echo esc_html( isset( $it['notes'] ) ? (string) $it['notes'] : '' ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<p class="submit" style="display:flex; gap:10px; align-items:center;">
				<button type="submit" name="rinews_save_widget_sizes" class="button button-primary button-large">
					<?php esc_html_e( 'Simpan Pengaturan Ukuran Gambar', 'rinews' ); ?>
				</button>
				<button type="submit" name="rinews_reset_widget_sizes" class="button button-secondary">
					<?php esc_html_e( 'Reset ke Folder JSON Default', 'rinews' ); ?>
				</button>
			</p>
			<p class="description">
				<?php esc_html_e( 'Catatan: Jika Anda mengubah dimensi px pada website yang sudah memiliki banyak foto lama di Pustaka Media, jalankan plugin "Regenerate Thumbnails" sekali agar file gambar fisik lama ikut diperbarui.', 'rinews' ); ?>
			</p>
		</form>
	</div>
	<?php
}
