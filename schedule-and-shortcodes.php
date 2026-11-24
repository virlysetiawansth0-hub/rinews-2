<?php
/**
 * RINEWS.ID — Pengaturan Jam Tayang Mandiri (Hari, Tanggal, Tahun & Jam),
 * Kontrol Semua Widget, serta Shortcode Kompatibel Elementor / Gutenberg / Classic Editor.
 *
 * @package RINEWS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Daftar nama hari dalam Bahasa Indonesia
 */
function rinews_get_weekdays_id() {
    return array( 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu' );
}

/**
 * Ambil waktu tayang aktif portal (Otomatis WordPress atau Pengaturan Manual Hari/Tanggal/Tahun/Jam)
 */
function rinews_get_broadcast_time_data() {
    $opt = get_option( 'rinews_broadcast_schedule', array() );
    $mode = isset( $opt['mode'] ) ? $opt['mode'] : 'auto';

    $day_map = array(
        'Sunday'    => 'Minggu',
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
        'Saturday'  => 'Sabtu',
    );

    $tz     = wp_timezone();
    $now_dt = current_datetime();
    $now_ts = $now_dt->getTimestamp();

    if ( 'custom' === $mode && ! empty( $opt['custom_date'] ) ) {
        $custom_date = sanitize_text_field( (string) $opt['custom_date'] );
        $custom_time = ! empty( $opt['custom_time'] ) ? sanitize_text_field( (string) $opt['custom_time'] ) : '08:00';
        try {
            $dt = new DateTimeImmutable( $custom_date . ' ' . $custom_time, $tz );
            $ts = $dt->getTimestamp();
        } catch ( Exception $e ) {
            $ts = $now_ts;
        }
        $en_day   = wp_date( 'l', $ts, $tz );
        $day_name = ! empty( $opt['custom_day'] ) ? sanitize_text_field( (string) $opt['custom_day'] ) : ( isset( $day_map[ $en_day ] ) ? $day_map[ $en_day ] : 'Senin' );
        return array(
            'is_custom'  => true,
            'timestamp'  => $ts,
            'day_name'   => $day_name,
            'date_iso'   => wp_date( 'Y-m-d', $ts, $tz ),
            'time_hm'    => wp_date( 'H:i', $ts, $tz ),
            'formatted'  => $day_name . ', ' . wp_date( 'j F Y', $ts, $tz ) . ' · ' . wp_date( 'H:i', $ts, $tz ) . ' WIB',
        );
    }

    $en_day   = wp_date( 'l', $now_ts, $tz );
    $day_name = isset( $day_map[ $en_day ] ) ? $day_map[ $en_day ] : wp_date( 'l', $now_ts, $tz );

    return array(
        'is_custom'  => false,
        'timestamp'  => $now_ts,
        'day_name'   => $day_name,
        'date_iso'   => wp_date( 'Y-m-d', $now_ts, $tz ),
        'time_hm'    => wp_date( 'H:i', $now_ts, $tz ),
        'formatted'  => $day_name . ', ' . wp_date( 'j F Y', $now_ts, $tz ) . ' · ' . wp_date( 'H:i', $now_ts, $tz ) . ' WIB',
    );
}

function rinews_get_broadcast_clock_label() {
    $data = rinews_get_broadcast_time_data();
    return $data['formatted'];
}

/**
 * Evaluasi apakah sebuah Widget sedang berada dalam jadwal jam tayangnya (Hari, Tanggal/Tahun & Jam)
 */
function rinews_is_widget_scheduled( $instance ) {
    if ( ! empty( $instance['widget_disabled'] ) ) {
        return false;
    }
    if ( empty( $instance['sched_enabled'] ) ) {
        return true;
    }

    $now = rinews_get_broadcast_time_data();

    // Cek Hari Aktif
    if ( ! empty( $instance['sched_days'] ) && is_array( $instance['sched_days'] ) ) {
        if ( ! in_array( $now['day_name'], $instance['sched_days'], true ) ) {
            return false;
        }
    }

    // Cek Rentang Tanggal / Bulan / Tahun (YYYY-MM-DD)
    if ( ! empty( $instance['sched_date_start'] ) && $now['date_iso'] < $instance['sched_date_start'] ) {
        return false;
    }
    if ( ! empty( $instance['sched_date_end'] ) && $now['date_iso'] > $instance['sched_date_end'] ) {
        return false;
    }

    // Cek Rentang Jam Tayang (HH:MM)
    $start_t = ! empty( $instance['sched_time_start'] ) ? $instance['sched_time_start'] : '00:00';
    $end_t   = ! empty( $instance['sched_time_end'] ) ? $instance['sched_time_end'] : '23:59';

    if ( $start_t <= $end_t ) {
        if ( $now['time_hm'] < $start_t || $now['time_hm'] > $end_t ) {
            return false;
        }
    } else {
        if ( $now['time_hm'] < $start_t && $now['time_hm'] > $end_t ) {
            return false;
        }
    }

    return true;
}

/**
 * Render field Pengaturan Jam Tayang Sendiri di dalam form setiap Widget WordPress
 */
function rinews_render_widget_schedule_fields( $widget, $instance ) {
    $disabled   = ! empty( $instance['widget_disabled'] );
    $enabled    = ! empty( $instance['sched_enabled'] );
    $days       = isset( $instance['sched_days'] ) && is_array( $instance['sched_days'] ) ? $instance['sched_days'] : rinews_get_weekdays_id();
    $date_start = isset( $instance['sched_date_start'] ) ? $instance['sched_date_start'] : '';
    $date_end   = isset( $instance['sched_date_end'] ) ? $instance['sched_date_end'] : '';
    $time_start = isset( $instance['sched_time_start'] ) ? $instance['sched_time_start'] : '00:00';
    $time_end   = isset( $instance['sched_time_end'] ) ? $instance['sched_time_end'] : '23:59';
    ?>
    <div style="margin-top:10px; padding:10px; background:#F8FAFC; border:1px solid #CBD5E1; border-radius:6px;">
        <strong style="display:block; margin-bottom:6px; color:#0F172A;">Pengaturan Jam Tayang Widget (Hari, Tanggal/Tahun &amp; Jam):</strong>
        <p style="margin:4px 0;">
            <label>
                <input type="checkbox" name="<?php echo esc_attr( $widget->get_field_name( 'widget_disabled' ) ); ?>" value="1" <?php checked( $disabled ); ?> />
                <span>Sembunyikan widget ini sementara</span>
            </label>
        </p>
        <p style="margin:4px 0;">
            <label>
                <input type="checkbox" name="<?php echo esc_attr( $widget->get_field_name( 'sched_enabled' ) ); ?>" value="1" <?php checked( $enabled ); ?> />
                <span>Aktifkan Jadwal Tayang Khusus (Hari, Tanggal/Tahun &amp; Jam)</span>
            </label>
        </p>
        <p style="margin:6px 0;">
            <span style="display:block; font-size:11px; font-weight:700; margin-bottom:4px;">Pilih Hari Tayang:</span>
            <?php foreach ( rinews_get_weekdays_id() as $d ) : ?>
                <label style="display:inline-block; margin-right:8px; font-size:11px;">
                    <input type="checkbox" name="<?php echo esc_attr( $widget->get_field_name( 'sched_days' ) ); ?>[]" value="<?php echo esc_attr( $d ); ?>" <?php checked( in_array( $d, $days, true ) ); ?> />
                    <?php echo esc_html( $d ); ?>
                </label>
            <?php endforeach; ?>
        </p>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:6px; margin-top:6px;">
            <div>
                <label style="font-size:11px; font-weight:700;">Tanggal/Tahun Mulai:</label>
                <input class="widefat" type="date" name="<?php echo esc_attr( $widget->get_field_name( 'sched_date_start' ) ); ?>" value="<?php echo esc_attr( $date_start ); ?>" />
            </div>
            <div>
                <label style="font-size:11px; font-weight:700;">Tanggal/Tahun Selesai:</label>
                <input class="widefat" type="date" name="<?php echo esc_attr( $widget->get_field_name( 'sched_date_end' ) ); ?>" value="<?php echo esc_attr( $date_end ); ?>" />
            </div>
            <div>
                <label style="font-size:11px; font-weight:700;">Jam Mulai (WIB):</label>
                <input class="widefat" type="time" name="<?php echo esc_attr( $widget->get_field_name( 'sched_time_start' ) ); ?>" value="<?php echo esc_attr( $time_start ); ?>" />
            </div>
            <div>
                <label style="font-size:11px; font-weight:700;">Jam Selesai (WIB):</label>
                <input class="widefat" type="time" name="<?php echo esc_attr( $widget->get_field_name( 'sched_time_end' ) ); ?>" value="<?php echo esc_attr( $time_end ); ?>" />
            </div>
        </div>
    </div>
    <?php
}

/**
 * Sanitasi field jadwal widget saat disimpan di WordPress
 */
function rinews_sanitize_widget_schedule_fields( $new_instance ) {
    $valid_days = rinews_get_weekdays_id();
    $picked     = isset( $new_instance['sched_days'] ) && is_array( $new_instance['sched_days'] )
        ? array_values( array_intersect( $new_instance['sched_days'], $valid_days ) )
        : $valid_days;

    return array(
        'widget_disabled'  => ! empty( $new_instance['widget_disabled'] ) ? 1 : 0,
        'sched_enabled'    => ! empty( $new_instance['sched_enabled'] ) ? 1 : 0,
        'sched_days'       => $picked,
        'sched_date_start' => isset( $new_instance['sched_date_start'] ) ? sanitize_text_field( $new_instance['sched_date_start'] ) : '',
        'sched_date_end'   => isset( $new_instance['sched_date_end'] ) ? sanitize_text_field( $new_instance['sched_date_end'] ) : '',
        'sched_time_start' => isset( $new_instance['sched_time_start'] ) ? sanitize_text_field( $new_instance['sched_time_start'] ) : '00:00',
        'sched_time_end'   => isset( $new_instance['sched_time_end'] ) ? sanitize_text_field( $new_instance['sched_time_end'] ) : '23:59',
    );
}

/**
 * Halaman Admin: Tampilan -> Widget & Jam Tayang RINEWS.ID
 */
function rinews_register_schedule_admin_menu() {
    add_theme_page(
        __( 'Pengaturan Jam Tayang & Semua Widget', 'rinews' ),
        __( 'Widget & Jam Tayang', 'rinews' ),
        'manage_options',
        'rinews-schedule',
        'rinews_render_schedule_admin_page'
    );
}
add_action( 'admin_menu', 'rinews_register_schedule_admin_menu' );

function rinews_render_schedule_admin_page() {
    $saved = false;
    if ( isset( $_POST['rinews_save_schedule'] ) && check_admin_referer( 'rinews_schedule_nonce' ) ) {
        $opt = array(
            'mode'        => ( isset( $_POST['rn_time_mode'] ) && 'custom' === $_POST['rn_time_mode'] ) ? 'custom' : 'auto',
            'custom_day'  => isset( $_POST['rn_custom_day'] ) ? sanitize_text_field( wp_unslash( $_POST['rn_custom_day'] ) ) : '',
            'custom_date' => isset( $_POST['rn_custom_date'] ) ? sanitize_text_field( wp_unslash( $_POST['rn_custom_date'] ) ) : wp_date( 'Y-m-d' ),
            'custom_time' => isset( $_POST['rn_custom_time'] ) ? sanitize_text_field( wp_unslash( $_POST['rn_custom_time'] ) ) : wp_date( 'H:i' ),
        );
        update_option( 'rinews_broadcast_schedule', $opt );
        $saved = true;
    }

    $opt       = get_option( 'rinews_broadcast_schedule', array() );
    $mode      = isset( $opt['mode'] ) ? $opt['mode'] : 'auto';
    $c_day     = isset( $opt['custom_day'] ) ? $opt['custom_day'] : '';
    $c_date    = isset( $opt['custom_date'] ) ? $opt['custom_date'] : wp_date( 'Y-m-d' );
    $c_time    = isset( $opt['custom_time'] ) ? $opt['custom_time'] : wp_date( 'H:i' );
    $effective = rinews_get_broadcast_time_data();
    ?>
    <div class="wrap">
        <h1>Pengaturan Sendiri Jam Tayang &amp; Semua Widget RINEWS.ID</h1>
        <?php if ( $saved ) : ?>
            <div class="notice notice-success is-dismissible">
                <p><strong>Tersimpan!</strong> Pengaturan Hari, Tanggal, Tahun &amp; Jam Tayang portal telah diperbarui.</p>
            </div>
        <?php endif; ?>

        <div class="card" style="max-width:860px; padding:20px; margin-bottom:20px;">
            <h2>1. Pengaturan Jam Tayang Portal (Hari, Tanggal, Tahun &amp; Jam)</h2>
            <p>Waktu tayang aktif saat ini: <strong><?php echo esc_html( $effective['formatted'] ); ?></strong></p>
            <form method="post">
                <?php wp_nonce_field( 'rinews_schedule_nonce' ); ?>
                <table class="form-table">
                    <tr>
                        <th>Mode Jam Tayang</th>
                        <td>
                            <label style="margin-right:16px;"><input type="radio" name="rn_time_mode" value="auto" <?php checked( $mode, 'auto' ); ?> /> Otomatis (Waktu Server WordPress WIB)</label>
                            <label><input type="radio" name="rn_time_mode" value="custom" <?php checked( $mode, 'custom' ); ?> /> Atur Sendiri (Hari, Tanggal, Tahun &amp; Jam Manual)</label>
                        </td>
                    </tr>
                    <tr>
                        <th>Hari Tayang</th>
                        <td>
                            <select name="rn_custom_day">
                                <option value="">Otomatis dari Tanggal</option>
                                <?php foreach ( rinews_get_weekdays_id() as $day ) : ?>
                                    <option value="<?php echo esc_attr( $day ); ?>" <?php selected( $c_day, $day ); ?>><?php echo esc_html( $day ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal, Bulan &amp; Tahun</th>
                        <td><input type="date" name="rn_custom_date" value="<?php echo esc_attr( $c_date ); ?>" /></td>
                    </tr>
                    <tr>
                        <th>Jam Tayang (WIB)</th>
                        <td><input type="time" name="rn_custom_time" value="<?php echo esc_attr( $c_time ); ?>" /></td>
                    </tr>
                </table>
                <p><button type="submit" name="rinews_save_schedule" class="button button-primary button-hero">Simpan Pengaturan Jam Tayang</button></p>
            </form>
        </div>

        <div class="card" style="max-width:860px; padding:20px;">
            <h2>2. Shortcode Siap Pakai untuk Semua Plugin (Elementor, WPBakery, Gutenberg, Classic Editor)</h2>
            <p>Tempelkan shortcode di bawah ke dalam halaman lama, Elementor Shortcode Widget, atau Gutenberg:</p>
            <ul style="list-style:disc; padding-left:20px; line-height:1.9;">
                <li><code>[rinews_slide_berita]</code> — Slide Berita Baru (Hanya Judul) + 6 Foto Thumbnail (Hanya Foto) + 3 Teratas</li>
                <li><code>[rinews_berita_viral]</code> — Kolom Berita Viral (Pembaca Terbanyak)</li>
                <li><code>[rinews_kategori_berita title="NASIONAL" count="4" layout="horizontal"]</code> — Blok Kategori Berita</li>
                <li><code>[rinews_live_streaming]</code> — Pemutar Video LIVE Streaming Portrait 9:16 (TikTok Live + Izin Admin)</li>
                <li><code>[rinews_tiktok_feed]</code> — 4 Tayangan Teratas TikTok @ri.news.portal</li>
                <li><code>[rinews_youtube_feed]</code> — Video Berita YouTube Otomatis</li>
                <li><code>[rinews_jam_tayang]</code> — Menampilkan Hari, Tanggal, Tahun &amp; Jam Tayang Aktif</li>
            </ul>
            <p style="margin-top:12px;">
                <a href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>" class="button button-secondary">Buka Pengaturan Lengkap 16 Area Widget &rarr;</a>
            </p>
        </div>
    </div>
    <?php
}

/**
 * Shortcodes untuk kompatibilitas maksimal dengan Elementor, WPBakery, Divi, Gutenberg & Website Lama
 */
add_shortcode( 'rinews_jam_tayang', function() {
    return '<span class="rn-shortcode-clock">' . esc_html( rinews_get_broadcast_clock_label() ) . '</span>';
} );

add_shortcode( 'rinews_slide_berita', function( $atts = array() ) {
    $a = shortcode_atts( array( 'slide_count' => 6 ), (array) $atts, 'rinews_slide_berita' );
    ob_start();
    the_widget( 'RINEWS_Widget_Slide_Berita_Baru', array( 'slide_count' => (int) $a['slide_count'] ) );
    return ob_get_clean();
} );

add_shortcode( 'rinews_berita_viral', function( $atts = array() ) {
    $a = shortcode_atts( array( 'title' => 'BERITA VIRAL', 'count' => 3 ), (array) $atts, 'rinews_berita_viral' );
    ob_start();
    the_widget( 'RINEWS_Widget_Berita_Viral', array( 'title' => sanitize_text_field( $a['title'] ), 'count' => (int) $a['count'] ) );
    return ob_get_clean();
} );

add_shortcode( 'rinews_kategori_berita', function( $atts = array() ) {
    $a = shortcode_atts( array( 'title' => 'NASIONAL', 'cat_id' => 0, 'count' => 4, 'offset' => 0, 'layout' => 'horizontal' ), (array) $atts, 'rinews_kategori_berita' );
    ob_start();
    the_widget( 'RINEWS_Widget_Kategori_Berita', array(
        'title'  => sanitize_text_field( $a['title'] ),
        'cat_id' => (int) $a['cat_id'],
        'count'  => (int) $a['count'],
        'offset' => max( 0, (int) $a['offset'] ),
        'layout' => sanitize_key( $a['layout'] ),
    ) );
    return ob_get_clean();
} );

add_shortcode( 'rinews_live_streaming', function() {
    ob_start();
    the_widget( 'RINEWS_Widget_Live_Streaming' );
    return ob_get_clean();
} );

add_shortcode( 'rinews_tiktok_feed', function() {
    ob_start();
    the_widget( 'RINEWS_Widget_TikTok_Feed' );
    return ob_get_clean();
} );

add_shortcode( 'rinews_youtube_feed', function() {
    ob_start();
    the_widget( 'RINEWS_Widget_YouTube_Feed' );
    return ob_get_clean();
} );
