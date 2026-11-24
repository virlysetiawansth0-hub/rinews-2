<?php
/**
 * RINEWS.ID — Integrasi TikTok Aktif Otomatis (oEmbed, Auto-Seed Widget, & Sinkronisasi Profil)
 * Memastikan widget TikTok Video & LIVE Streaming Portrait langsung aktif segera setelah tema diunggah.
 *
 * @package RINEWS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Daftarkan penyedia oEmbed TikTok secara eksplisit agar tautan video TikTok langsung aktif di artikel & widget.
 */
function rinews_register_tiktok_oembed_provider() {
	if ( function_exists( 'wp_oembed_add_provider' ) ) {
		wp_oembed_add_provider( '#https?://(www\.)?tiktok\.com/@[^/]+/video/\d+.*#i', 'https://www.tiktok.com/oembed', true );
		wp_oembed_add_provider( '#https?://(www\.)?tiktok\.com/t/[a-zA-Z0-9]+/?.*#i', 'https://www.tiktok.com/oembed', true );
		wp_oembed_add_provider( '#https?://(vt|vm)\.tiktok\.com/.*#i', 'https://www.tiktok.com/oembed', true );
	}
}
add_action( 'init', 'rinews_register_tiktok_oembed_provider', 5 );

/**
 * Ekstrak ID Video TikTok numerik dari URL video TikTok.
 *
 * @param string $url URL video TikTok.
 * @return string
 */
function rinews_extract_tiktok_video_id( $url ) {
	$url = trim( (string) $url );
	if ( '' === $url ) {
		return '';
	}
	if ( preg_match( '#/video/(\d+)#i', $url, $m ) ) {
		return $m[1];
	}
	if ( preg_match( '#/v/(\d+)#i', $url, $m ) ) {
		return $m[1];
	}
	return '';
}

/**
 * Aktifkan widget TikTok Video & LIVE Streaming otomatis segera setelah tema diunggah/diaktifkan.
 * Aman untuk website lama: hanya mengisi slot widget jika masih kosong (tidak menimpa widget yang sudah ada).
 */
function rinews_ensure_tiktok_widgets_active() {
	if ( ! is_blog_installed() ) {
		return;
	}

	$activated_ver = get_option( 'rinews_tiktok_autoactivated_ver', '' );
	$sidebars      = get_option( 'sidebars_widgets', array() );
	if ( ! is_array( $sidebars ) ) {
		$sidebars = array();
	}

	// Hindari overwrite apabila sudah terkonfigurasi pada versi aktif saat ini
	$has_tiktok_sidebar = ! empty( $sidebars['widget-tiktok-video'] ) && is_array( $sidebars['widget-tiktok-video'] );
	$has_live_sidebar   = ! empty( $sidebars['widget-live-streaming'] ) && is_array( $sidebars['widget-live-streaming'] );

	if ( defined( 'RINEWS_VERSION' ) && RINEWS_VERSION === $activated_ver && $has_tiktok_sidebar && $has_live_sidebar ) {
		return;
	}

	$tiktok_handle = trim( (string) get_theme_mod( 'rinews_tiktok_handle', '@ri.news.portal' ) );
	if ( '' === $tiktok_handle ) {
		$tiktok_handle = '@ri.news.portal';
	}
	$clean_handle  = '@' . ltrim( $tiktok_handle, '@' );
	$tiktok_url    = trim( (string) get_theme_mod( 'rinews_tiktok_url', 'https://www.tiktok.com/' . $clean_handle . '?lang=id-ID' ) );
	$default_vids  = trim( (string) get_theme_mod( 'rinews_tiktok_video_urls', 'https://www.tiktok.com/@ri.news.portal/video/7708887155644452110' ) );
	if ( '' === $default_vids ) {
		$default_vids = 'https://www.tiktok.com/@ri.news.portal/video/7708887155644452110';
	}

	$updated_sidebars = false;

	// 1. Pastikan Widget TikTok Video aktif di Area Widget 11
	if ( ! $has_tiktok_sidebar ) {
		$widget_tt = get_option( 'widget_rinews_widget_tiktok', array() );
		if ( ! is_array( $widget_tt ) ) {
			$widget_tt = array();
		}
		if ( empty( $widget_tt[10] ) ) {
			$widget_tt[10] = array(
				'title'       => 'TIKTOK VIDEO — 4 TAYANGAN TERATAS',
				'handle'      => $clean_handle,
				'account_url' => $tiktok_url,
				'video_urls'  => $default_vids,
			);
			update_option( 'widget_rinews_widget_tiktok', $widget_tt );
		}
		$sidebars['widget-tiktok-video'] = array( 'rinews_widget_tiktok-10' );
		$updated_sidebars                = true;
	}

	// 2. Pastikan Widget LIVE Streaming Portrait (TikTok Live) aktif di Area Widget 16
	if ( ! $has_live_sidebar ) {
		$widget_live = get_option( 'widget_rinews_widget_live_streaming', array() );
		if ( ! is_array( $widget_live ) ) {
			$widget_live = array();
		}
		if ( empty( $widget_live[10] ) ) {
			$widget_live[10] = array(
				'title'            => 'LIVE Streaming',
				'is_live_active'   => 1,
				'broadcaster_name' => 'RI News Portal (Siaran Resmi)',
				'tiktok_handle'    => $clean_handle,
				'tiktok_live_url'  => 'https://www.tiktok.com/' . $clean_handle . '/live',
				'stream_video_url' => 'https://www.rinews.id/wp-content/uploads/2025/07/Video-Spot-RINEWS.mp4',
				'stream_topic'     => 'Siaran Langsung Portal Berita RINEWS.ID & Kontributor Terverifikasi',
				'screen_size'      => 'standard',
				'screen_fit'       => 'cover',
				'rules_summary'    => '1. Siaran terbuka untuk umum dengan izin & persetujuan resmi Admin RINEWS.ID. 2. Wajib menautkan akun TikTok Live aktif serta mematuhi Kode Etik Jurnalistik, UU ITE, dan bebas SARA/hoaks. 3. Admin berhak menghentikan siaran sewaktu-waktu bila terjadi pelanggaran.',
			);
			update_option( 'widget_rinews_widget_live_streaming', $widget_live );
		}
		$sidebars['widget-live-streaming'] = array( 'rinews_widget_live_streaming-10' );
		$updated_sidebars                  = true;
	}

	if ( $updated_sidebars ) {
		update_option( 'sidebars_widgets', $sidebars );
	}

	if ( defined( 'RINEWS_VERSION' ) ) {
		update_option( 'rinews_tiktok_autoactivated_ver', RINEWS_VERSION );
	}
}
add_action( 'after_switch_theme', 'rinews_ensure_tiktok_widgets_active', 15 );
add_action( 'init', 'rinews_ensure_tiktok_widgets_active', 20 );

