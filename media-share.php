<?php
/**
 * RINEWS.ID — Kolom Media Share Lengkap 7 Platform
 * WhatsApp, Facebook, Instagram, Twitter/X, YouTube, Copy Link, & Email
 *
 * @package RINEWS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function rinews_render_media_share_bar( $compact = true, $custom_url = '', $custom_title = '' ) {
    $permalink = is_singular() ? get_permalink() : home_url( '/' );
    $post_title = is_singular() ? get_the_title() : 'RINEWS.ID — Media Berita Republik Indonesia';

    $url   = $custom_url ? (string) $custom_url : (string) ( $permalink ? $permalink : home_url( '/' ) );
    $title = $custom_title ? (string) $custom_title : (string) ( $post_title ? $post_title : 'RINEWS.ID — Media Berita Republik Indonesia' );

    $enc_url   = rawurlencode( $url );
    $enc_title = rawurlencode( $title );

    $ig_url = (string) get_theme_mod( 'rinews_instagram_url', 'https://www.instagram.com/rinews.id/' );
    $yt_url = (string) get_theme_mod( 'rinews_youtube_url', 'https://www.youtube.com/@rinews-berita-video' );
    $btn_cls = $compact ? 'rn-share-btn' : 'rn-share-btn rn-share-btn--full';
    ?>
    <div class="rn-share-buttons" aria-label="Media Share RINEWS.ID">
        <!-- WhatsApp -->
        <a href="<?php echo esc_url( 'https://wa.me/?text=' . $enc_title . '%20' . $enc_url ); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr( $btn_cls ); ?> rn-share-wa" title="Bagikan ke WhatsApp" aria-label="WhatsApp">
            <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor" aria-hidden="true"><path d="M12.03 2C6.5 2 2 6.48 2 12c0 1.82.49 3.59 1.42 5.14L2 22l4.98-1.39A9.97 9.97 0 0 0 12.03 22C17.56 22 22 17.52 22 12S17.56 2 12.03 2Zm5.83 14.12c-.24.68-1.42 1.3-1.97 1.38-.5.08-1.15.11-1.85-.11-.43-.14-.98-.32-1.69-.63-2.97-1.28-4.91-4.27-5.06-4.47-.15-.2-1.21-1.61-1.21-3.07 0-1.46.76-2.18 1.03-2.48.27-.3.59-.38.79-.38h.57c.18 0 .43-.07.67.51.25.6.84 2.06.91 2.21.08.15.13.33.03.53-.1.2-.15.33-.3.5-.15.18-.32.4-.45.53-.15.15-.3.31-.13.61.18.3.78 1.29 1.68 2.09 1.15 1.03 2.13 1.35 2.43 1.5.3.15.48.13.65-.08.18-.2.75-.88.95-1.18.2-.3.4-.25.68-.15.28.1 1.76.83 2.06.98.3.15.5.23.58.35.08.13.08.73-.17 1.41Z"/></svg>
            <?php if ( ! $compact ) : ?><span>WhatsApp</span><?php endif; ?>
        </a>

        <!-- Facebook -->
        <a href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . $enc_url ); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr( $btn_cls ); ?> rn-share-fb" title="Bagikan ke Facebook" aria-label="Facebook">
            <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor" aria-hidden="true"><path d="M13.5 22v-8.2h2.75l.41-3.2H13.5V8.56c0-.93.26-1.56 1.59-1.56h1.7V4.14c-.3-.04-1.31-.14-2.49-.14-2.46 0-4.15 1.5-4.15 4.26v2.34H7.38v3.2h2.77V22h3.35Z"/></svg>
            <?php if ( ! $compact ) : ?><span>Facebook</span><?php endif; ?>
        </a>

        <!-- Instagram -->
        <a href="<?php echo esc_url( $ig_url ); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr( $btn_cls ); ?> rn-share-ig" title="Instagram RINEWS.ID" aria-label="Instagram">
            <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor" aria-hidden="true"><path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2Zm0 2A3.75 3.75 0 0 0 4 7.75v8.5A3.75 3.75 0 0 0 7.75 20h8.5A3.75 3.75 0 0 0 20 16.25v-8.5A3.75 3.75 0 0 0 16.25 4h-8.5Zm9.5 1.5a1.25 1.25 0 1 1 0 2.5 1.25 1.25 0 0 1 0-2.5ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z"/></svg>
            <?php if ( ! $compact ) : ?><span>Instagram</span><?php endif; ?>
        </a>

        <!-- Twitter / X -->
        <a href="<?php echo esc_url( 'https://twitter.com/intent/tweet?text=' . $enc_title . '&url=' . $enc_url ); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr( $btn_cls ); ?> rn-share-tw" title="Bagikan ke Twitter / X" aria-label="Twitter">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231 5.45-6.231Zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77Z"/></svg>
            <?php if ( ! $compact ) : ?><span>Twitter</span><?php endif; ?>
        </a>

        <!-- YouTube -->
        <a href="<?php echo esc_url( $yt_url ); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr( $btn_cls ); ?> rn-share-yt" title="Kanal YouTube RINEWS.ID" aria-label="YouTube">
            <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor" aria-hidden="true"><path d="M21.58 7.19a2.75 2.75 0 0 0-1.94-1.95C17.93 4.75 12 4.75 12 4.75s-5.93 0-7.64.49A2.75 2.75 0 0 0 2.42 7.2 28.8 28.8 0 0 0 2 12a28.8 28.8 0 0 0 .42 4.81 2.75 2.75 0 0 0 1.94 1.95c1.71.49 7.64.49 7.64.49s5.93 0 7.64-.49a2.75 2.75 0 0 0 1.94-1.95A28.8 28.8 0 0 0 22 12a28.8 28.8 0 0 0-.42-4.81ZM10 15.2V8.8l5.5 3.2-5.5 3.2Z"/></svg>
            <?php if ( ! $compact ) : ?><span>YouTube</span><?php endif; ?>
        </a>

        <!-- Copy Link -->
        <button type="button" class="<?php echo esc_attr( $btn_cls ); ?> rn-share-copy js-rn-copy-link" data-copy-url="<?php echo esc_url( $url ); ?>" title="Salin Tautan" aria-label="Copy Link">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
            <?php if ( ! $compact ) : ?><span class="js-rn-copy-label">Copy Link</span><?php endif; ?>
        </button>

        <!-- Email -->
        <a href="<?php echo esc_url( 'mailto:?subject=' . $enc_title . '&body=' . $enc_title . '%0A%0ABaca%20selengkapnya:%20' . $enc_url ); ?>" class="<?php echo esc_attr( $btn_cls ); ?> rn-share-mail" title="Bagikan via Email" aria-label="Email">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            <?php if ( ! $compact ) : ?><span>Email</span><?php endif; ?>
        </a>
    </div>
    <?php
}
