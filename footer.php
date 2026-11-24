<?php
/**
 * RINEWS.ID — Footer Video Terintegrasi (TikTok & YouTube), 3 Iklan Bawah, Iklan Panjang, & Copyright
 *
 * @package RINEWS
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<footer class="rn-footer">
    <div class="rn-container">
        <!-- TIKTOK VIDEO -->
        <section class="rn-section">
            <?php
            if ( is_active_sidebar( 'widget-tiktok-video' ) ) {
                dynamic_sidebar( 'widget-tiktok-video' );
            } else {
                the_widget( 'RINEWS_Widget_TikTok_Feed' );
            }
            ?>
        </section>

        <!-- VIDEO BERITA YOUTUBE -->
        <section class="rn-section">
            <?php
            if ( is_active_sidebar( 'widget-youtube-video' ) ) {
                dynamic_sidebar( 'widget-youtube-video' );
            } else {
                the_widget( 'RINEWS_Widget_YouTube_Feed' );
            }
            ?>
        </section>

        <!-- 3 GAMBAR IKLAN BAWAH -->
        <section class="rn-section">
            <div class="rn-3col-grid">
                <?php
                if ( is_active_sidebar( 'widget-iklan-footer-3' ) ) {
                    dynamic_sidebar( 'widget-iklan-footer-3' );
                } else {
                    the_widget( 'RINEWS_Widget_Iklan_Gambar', array( 'sponsor' => 'GAMBAR IKLAN 1', 'title' => 'Kemitraan Media Pemerintah & Daerah', 'image_url' => RINEWS_URI . '/assets/images/news_regional_indonesia.jpg' ) );
                    the_widget( 'RINEWS_Widget_Iklan_Gambar', array( 'sponsor' => 'GAMBAR IKLAN 2', 'title' => 'Produksi Video Berita & Dokumenter', 'image_url' => RINEWS_URI . '/assets/images/iklan_korporat_digital.jpg' ) );
                    the_widget( 'RINEWS_Widget_Iklan_Gambar', array( 'sponsor' => 'GAMBAR IKLAN 3', 'title' => 'Pemasangan Iklan Display & Bisnis', 'image_url' => RINEWS_URI . '/assets/images/news_bisnis_scbd.jpg' ) );
                }
                ?>
            </div>
        </section>

        <!-- IKLAN PANJANG PALING BAWAH -->
        <section class="rn-section">
            <?php
            if ( is_active_sidebar( 'widget-iklan-panjang-bawah' ) ) {
                dynamic_sidebar( 'widget-iklan-panjang-bawah' );
            } else {
                the_widget( 'RINEWS_Widget_Iklan_Gambar', array(
                    'sponsor'   => 'IKLAN PANJANG · RINEWS MEDIA NETWORK',
                    'title'     => 'Kerja Sama Liputan Pemerintahan, Korporasi & Advertorial Nasional',
                    'image_url' => RINEWS_URI . '/assets/images/iklan_korporat_digital.jpg',
                    'mode'      => 'leaderboard',
                ) );
            }
            ?>
        </section>

        <!-- SPACE TENGAH DI ATAS COPYRIGHT: LIVE STREAMING PORTRAIT (TIKTOK LIVE) -->
        <section id="rinews-live-streaming" class="rn-section rn-live-center-wrap">
            <?php
            if ( is_active_sidebar( 'widget-live-streaming' ) ) {
                dynamic_sidebar( 'widget-live-streaming' );
            } else {
                the_widget( 'RINEWS_Widget_Live_Streaming' );
            }
            ?>
        </section>

        <div class="rn-footer-links">
            <a href="<?php echo esc_url( home_url( '/tentang-kami/' ) ); ?>">TENTANG KAMI</a>
            <span>-</span>
            <a href="<?php echo esc_url( home_url( '/struktur-redaksi/' ) ); ?>">STRUKTUR REDAKSI</a>
            <span>-</span>
            <a href="<?php echo esc_url( home_url( '/pedoman-media-siber/' ) ); ?>">PEDOMAN MEDIA SIBER</a>
            <span>-</span>
            <a href="<?php echo esc_url( home_url( '/kontak-iklan/' ) ); ?>">KONTAK IKLAN</a>
            <span>-</span>
            <a href="<?php echo esc_url( home_url( '/privasi-policy/' ) ); ?>">PRIVASI POLICY</a>
        </div>

        <p class="rn-copyright">
            <?php echo esc_html( get_theme_mod( 'rinews_copyright_text', 'COPYRIGHT @RINEWS | PRODUKSI 2026 | PT. VIRNANDA CREATOR PRODUCTIONS' ) ); ?>
        </p>
    </div>
</footer>

<!-- Modal YouTube Player -->
<div id="rn-yt-modal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.85); align-items:center; justify-content:center; padding:16px;">
    <div style="background:#020617; border:1px solid #334155; border-radius:14px; max-width:860px; width:100%; overflow:hidden;">
        <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 16px; border-bottom:1px solid #1E293B; color:#FFF;">
            <strong id="rn-yt-modal-title" style="font-size:13px;">Video Berita</strong>
            <button type="button" id="rn-yt-modal-close" style="background:#1E293B; color:#FFF; border:none; border-radius:6px; padding:4px 10px; cursor:pointer; font-weight:800;">Tutup</button>
        </div>
        <div style="aspect-ratio:16/9; width:100%;">
            <iframe id="rn-yt-iframe" src="" style="width:100%; height:100%; border:0;" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>
    </div>
</div>

<!-- Modal TikTok Player Portrait -->
<div id="rn-tt-modal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.88); align-items:center; justify-content:center; padding:16px;">
    <div style="background:#020617; border:1px solid #334155; border-radius:16px; max-width:410px; width:100%; overflow:hidden; box-shadow:0 24px 60px rgba(0,0,0,0.75);">
        <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 14px; border-bottom:1px solid #1E293B; color:#FFF;">
            <strong id="rn-tt-modal-title" style="font-size:12.5px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; padding-right:10px;">TikTok RINEWS.ID</strong>
            <button type="button" id="rn-tt-modal-close" style="background:#1E293B; color:#FFF; border:none; border-radius:6px; padding:4px 10px; cursor:pointer; font-weight:800; flex-shrink:0;">Tutup</button>
        </div>
        <div style="aspect-ratio:9/16; width:100%; max-height:74vh; background:#000;">
            <iframe id="rn-tt-iframe" src="" style="width:100%; height:100%; border:0;" allow="autoplay; encrypted-media; fullscreen" allowfullscreen></iframe>
        </div>
        <div style="padding:10px 14px; border-top:1px solid #1E293B; display:flex; align-items:center; justify-content:space-between; gap:8px; background:#090D16;">
            <span style="font-size:11px; color:#94A3B8;">Pemutar Resmi TikTok RINEWS.ID</span>
            <a id="rn-tt-external-link" href="https://www.tiktok.com/@ri.news.portal" target="_blank" rel="noopener" style="padding:5px 10px; border-radius:6px; background:#FF0050; color:#FFF; font-size:11px; font-weight:800;">Buka di TikTok &rarr;</a>
        </div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
