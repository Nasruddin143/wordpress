<?php

defined('ABSPATH') || exit;

/* =========================================================
 * PRIMARY TERM
 * ========================================================= */

function kt_get_primary_term($post_id, $post_type = null)
{
	$post_id = (int) $post_id;
	if (!$post_id)
		return null;

	$cache_key = "primary_term_$post_id";
	$cached = KT_Cache::get($cache_key, 'terms');

	if ($cached !== false)
		return $cached;

	$post_type = $post_type ?: get_post_type($post_id);

	static $map = [
		'post' => 'category',
		'sewing_machine' => 'sewing_machine_category',
		'air_cooler' => 'air_cooler_category',
		'service' => 'service_category',
		'accessory' => 'accessory_category',
	];

	if (empty($map[$post_type])) {
		KT_Cache::set($cache_key, null, 'terms');
		return null;
	}

	$terms = get_the_terms($post_id, $map[$post_type]);

	if (empty($terms) || is_wp_error($terms)) {
		KT_Cache::set($cache_key, null, 'terms');
		return null;
	}

	// Prefer parent term
	foreach ($terms as $term) {
		if ((int) $term->parent === 0) {
			KT_Cache::set($cache_key, $term, 'terms');
			return $term;
		}
	}

	$term = reset($terms);
	KT_Cache::set($cache_key, $term, 'terms');
	return $term;
}


/* =========================================================
 * POST TAGS (Supports Default + CPT)
 * ========================================================= */

function kt_get_post_tags($post_id, $post_type = null)
{
	$post_id = (int) $post_id;
	if (!$post_id)
		return [];

	$cache_key = "post_tags_$post_id";
	$cached = KT_Cache::get($cache_key, 'terms');

	if ($cached !== false)
		return $cached;

	$post_type = $post_type ?: get_post_type($post_id);

	static $map = [
		'post' => 'post_tag',
		'sewing_machine' => 'sewing_machine_tag',
		'air_cooler' => 'air_cooler_tag',
		'service' => 'service_tag',
		'accessory' => 'accessory_tag',
	];

	if (empty($map[$post_type])) {
		KT_Cache::set($cache_key, [], 'terms');
		return [];
	}

	$terms = get_the_terms($post_id, $map[$post_type]);

	if (empty($terms) || is_wp_error($terms)) {
		KT_Cache::set($cache_key, [], 'terms');
		return [];
	}

	KT_Cache::set($cache_key, $terms, 'terms');
	return $terms;
}


/* =========================================================
 * CLEAR CACHE ON SAVE
 * ========================================================= */

add_action('save_post', function ($post_id) {

	if (wp_is_post_revision($post_id))
		return;

	KT_Cache::delete("post_tags_$post_id", 'terms');
	KT_Cache::delete("primary_term_$post_id", 'terms');
});


/**
 * -----------------------------------------------------------------------------
 * SOCIAL SHARE BUTTONS
 * -----------------------------------------------------------------------------
 */
