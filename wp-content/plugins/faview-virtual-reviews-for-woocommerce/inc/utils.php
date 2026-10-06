<?php

namespace VirtualReviews\Inc;

defined( 'ABSPATH' ) || exit;

class Utils {
	public static function get_product_categories( $args = [] ) {
		add_filter( 'terms_clauses', [ __CLASS__, 'filter_language' ], 9 );

		$args       = wp_parse_args( $args, [ 'taxonomy' => 'product_cat', 'hide_empty' => false ] );
		$categories = get_categories( $args );

		remove_filter( 'terms_clauses', [ __CLASS__, 'filter_language' ], 9 );

		return self::build_dropdown_categories_tree( $categories );
	}

	public static function filter_language( $clauses ) {
		if ( function_exists( 'pll_default_language' ) ) {
			global $wpdb;

			$default          = pll_default_language( 'OBJECT' );
			$clauses['join']  .= " INNER JOIN $wpdb->term_relationships AS pll_tr ON pll_tr.object_id = t.term_id";
			$clauses['where'] .= ' AND pll_tr.term_taxonomy_id = ' . absint( $default->tl_term_taxonomy_id );
		}

		return $clauses;
	}

	private static function build_dropdown_categories_tree( $all_cats, $parent_cat = 0, $level = 1 ) {
		$res = [];
		foreach ( $all_cats as $cat ) {
			if ( $cat->parent == $parent_cat ) {
				$prefix               = str_repeat( '&nbsp;-&nbsp;', $level - 1 );
				$res[ $cat->term_id ] = $prefix . $cat->name . " ({$cat->count})";
				$child_cats           = self::build_dropdown_categories_tree( $all_cats, $cat->term_id, $level + 1 );
				if ( $child_cats ) {
					$res += $child_cats;
				}
			}
		}

		return $res;
	}

	public static function generate_time_array( $length, $from, $to ) {
		$map = [];
		for ( $i = 0; $i < $length; $i ++ ) {
			$t = rand( $from, $to );
			$h = date( 'H', $t );

			if ( $h >= 7 && $h <= 24 ) {
				$map[] = $t;
			} else {
				$x     = rand( 7 - $h, 24 - $h ) * HOUR_IN_SECONDS;
				$map[] = $t + $x;
			}
		}

		sort( $map );

		return $map;
	}

	public static function get_template( $template_name, $args = [] ) {
		wc_get_template( $template_name . '.php', $args, 'faview-virtual-reviews-for-woocommerce', WVR_CONST['templates_dir'] );
	}

	public static function get_sample_reviews() {
		return [
			5 => [
				"I rarely leave a comment, but this item is beyond worth it! Gotta let you guys know!",
				"Thank you guys for this amazing creation! Absolutely mind-blowing!",
				"Think everyone should know about this, it's just beyond my expectations",
				"Wished I had found this sooner, it took me a lot of money and time until I found my dream product here!",
				"An awesome product with great flexibility. The customer support is superb. I recommend this without any doubt.",
				"This is the coolest thing I've found on here! Will keep using your products in the future!",
				"The first time I got the urges to leave a comment, but this is simply a top-notch thing you can find.",
				"Been using a lot of items, this one is obviously the best",
				"Quite easy to use, nice design, surely will buy again",
				"Just love the design and the customer support is the nicest.",
				"I want to say thanks to the support team for helping with my continuously silly questions, you're the best!",
				"The item and the design are very cool. Also the support is amazing, they always help you with any detail that you have.",
				"Good response from the support.",
				"I think this is amazing. Lots of features and customizable from every point of view. The few times I asked for help in support they were competent, fast and above all very patient. Really recommend",
				"This is the best support for a product I had so far, they reply quickly and solve themselves the problems most of the time. Kudos guyz",
				"I received a personalized and attentive treatment. Thanks to this deal, I was able to find a solution to my problem in a short time. :)",
				"I like this item and also the customer service of them.",
				"It's far better than those similar products, while the price is still acceptable.",
				"Great item! It provides too many things compared to its price charged.",
				"I'm caught :) Find my love and will never buy in any other shop.",
				"Been using it for a while, I usually don't write a review but this time I am truly convinced to write.",
				"I got excellent support for this item. They were patient with me, and helped me solve my issue. I recommend this company 100%",
			],
			4 => [
				'Not the best thing, but worth the money',
				'Not bad, but the service does not meet my expectations.',
				'Got some issues, but it works for me.',
				'A promising product, worth trying',
				'4 stars for the product, work as described, but not as expected.',
				'Everybody skips this, but it\'s actually a good item, I\'ll remove 1 star for the customer support',
				'Think this could be a potential item in the future',
				'Hope to see your improvement over time',
				'Great item. Easy to use and really clean. Worked perfectly!',
				'Very flexible and well designed.',
				'By far the best item on the market, you will not be disappointed.',
			],
			3 => [
				'I wish there were more customization possible. The support is okay.',
				'Easy to handle',
				'Sadly, the item was broken, but they offer me a refund so I\'ll give a 3-star rating for the support',
				'The quality is average, focus too much on unnecessary things',
				'No big difference compared to other similar products',
				'If you take a look closely, there\'re quite some issues.',
				'Please think carefully when purchasing this item.',
				'I feel like these social media feeds are expensive for what they offer.',
			],
			2 => [
				'Totally different from how it was described!',
				'Nothing good like how it was advertised',
				'Not work as promised',
				'Where is the function you promise in your product description?',
				'This disappointed me so much.',
				'Is this seriously how you treat your customers?',
				'Exhausting finding out how to use it',
				'Too complicated to understand how it works',
				'The design is purely terrible.',
				'Buying this was my worst decision',
				'Good customer support, but you still declined my refund request regarding the item quality.',
				'Should I be impressed with how bad the item is?',
				'So much worse than I expected.',
				'It\'s hard to describe when this item just disappoints me in many ways.',
				'Would not recommend, wayyyyyyyyy over priced they should be paying you to test this.',
			],
			1 => [
				'It\'s purely bullshit.',
				'Can\'t use it',
				'Just wasting my money buying it and my time using it, totally useless',
				'Can anything be worse than this?',
				'Never come back again',
				'Will never ever have a next time with you guys',
				'Bad service, bad product, simply a "No"',
				'So many issues. Have purchased and never used again.',
				'Not recommended.',
				'A good-for-nothing product',
				'Just a burden, wasted my time',
				'I need to let other users know, don\'t use it!',
				'Worst thing you\'ve ever made',
				'Is this your idea of selling products?',
				'Is this the best products you have?',
				'This is a total waste of time and money, I advise everyone not to waste any moment with this.',
				'5-star for product quality, but 1-star for customer support',
				'No longer works. This happened a while back then they fixed but once again it broke',
			]
		];
	}
}