/**
 * Temukan video terbaru dari halaman profil publik TikTok (jika server hosting diizinkan mengakses TikTok).
 *
 * @param string $handle Username TikTok (dengan atau tanpa @).
 * @param int    $limit  Maksimal URL video.
 * @return array<int, string>
 */
function rinews_discover_tiktok_profile_videos( $handle, $limit = 4 ) {
	$clean_handle = ltrim( trim( (string) $handle ), '@' );
	if ( '' === $clean_handle || ! get_theme_mod( 'rinews_tiktok_auto_sync', true ) ) {
		return array();
	}

	$cache_key = 'rinews_tt_prof_' . md5( strtolower( $clean_handle ) );
	$cached    = get_transient( $cache_key );
	if ( is_array( $cached ) ) {
		return array_slice( $cached, 0, $limit );
	}

	$found = array();
	$resp  = wp_remote_get(
		'https://www.tiktok.com/@' . rawurlencode( $clean_handle ),
		array(
			'timeout' => 6,
			'headers' => array(
				'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
				'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8',
			),
		)
	);

	if ( ! is_wp_error( $resp ) && 200 === wp_remote_retrieve_response_code( $resp ) ) {
		$body = (string) wp_remote_retrieve_body( $resp );
		if ( preg_match_all( '#https?:\\\\/\\\\/www\.tiktok\.com\\\\/@' . preg_quote( $clean_handle, '#' ) . '\\\\/video\\\\/(\d+)#i', $body, $m_escaped ) ) {
			foreach ( $m_escaped[1] as $vid ) {
				$url = 'https://www.tiktok.com/@' . $clean_handle . '/video/' . $vid;
				if ( ! in_array( $url, $found, true ) ) {
					$found[] = $url;
				}
			}
		}
		if ( preg_match_all( '#"id"\s*:\s*"(\d{15,22})"#', $body, $m_ids ) ) {
			foreach ( $m_ids[1] as $vid ) {
				$url = 'https://www.tiktok.com/@' . $clean_handle . '/video/' . $vid;
				if ( ! in_array( $url, $found, true ) ) {
					$found[] = $url;
				}
			}
		}
	}

	$found = array_slice( $found, 0, max( 4, $limit ) );
	set_transient( $cache_key, $found, 20 * MINUTE_IN_SECONDS );
	return array_slice( $found, 0, $limit );
}

/**
 * Ambil data 4 tayangan TikTok lengkap (oEmbed + auto-discovery + fallback berita aktif) untuk Widget TikTok.
 *
 * @param string $handle          Username TikTok (@...).
 * @param string $account_url     URL Profil TikTok.
 * @param string $manual_urls_raw Daftar URL video dari Widget / Customizer.
 * @param int    $limit           Jumlah tayangan yang ditampilkan.
 * @return array<int, array<string, string>>
 */