function kt_social_share_buttons()
{
	$share_url   = urlencode(home_url(add_query_arg([], wp_unslash($_SERVER['REQUEST_URI']))));
	$share_title = urlencode(wp_get_document_title());

	// Social platforms config
	$socials = [
		[
			'name'  => __('WhatsApp'),
			'icon'  => 'bi-whatsapp',
			'url'   => "https://wa.me/?text={$share_url}"
		],
		[
			'name'  => __('Facebook'),
			'icon'  => 'bi-facebook',
			'url'   => "https://www.facebook.com/sharer/sharer.php?u={$share_url}"
		],
		[
			'name'  => __('X (Twitter)'),
			'icon'  => 'bi-twitter-x',
			'url'   => "https://twitter.com/intent/tweet?text={$share_title}&url={$share_url}"
		],
		[
			'name'  => __('LinkedIn'),
			'icon'  => 'bi-linkedin',
			'url'   => "https://www.linkedin.com/shareArticle?mini=true&url={$share_url}&title={$share_title}"
		]
	];

	ob_start();
?>
	<nav class="post-share d-flex flex-wrap align-items-center gap-2" role="navigation"
		aria-label="<?php echo esc_attr('Social sharing options'); ?>">

		<span class="me-2 d-none d-sm-inline">
			<?php echo esc_html__('Share:'); ?>
		</span>

		<?php foreach ($socials as $social): ?>
			<a href="<?php echo esc_url($social['url']); ?>" title="<?php echo esc_attr(sprintf(__('Share on %s'), $social['name'])); ?>"
				target="_blank"
				rel="noopener nofollow"
				class="link-offset-2 link-underline link-underline-opacity-0 link-dark"
				aria-label="<?php echo esc_attr(sprintf(__('Share on %s'), $social['name'])); ?>">

				<i class="bi <?php echo esc_attr($social['icon']); ?> fs-5" aria-hidden="true"></i>
			</a>
		<?php endforeach; ?>

		<button type="button"
			onclick="window.print();"
			class="btn btn-link link-dark p-0 border-0"
			aria-label="<?php echo esc_attr(__('Print this page')); ?>"
			title="<?php echo esc_attr('Print this page'); ?>">

			<i class="bi bi-printer fs-5" aria-hidden="true"></i>
		</button>

	</nav>
<?php
	return ob_get_clean();
}

/**
 * -----------------------------------------------------------------------------
 * ACF Product Price (Reusable for all CPTs)
 * -----------------------------------------------------------------------------
 */
function kt_acf_product_price_html($post_id = null, $args = [])
{
	$post_id = $post_id ?: get_the_ID();

	if (!function_exists('get_field')) {
		return '';
	}

	$defaults = [
		'show_discount' => true,
		'show_diff'     => true,
		'currency'      => '₹',
	];

	$args = wp_parse_args($args, $defaults);

	$actual = (float) get_field('actual_price', $post_id);
	$final  = (float) get_field('final_price', $post_id);

	if (!$final) {
		return '';
	}

	ob_start();
	?>

	<div class="kt-product-price">

		<span class="kt-price-final text-danger fw-bold me-1">
			<?php echo esc_html($args['currency']) . number_format($final, 0); ?>
		</span>

		<?php if ($args['show_discount'] && $actual && $actual > $final):
			$discount = round((($actual - $final) / $actual) * 100);
		?>
			<span class="kt-price-actual fw-bold text-secondary text-decoration-line-through">
				<?php echo esc_html($args['currency']) . number_format($actual, 0); ?>
			</span>

			<span class="kt-discount-badge text-success fw-bold ms-2">
				<?php echo esc_html($discount . '% OFF'); ?>
			</span>
		<?php endif; ?>

		<?php if ($args['show_diff'] && $actual && $actual > $final): ?>
			<span class="kt-price-save fw-bold text-secondary">
				<?php
				echo sprintf(
					esc_html('| You save %s'),
					$args['currency'] . number_format($actual - $final, 0)
				);
				?>
			</span>
		<?php endif; ?>

	</div>

	<?php
	return ob_get_clean();
}


/**
 * -----------------------------------------------------------------------------
 * ACF Discount Percentage (Reusable for all CPTs)
 * -----------------------------------------------------------------------------
 */
function kt_get_product_discount_percent($post_id = null)
{
	$post_id = $post_id ?: get_the_ID();

	if (!function_exists('get_field')) {
		return 0;
	}

	$actual = (float) get_field('actual_price', $post_id);
	$final = (float) get_field('final_price', $post_id);

	if ($actual > 0 && $final > 0 && $actual > $final) {
		return round((($actual - $final) / $actual) * 100);
	}

	return 0;
}

/**
 * -----------------------------------------------------------------------------
 * SITE REVIEW (TAGS)
 * -----------------------------------------------------------------------------
 */
