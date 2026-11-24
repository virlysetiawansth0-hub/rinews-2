<?php
/**
 * RINEWS.ID — Template Halaman Utama (Front Page)
 * Seluruh blok terhubung langsung dengan Area Widget WordPress (Tampilan -> Widget).
 *
 * @package RINEWS
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
?>

<main class="rn-container">
    <!-- 1. HERO 3 KOLOM: INDEKS PENCARIAN + 3 IKLAN | BERITA VIRAL | SLIDE BERITA BARU + 3 TERATAS -->
    <section class="rn-section">
        <div class="rn-hero-grid">
            <!-- Kolom 1: Indeks Pencarian + 3 Ruang Gambar Iklan -->
            <div class="rn-red-panel" style="display:flex; flex-direction:column;">
                <div class="rn-panel-header">
                    <h2 class="rn-panel-title">&#128269; INDEKS PENCARIAN</h2>
                </div>
                <div class="rn-white-box" style="flex:1;">
                    <form role="search" method="get" class="rn-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <input type="search" class="rn-search-input" placeholder="Cari berita, daerah, topik..." value="<?php echo esc_attr( get_search_query( false ) ); ?>" name="s" />
                        <button type="submit" class="rn-search-btn">Cari</button>
                    </form>

                    <div class="rn-sidebar-ads">
                        <?php
                        if ( is_active_sidebar( 'widget-indeks-iklan' ) ) {
                            dynamic_sidebar( 'widget-indeks-iklan' );
                        } else {
                            the_widget( 'RINEWS_Widget_Iklan_Gambar', array( 'sponsor' => 'IKLAN 1 · BANK NUSANTARA', 'title' => 'Solusi Tabungan Bisnis & QRIS', 'image_url' => RINEWS_URI . '/assets/images/iklan_korporat_digital.jpg' ) );
                            the_widget( 'RINEWS_Widget_Iklan_Gambar', array( 'sponsor' => 'IKLAN 2 · GARUDA FIBER 5G', 'title' => 'Internet Korporat 38 Provinsi', 'image_url' => RINEWS_URI . '/assets/images/news_teknologi_riset.jpg' ) );
                            the_widget( 'RINEWS_Widget_Iklan_Gambar', array( 'sponsor' => 'IKLAN 3 · NUSANTARA LOGISTIK', 'title' => 'Kargo Pelabuhan & Udara Cepat', 'image_url' => RINEWS_URI . '/assets/images/news_regional_indonesia.jpg' ) );
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Kolom 2: Berita Viral (3 Berita Pembaca Terbanyak) -->
            <div>
                <?php
                if ( is_active_sidebar( 'widget-berita-viral' ) ) {
                    dynamic_sidebar( 'widget-berita-viral' );
                } else {
                    the_widget( 'RINEWS_Widget_Berita_Viral', array( 'title' => 'BERITA VIRAL', 'count' => 3 ) );
                }
                ?>
            </div>

            <!-- Kolom 3: Slide Berita Baru (6 Pos Baru Teratas) + 3 Kolom Internasional/Nasional/Regional -->
            <div>
                <?php
                if ( is_active_sidebar( 'widget-slide-berita' ) ) {
                    dynamic_sidebar( 'widget-slide-berita' );
                } else {
                    the_widget( 'RINEWS_Widget_Slide_Berita_Baru', array( 'slide_count' => 6 ) );
                }
                ?>
            </div>
        </div>
    </section>

    <!-- 2. IKLAN PANJANG 1 -->
    <section class="rn-section">
        <?php
        if ( is_active_sidebar( 'widget-iklan-panjang-1' ) ) {
            dynamic_sidebar( 'widget-iklan-panjang-1' );
        } else {
            the_widget( 'RINEWS_Widget_Iklan_Gambar', array(
                'sponsor'   => 'IKLAN PANJANG · PT. VIRNANDA CREATOR PRODUCTIONS',
                'title'     => 'Pasang Iklan Brand & Advertorial Nasional di RINEWS.ID — Menjangkau 38 Provinsi',
                'image_url' => RINEWS_URI . '/assets/images/iklan_korporat_digital.jpg',
                'mode'      => 'leaderboard',
            ) );
        }
        ?>
    </section>

    <!-- 3. POS BARU — SATU GRUP KIRI & KANAN (20 BERITA/HAL + PAGINASI TERBARU HINGGA TERLAMA) -->
    <section id="rn-pos-baru" class="rn-section">
        <?php
        if ( is_active_sidebar( 'widget-pos-baru' ) ) {
            dynamic_sidebar( 'widget-pos-baru' );
        } else {
            the_widget( 'RINEWS_Widget_Kategori_Berita', array(
                'title'           => 'POS BARU',
                'count'           => 20,
                'offset'          => 0,
                'layout'          => 'grid_pos_baru',
                'show_subs'       => false,
                'show_pagination' => true,
            ) );
        }
        ?>
    </section>

    <!-- 4. IKLAN PANJANG 2 -->
    <section class="rn-section">
        <?php
        if ( is_active_sidebar( 'widget-iklan-panjang-2' ) ) {
            dynamic_sidebar( 'widget-iklan-panjang-2' );
        } else {
            the_widget( 'RINEWS_Widget_Iklan_Gambar', array(
                'sponsor'   => 'IKLAN PANJANG · FORUM EKONOMI & INFRASTRUKTUR',
                'title'     => 'Pameran Investasi & Hilirisasi Industri Nasional — Jakarta Convention Hall',
                'image_url' => RINEWS_URI . '/assets/images/news_bisnis_scbd.jpg',
                'mode'      => 'leaderboard',
            ) );
        }
        ?>
    </section>

    <!-- 5. INTEGRASI KATEGORI HORIZONTAL (Dapat Diedit Per Kategori di Tampilan -> Widget) -->
    <section class="rn-section" style="display:flex; flex-direction:column; gap:14px;">
        <?php
        if ( is_active_sidebar( 'widget-kategori-utama' ) ) {
            dynamic_sidebar( 'widget-kategori-utama' );
        } else {
            $default_horizontal = array( 'INTERNASIONAL', 'NASIONAL', 'REGIONAL', 'BUSER BERITA', 'OLAH RAGA', 'BISNIS' );
            foreach ( $default_horizontal as $cat_title ) {
                $term = get_term_by( 'name', $cat_title, 'category' );
                the_widget( 'RINEWS_Widget_Kategori_Berita', array(
                    'title'     => $cat_title,
                    'cat_id'    => $term ? $term->term_id : 0,
                    'count'     => 4,
                    'layout'    => 'horizontal',
                    'show_subs' => true,
                ) );
            }
        }
        ?>
    </section>

    <!-- 6. 3 KOLOM VERTIKAL: ILMU PENGETAHUAN | HIBURAN | BUDAYA -->
    <section class="rn-section">
        <div class="rn-3col-grid">
            <?php
            if ( is_active_sidebar( 'widget-kategori-vertikal' ) ) {
                dynamic_sidebar( 'widget-kategori-vertikal' );
            } else {
                foreach ( array( 'ILMU PENGETAHUAN', 'HIBURAN', 'BUDAYA' ) as $cat_title ) {
                    $term = get_term_by( 'name', $cat_title, 'category' );
                    the_widget( 'RINEWS_Widget_Kategori_Berita', array(
                        'title'     => $cat_title,
                        'cat_id'    => $term ? $term->term_id : 0,
                        'count'     => 3,
                        'layout'    => 'vertical',
                        'show_subs' => false,
                    ) );
                }
            }
            ?>
        </div>
    </section>

    <!-- 7. KOLOM HITAM (FONT PUTIH): KOMENTAR | ARSIP 12 BULAN | MUNGKIN TERLEWATKAN (H-1) -->
    <section class="rn-section">
        <div class="rn-3col-grid">
            <?php if ( is_active_sidebar( 'widget-kolom-hitam' ) ) : ?>
                <?php dynamic_sidebar( 'widget-kolom-hitam' ); ?>
            <?php else : ?>
                <!-- Kolom Hitam 1: Komentar Terbaru -->
                <div class="rn-black-box">
                    <h2 class="rn-black-heading">&#128172; KOMENTAR</h2>
                    <ul class="rn-yesterday-list">
                        <?php
                        $recent_comments = get_comments( array( 'number' => 5, 'status' => 'approve' ) );
                        if ( ! empty( $recent_comments ) ) :
                            foreach ( $recent_comments as $rc ) :
                                ?>
                                <li>
                                    <div style="font-size:11px; font-weight:800; color:#FFF;"><?php echo esc_html( $rc->comment_author ); ?></div>
                                    <p style="margin:3px 0; font-size:12px; color:#FFF;"><?php echo esc_html( wp_trim_words( $rc->comment_content, 16 ) ); ?></p>
                                    <a href="<?php echo esc_url( get_comment_link( $rc ) ); ?>" style="font-size:10.5px; text-decoration:underline; color:#FFF;">Pada: <?php echo esc_html( get_the_title( $rc->comment_post_ID ) ); ?></a>
                                </li>
                                <?php
                            endforeach;
                        else :
                            echo '<li><p style="font-size:12px; color:#FFF;">Belum ada komentar pembaca. Buka berita untuk mengirim komentar pertama.</p></li>';
                        endif;
                        ?>
                    </ul>
                </div>

                <!-- Kolom Hitam 2: Arsip 12 Bulan (Tulisan Bulan & Tahun Saja) -->
                <?php the_widget( 'RINEWS_Widget_Arsip_12_Bulan', array( 'title' => 'ARSIP' ) ); ?>

                <!-- Kolom Hitam 3: Mungkin Terlewatkan (Judul Saja, Berita H-1) -->
                <?php the_widget( 'RINEWS_Widget_Kemarin_Terlewatkan', array( 'title' => 'MUNGKIN TERLEWATKAN' ) ); ?>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
get_footer();
