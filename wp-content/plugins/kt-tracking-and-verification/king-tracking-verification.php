<?php
/*
 * Plugin Name: Tracking & Verification
 * Description: GA4, Google Tag Manager, Facebook Pixel, Domain Verification & IndexNow in one plugin.
 * Version: 1.2.0
 * Author: Nasruddin Shaikh
 */

if (!defined('ABSPATH'))
    exit;

/* =========================================================
 * REGISTER SETTINGS
 * ======================================================= */
function kt_register_settings()
{
    // Google Analytics
    register_setting('kt_settings', 'kt_ga_enabled', ['sanitize_callback' => 'absint']);
    register_setting('kt_settings', 'kt_ga_id', ['sanitize_callback' => 'sanitize_text_field']);

    // Google Tag Manager
    register_setting('kt_settings', 'kt_gtm_enabled', ['sanitize_callback' => 'absint']);
    register_setting('kt_settings', 'kt_gtm_id', ['sanitize_callback' => 'sanitize_text_field']);

    // Facebook Pixel
    register_setting('kt_settings', 'kt_fb_pixel_enabled', ['sanitize_callback' => 'absint']);
    register_setting('kt_settings', 'kt_fb_pixel_id', ['sanitize_callback' => 'sanitize_text_field']);

    // Domain Verification
    register_setting('kt_settings', 'kt_google_verification', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('kt_settings', 'kt_facebook_verification', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('kt_settings', 'kt_bing_verification', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('kt_settings', 'kt_yandex_verification', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('kt_settings', 'kt_pinterest_verification', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('kt_settings', 'kt_ahref_verification', ['sanitize_callback' => 'sanitize_text_field']);

    // IndexNow
    register_setting('kt_settings', 'kt_indexnow_key', ['sanitize_callback' => 'sanitize_key']);
}
add_action('admin_init', 'kt_register_settings');

/* =========================================================
 * ADMIN MENU
 * ======================================================= */
add_action('admin_menu', function () {
    add_options_page(
        'Tracking & Verification',
        'Tracking & Verification',
        'manage_options',
        'kt-tracking',
        'kt_settings_page'
    );
});

/* =========================================================
 * SETTINGS PAGE
 * ======================================================= */
function kt_settings_page()
{ ?>
    <div class="wrap">
        <h1>Tracking & Verification</h1>

        <form method="post" action="options.php">
            <?php settings_fields('kt_settings'); ?>
            <?php do_settings_sections('kt_settings'); ?>

            <h2>Google Analytics (GA4)</h2>
            <table class="form-table">
                <tr>
                    <th>Enable GA</th>
                    <td><input type="checkbox" name="kt_ga_enabled" value="1" <?php checked(1, get_option('kt_ga_enabled')); ?>></td>
                </tr>
                <tr>
                    <th>Measurement ID</th>
                    <td><input type="text" name="kt_ga_id" value="<?php echo esc_attr(get_option('kt_ga_id')); ?>"
                            placeholder="G-XXXXXXXXXX"></td>
                </tr>
            </table>

            <h2>Google Tag Manager</h2>
            <table class="form-table">
                <tr>
                    <th>Enable GTM</th>
                    <td><input type="checkbox" name="kt_gtm_enabled" value="1" <?php checked(1, get_option('kt_gtm_enabled')); ?>></td>
                </tr>
                <tr>
                    <th>Container ID</th>
                    <td><input type="text" name="kt_gtm_id" value="<?php echo esc_attr(get_option('kt_gtm_id')); ?>"
                            placeholder="GTM-XXXXXXX"></td>
                </tr>
            </table>

            <h2>Facebook Pixel</h2>
            <table class="form-table">
                <tr>
                    <th>Enable Pixel</th>
                    <td><input type="checkbox" name="kt_fb_pixel_enabled" value="1" <?php checked(1, get_option('kt_fb_pixel_enabled')); ?>></td>
                </tr>
                <tr>
                    <th>Pixel ID</th>
                    <td><input type="text" name="kt_fb_pixel_id"
                            value="<?php echo esc_attr(get_option('kt_fb_pixel_id')); ?>"></td>
                </tr>
            </table>

            <h2>Domain Verification</h2>
            <table class="form-table">
                <tr>
                    <th>Google Search Console</th>
                    <td><input type="text" name="kt_google_verification"
                            value="<?php echo esc_attr(get_option('kt_google_verification')); ?>"></td>
                </tr>
                <tr>
                    <th>Facebook</th>
                    <td><input type="text" name="kt_facebook_verification"
                            value="<?php echo esc_attr(get_option('kt_facebook_verification')); ?>"></td>
                </tr>
                <tr>
                    <th>Bing</th>
                    <td><input type="text" name="kt_bing_verification"
                            value="<?php echo esc_attr(get_option('kt_bing_verification')); ?>"></td>
                </tr>
                <tr>
                    <th>Yandex</th>
                    <td><input type="text" name="kt_yandex_verification"
                            value="<?php echo esc_attr(get_option('kt_yandex_verification')); ?>"></td>
                </tr>
                <tr>
                    <th>Pinterest</th>
                    <td><input type="text" name="kt_pinterest_verification"
                            value="<?php echo esc_attr(get_option('kt_pinterest_verification')); ?>"></td>
                </tr>
                <tr>
                    <th>Ahrefs</th>
                    <td><input type="text" name="kt_ahref_verification"
                            value="<?php echo esc_attr(get_option('kt_ahref_verification')); ?>"></td>
                </tr>
            </table>

            <h2>IndexNow</h2>
            <table class="form-table">
                <tr>
                    <th>IndexNow Key</th>
                    <td><input type="text" name="kt_indexnow_key"
                            value="<?php echo esc_attr(get_option('kt_indexnow_key')); ?>"></td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
<?php }

/* =========================================================
 * GOOGLE TAG MANAGER (CORRECT PLACEMENT)
 * ======================================================= */

/* GTM SCRIPT → <head> */
add_action('wp_head', function () {

    if (!get_option('kt_gtm_enabled'))
        return;
    $id = king_clean_gtm_id(get_option('kt_gtm_id'));
    if (!$id)
        return;
    ?>
    <!-- Google Tag Manager -->
    <script>
        (function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
            var f = d.getElementsByTagName(s)[0], j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', '<?php echo esc_js($id); ?>');
    </script>
    <!-- End Google Tag Manager -->
<?php }, 0);

/* GTM NOSCRIPT → after <body> */
function king_output_gtm_body()
{
    if (!get_option('kt_gtm_enabled'))
        return;

    $gtm_id = king_clean_gtm_id(get_option('kt_gtm_id'));
    if (!$gtm_id)
        return; ?>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr($gtm_id); ?>" height="0"
            width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <?php
}
add_action('wp_body_open', 'king_output_gtm_body', 1);


/* =========================================================
 * GOOGLE ANALYTICS (disabled if GTM enabled)
 * ======================================================= */
add_action('wp_body_open', function () {

    if (get_option('kt_gtm_enabled'))
        return;
    if (!get_option('kt_ga_enabled'))
        return;

    $id = trim(get_option('kt_ga_id'));
    if (!$id)
        return;
    ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($id); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', '<?php echo esc_js($id); ?>', { anonymize_ip: true });
    </script>
<?php }, 5);

/* =========================================================
 * FACEBOOK PIXEL
 * ======================================================= */
add_action('wp_head', function () {

    if (!get_option('kt_fb_pixel_enabled'))
        return;
    $id = trim(get_option('kt_fb_pixel_id'));
    if (!$id)
        return;
    ?>
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return; n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0';
            n.queue = []; t = b.createElement(e); t.async = !0;
            t.src = v; s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '<?php echo esc_js($id); ?>');
        fbq('track', 'PageView');
    </script>

    <noscript>
        <img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=<?php echo esc_attr($id); ?>&ev=PageView&noscript=1" />
    </noscript>
<?php }, 20);

/* =========================================================
 * DOMAIN VERIFICATION META TAGS
 * ======================================================= */
add_action('wp_head', function () {

    $tags = [
        'google-site-verification' => get_option('kt_google_verification'),
        'facebook-domain-verification' => get_option('kt_facebook_verification'),
        'msvalidate.01' => get_option('kt_bing_verification'),
        'yandex-verification' => get_option('kt_yandex_verification'),
        'p:domain_verify' => get_option('kt_pinterest_verification'),
        'ahrefs-site-verification' => get_option('kt_ahref_verification'), //58dfc5d1dbff8e5ac05d6766f6de9c3c7ffd5d6d6d903515d3f8a16cbfec777f
    ];

    foreach ($tags as $name => $value) {
        if ($value) {
            echo '<meta name="' . esc_attr($name) . '" content="' . esc_attr($value) . '">' . "\n";
        }
    }
}, 1);

/* =========================================================
 * INDEXNOW AUTO KEY FILE
 * ======================================================= */
add_action('update_option_kt_indexnow_key', function ($old, $new) {
    $new = sanitize_key($new);

    if (!$new)
        return;
    $root = ABSPATH;

    if ($old && file_exists($root . $old . '.txt')) {
        @unlink($root . $old . '.txt');
    }

    $file = $root . $new . '.txt';
    if (!file_exists($file)) {
        if (is_writable($root)) {
            file_put_contents($file, $new);
        }
        if (file_exists($file)) {
            @chmod($file, 0644);
        }
    }
}, 10, 2);

function king_clean_gtm_id($gtm_id)
{
    $gtm_id = trim($gtm_id);
    $gtm_id = str_replace(['"', "'"], '', $gtm_id);
    return preg_match('/^GTM-[A-Z0-9]+$/', $gtm_id) ? $gtm_id : '';
}


/* =========================================================
 * INDEXNOW AUTO SUBMIT (PING)
 * ======================================================= */
add_action('transition_post_status', 'kt_indexnow_ping_all', 10, 3);

function kt_indexnow_ping_all($new_status, $old_status, $post) {

    // Only first publish
    if ($new_status !== 'publish' || $old_status === 'publish') return;

    if (wp_is_post_revision($post->ID)) return;

    $key = get_option('kt_indexnow_key');
    if (!$key) return;

    $url = get_permalink($post->ID);
    if (!$url) return;

    $url = urlencode($url);

    wp_remote_get("https://api.indexnow.org/indexnow?url=$url&key=$key", [
        'timeout' => 5,
        'blocking' => false
    ]);
}

add_action('before_delete_post', 'kt_store_deleted_url');
add_action('deleted_post', 'kt_indexnow_delete');

function kt_store_deleted_url($post_id) {
    $url = get_permalink($post_id);
    if ($url) {
        update_post_meta($post_id, '_kt_deleted_url', $url);
    }
}

function kt_indexnow_delete($post_id)
{
    $key = get_option('kt_indexnow_key');
    if (!$key) return;

    $url = get_post_meta($post_id, '_kt_deleted_url', true);
    if (!$url) return;

    $url = urlencode($url);

    wp_remote_get("https://api.indexnow.org/indexnow?url=$url&key=$key", [
        'timeout' => 5,
        'blocking' => false
    ]);
}


/* =========================================================
 * CLEAN DB AFTER PLUGIN UNINSTALLED
 * ======================================================= */
register_uninstall_hook(__FILE__, 'kt_uninstall');

function kt_uninstall()
{
    delete_option('kt_ga_enabled');
    delete_option('kt_ga_id');
    delete_option('kt_gtm_enabled');
    delete_option('kt_gtm_id');
    delete_option('kt_fb_pixel_enabled');
    delete_option('kt_fb_pixel_id');
    delete_option('kt_google_verification');
    delete_option('kt_bing_verification');
    delete_option('kt_indexnow_key');
    delete_option('kt_facebook_verification');
    delete_option('kt_yandex_verification');
    delete_option('kt_pinterest_verification');
    delete_option('kt_ahref_verification');
}