function rinews_fetch_tiktok_feed_items( $handle, $account_url, $manual_urls_raw = '', $limit = 4 ) {
	$clean_handle = '@' . ltrim( trim( (string) $handle ), '@' );
	if ( '@' === $clean_handle ) {
		$clean_handle = '@ri.news.portal';
	}
	if ( '' === trim( (string) $account_url ) ) {
		$account_url = 'https://www.tiktok.com/' . $clean_handle . '?lang=id-ID';
	}

	// Gabungkan URL dari konfigurasi Widget & Customizer
	$customizer_urls_raw = (string) get_theme_mod( 'rinews_tiktok_video_urls', '' );
	$combined_raw        = trim( (string) $manual_urls_raw . "\n" . $customizer_urls_raw );
	$urls                = array_values( array_unique( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $combined_raw ) ) ) ) );

	// Lengkapi otomatis dari profil TikTok jika slot masih kurang dari 4
	if ( count( $urls ) < $limit ) {
		$discovered = rinews_discover_tiktok_profile_videos( $clean_handle, $limit );
		foreach ( $discovered as $du ) {
			if ( ! in_array( $du, $urls, true ) ) {
				$urls[] = $du;
			}
		}
	}

	$items   = array();
	$tt_size = function_exists( 'rinews_get_widget_image_size_slug' ) ? rinews_get_widget_image_size_slug( 'tiktok_video' ) : 'large';

	foreach ( array_slice( $urls, 0, $limit ) as $u ) {
		$vid       = rinews_extract_tiktok_video_id( $u );
		$cache_key = 'rinews_tt_item_' . md5( $u );
		$data      = get_transient( $cache_key );

		if ( false === $data ) {
			$resp = wp_remote_get( 'https://www.tiktok.com/oembed?url=' . rawurlencode( $u ), array( 'timeout' => 7 ) );
			if ( ! is_wp_error( $resp ) && 200 === wp_remote_retrieve_response_code( $resp ) ) {
				$json = json_decode( wp_remote_retrieve_body( $resp ), true );
				if ( is_array( $json ) && ! empty( $json ) ) {
					$resolved_id = ! empty( $json['embed_product_id'] ) ? preg_replace( '/\D/', '', (string) $json['embed_product_id'] ) : $vid;
					$data        = array(
						'title'    => ! empty( $json['title'] ) ? wp_strip_all_tags( (string) $json['title'] ) : 'Tayangan TikTok ' . $clean_handle,
						'thumb'    => ! empty( $json['thumbnail_url'] ) ? (string) $json['thumbnail_url'] : RINEWS_URI . '/assets/images/news_istana_jakarta.jpg',
						'url'      => $u,
						'video_id' => $resolved_id,
					);
				}
			}
			if ( ! is_array( $data ) ) {
				$data = array(
					'title'    => 'Laporan Video TikTok ' . $clean_handle,
					'thumb'    => RINEWS_URI . '/assets/images/news_istana_jakarta.jpg',
					'url'      => $u,
					'video_id' => $vid,
				);
			}
			set_transient( $cache_key, $data, 30 * MINUTE_IN_SECONDS );
		}
		$items[] = $data;
	}

	// Lengkapi hingga 4 slot dari berita terpopuler / terbaru website agar grid selalu penuh dan aktif
	if ( count( $items ) < $limit ) {
		$fallback_q = new WP_Query( array(
			'post_type'      => 'post',
			'posts_per_page' => $limit,
			'meta_key'       => 'post_views_count',
			'orderby'        => 'meta_value_num date',
			'order'          => 'DESC',
			'post_status'    => 'publish',
			'no_found_rows'  => true,
		) );
		if ( ! $fallback_q->have_posts() ) {
			$fallback_q = new WP_Query( array(
				'post_type'      => 'post',
				'posts_per_page' => $limit,
				'post_status'    => 'publish',
				'no_found_rows'  => true,
			) );
		}
		if ( $fallback_q->have_posts() ) {
			while ( $fallback_q->have_posts() && count( $items ) < $limit ) {
				$fallback_q->the_post();
				$thumb   = get_the_post_thumbnail_url( get_the_ID(), $tt_size );
				$items[] = array(
					'title'    => get_the_title(),
					'thumb'    => $thumb ? $thumb : RINEWS_URI . '/assets/images/news_regional_indonesia.jpg',
					'url'      => $account_url,
					'video_id' => '',
				);
			}
			wp_reset_postdata();
		}
	}

	if ( count( $items ) < $limit ) {
		$defaults = array(
			array( 'title' => 'Laporan Langsung Istana Negara & Kebijakan Nasional', 'thumb' => RINEWS_URI . '/assets/images/news_istana_jakarta.jpg', 'url' => $account_url, 'video_id' => '' ),
			array( 'title' => 'Buser Berita: Operasi Satgas Penegakan Hukum', 'thumb' => RINEWS_URI . '/assets/images/news_buser_hukum.jpg', 'url' => $account_url, 'video_id' => '' ),
			array( 'title' => 'Sorotan Suporter Timnas Indonesia di GBK', 'thumb' => RINEWS_URI . '/assets/images/news_olahraga_stadium.jpg', 'url' => $account_url, 'video_id' => '' ),
			array( 'title' => 'Festival Batik Tulis & Budaya Nusantara', 'thumb' => RINEWS_URI . '/assets/images/news_budaya_nusantara.jpg', 'url' => $account_url, 'video_id' => '' ),
		);
		foreach ( $defaults as $def ) {
			if ( count( $items ) >= $limit ) {
				break;
			}
			$items[] = $def;
		}
	}

	return array_slice( $items, 0, $limit );
}