function kt_trending_badges($post_id = null)
{
	if (!$post_id) {
		$post_id = get_the_ID();
	}

	$rating = (float) get_post_meta($post_id, '_kt_avg_rating', true);
	$count  = (int) get_post_meta($post_id, '_kt_review_count', true);
	$views  = (int) get_post_meta($post_id, '_kt_views', true);
	$date   = get_the_date('U', $post_id);

	$badges = '';

	// Top Rated
	if ($rating >= 4.5 && $count >= 5) {
		$badges .= '<span class="badge bg-success-subtle text-success-emphasis rounded-pill top-rated">Top Rated</span> ';
	}

	// Best Seller
	if (get_post_meta($post_id, '_kt_best_seller', true)) {
		$badges .= '<span class="badge bg-danger-subtle text-danger-emphasis rounded-pill best-seller">Best Seller</span> ';
	}

	// Trending
	if ($views > 50 && $rating >= 4) {
		$badges .= '<span class="badge bg-warning-subtle text-warning-emphasis rounded-pill trending">Trending</span> ';
	}

	// New Arrival
	if (time() - $date < 7 * 86400) {
		$badges .= '<span class="badge bg-info-subtle text-info-emphasis rounded-pill new">New</span> ';
	}

	return $badges;
}

/**
 * -----------------------------------------------------------------------------
 * CUSTOM LOGO CLASS (BOOTSTRAP)
 * -----------------------------------------------------------------------------
 */
add_filter('get_custom_logo', function ($html) {
	return str_replace('custom-logo-link', 'navbar-brand d-block', $html);
});

/* -------------------------------------------------
   NavXT: Home Breadcrumb URL (Default WP)
-------------------------------------------------- */
add_filter('bcn_breadcrumb_url', 'kt_breadcrumb_home_url', 10, 3);
function kt_breadcrumb_home_url($url, $type, $id)
{
	if (is_array($type) && in_array('home', $type, true)) {
		return home_url('/');
	}

	return $url;
}

/**
 * Move jQuery to the footer.
 */
function move_jquery_to_footer($wp_scripts)
{
	// Don't do anything in the admin area.
	if (is_admin()) {
		return;
	}

	// Target the core jQuery handles.
	$wp_scripts->add_data('jquery', 'group', 1);
	$wp_scripts->add_data('jquery-core', 'group', 1);
	$wp_scripts->add_data('jquery-migrate', 'group', 1);
}
add_action('wp_default_scripts', 'move_jquery_to_footer');


/* -------------------------------------------------
   NavXT: Fix Duplicate Search Breadcrumb
-------------------------------------------------- */
add_filter('bcn_breadcrumb_title', 'kt_fix_navxt_search_duplicate', 10, 3);
function kt_fix_navxt_search_duplicate($title, $type, $id)
{
	if (!is_array($type)) {
		return $title;
	}

	if (in_array('search', $type, true)) {

		$query = preg_replace('/^Search results for:\s*/i', '', $title);

		return sprintf(
			__('Search Results for: %s', 'kt'),
			'<span class="text-dark">' . esc_html($query) . '</span>'
		);
	}

	return $title;
}


/* -------------------------------------------------
   Removed Archive Title: Prefix Category, Tag etc.
-------------------------------------------------- */
add_filter('get_the_archive_title', function ($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif (is_tax()) { //for custom post types
        $title = sprintf(__('%1$s'), single_term_title('', false));
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    }
    return $title;
});


/* -------------------------------------------------
   Rank Math: OpeningHours JSON-LD
-------------------------------------------------- */
add_filter( 'rank_math/json_ld', function( $data, $jsonld ) {

    // Find the LocalBusiness / Organization node and inject openingHoursSpecification
    foreach ( $data as $key => $entity ) {
        if ( isset( $entity['@type'] ) && in_array( 'MensClothingStore', (array) $entity['@type'] ) ) {

            // Remove the old openingHours string
            unset( $data[ $key ]['openingHours'] );

            // Add structured openingHoursSpecification
            $data[ $key ]['openingHoursSpecification'] = [
                [
                    '@type'      => 'OpeningHoursSpecification',
                    'dayOfWeek'  => [
                        'https://schema.org/Monday',
                        'https://schema.org/Tuesday',
                        'https://schema.org/Thursday',
                        'https://schema.org/Friday',
                        'https://schema.org/Saturday',
                        'https://schema.org/Sunday',
                    ],
                    'opens'  => '09:00',
                    'closes' => '21:00',
                ],
            ];
        }
    }

    return $data;

}, 99, 2 );