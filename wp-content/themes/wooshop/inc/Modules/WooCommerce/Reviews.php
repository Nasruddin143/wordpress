<?php
/**
 * WooShop Advanced Reviews
 *
 * Extends WooCommerce product reviews with:
 * - Review summary.
 * - Rating filters.
 * - AJAX review loading.
 * - Verified-purchase indicators.
 * - Review pagination.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WC_Product;
use WooShop\Core\AssetsManager;
use WooShop\Core\Container;
use WooShop\Core\Module;
use WP_Comment;

defined( 'ABSPATH' ) || exit;

/**
 * Reviews class.
 */
class Reviews extends Module {

    /**
     * Asset manager.
     *
     * @var AssetsManager
     */
    protected AssetsManager $assets;

    /**
     * Constructor.
     *
     * @param Container $container Service container.
     * @param AssetsManager             $assets    Asset manager.
     */
    public function __construct(
        Container     $container,
        AssetsManager $assets
    ) {
        parent::__construct( $container );

        $this->assets = $assets;
    }

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {

        add_action(
            'wp_enqueue_scripts',
            array( $this, 'enqueue_assets' ),
            30
        );

        add_action(
            'woocommerce_after_single_product_summary',
            array( $this, 'render_summary' ),
            11
        );

        add_filter(
            'woocommerce_review_before',
            array( $this, 'render_verified_badge' ),
            20,
            2
        );

        add_action(
            'wp_ajax_wooshop_filter_reviews',
            array( $this, 'filter_reviews' )
        );

        add_action(
            'wp_ajax_nopriv_wooshop_filter_reviews',
            array( $this, 'filter_reviews' )
        );
    }

    /**
     * Enqueue review assets.
     *
     * @return void
     */
    public function enqueue_assets(): void {

        if ( ! is_product() ) {
            return;
        }

        $this->assets->load_component(
            'reviews'
        );

        wp_localize_script(
            'wooshop-reviews',
            'WooShopReviews',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce(
                    'wooshop_reviews'
                ),
                'action'  => 'wooshop_filter_reviews',
                'loading' => __( 'Loading reviews…', 'wooshop' ),
                'error'   => __( 'Unable to load reviews.', 'wooshop' ),
            )
        );
    }

    /**
     * Render review summary.
     *
     * @return void
     */
    public function render_summary(): void {

        global $product;

        if ( ! $product instanceof WC_Product ) {
            return;
        }

        $product_id = $product->get_id();

        $count = (int) $product->get_review_count();

        $average = (float) $product->get_average_rating();

        $distribution = $this->get_rating_distribution(
            $product_id
        );

        ?>

        <section
            class="ws-review-summary"
            data-ws-review-summary
        >

            <div class="ws-review-summary__header">

                <h2 class="h4">
                    <?php esc_html_e( 'Customer reviews', 'wooshop' ); ?>
                </h2>

                <div class="ws-review-summary__rating">

                    <strong>
                        <?php echo esc_html(
                            number_format_i18n(
                                $average,
                                1
                            )
                        ); ?>
                    </strong>

                    <?php
                    echo wp_kses_post(
                        wc_get_rating_html(
                            $average,
                            $count
                        )
                    );
                    ?>

                    <span>
						<?php
                        printf(
                        /* translators: %d review count. */
                            esc_html(
                                _n(
                                    '%d review',
                                    '%d reviews',
                                    $count,
                                    'wooshop'
                                )
                            ),
                            $count
                        );
                        ?>
					</span>

                </div>

            </div>

            <div class="ws-review-summary__ratings">

                <?php for ( $rating = 5; $rating >= 1; $rating-- ) : ?>

                    <?php
                    $rating_count = $distribution[ $rating ] ?? 0;

                    $percentage = $count > 0
                        ? round(
                            ( $rating_count / $count ) * 100
                        )
                        : 0;
                    ?>

                    <button
                        type="button"
                        class="ws-review-rating-filter"
                        data-ws-review-rating="<?php echo esc_attr(
                            $rating
                        ); ?>"
                    >

						<span class="ws-review-rating-filter__label">
							<?php echo esc_html( $rating ); ?>
                            <?php esc_html_e( 'stars', 'wooshop' ); ?>
						</span>

                        <span
                            class="ws-review-rating-filter__bar"
                            aria-hidden="true"
                        >
							<span
                                style="width: <?php echo esc_attr(
                                    $percentage
                                ); ?>%;"
                            ></span>
						</span>

                        <span class="ws-review-rating-filter__count">
							<?php echo esc_html( $rating_count ); ?>
						</span>

                    </button>

                <?php endfor; ?>

            </div>

            <div
                class="ws-reviews-list"
                data-ws-reviews-list
                data-product-id="<?php echo esc_attr(
                    $product_id
                ); ?>"
            >

                <?php
                $this->render_reviews(
                    $product_id
                );
                ?>

            </div>

        </section>

        <?php
    }

    /**
     * Render reviews.
     *
     * @param int $product_id Product ID.
     * @param int $rating     Optional rating filter.
     * @param int $page       Review page.
     * @return void
     */
    protected function render_reviews(
        int $product_id,
        int $rating = 0,
        int $page = 1
    ): void {

        $args = array(
            'post_id' => $product_id,
            'status'  => 'approve',
            'type'    => 'review',
            'number'  => 10,
            'paged'   => $page,
        );

        if ( $rating >= 1 && $rating <= 5 ) {

            $args['meta_query'] = array(
                array(
                    'key'     => 'rating',
                    'value'   => $rating,
                    'compare' => '=',
                    'type'    => 'NUMERIC',
                ),
            );
        }

        $reviews = get_comments( $args );

        if ( empty( $reviews ) ) {

            ?>
            <div class="alert alert-light">
                <?php
                esc_html_e(
                    'No reviews found.',
                    'wooshop'
                );
                ?>
            </div>
            <?php

            return;
        }

        foreach ( $reviews as $review ) {

            $review_rating = (int) get_comment_meta(
                $review->comment_ID,
                'rating',
                true
            );

            $verified = wc_review_is_from_verified_owner(
                $review->comment_ID
            );

            ?>

            <article
                class="ws-review"
                data-ws-review
            >

                <header class="ws-review__header">

                    <div class="ws-review__author">

                        <strong>
                            <?php echo esc_html(
                                $review->comment_author
                            ); ?>
                        </strong>

                        <?php if ( $verified ) : ?>

                            <span class="ws-review__verified">
								<?php
                                esc_html_e(
                                    'Verified purchase',
                                    'wooshop'
                                );
                                ?>
							</span>

                        <?php endif; ?>

                    </div>

                    <time
                        class="ws-review__date"
                        datetime="<?php echo esc_attr(
                            get_comment_date(
                                'c',
                                $review
                            )
                        ); ?>"
                    >
                        <?php echo esc_html(
                            get_comment_date(
                                '',
                                $review
                            )
                        ); ?>
                    </time>

                </header>

                <?php if ( $review_rating ) : ?>

                    <div class="ws-review__rating">
                        <?php
                        echo wp_kses_post(
                            wc_get_rating_html(
                                $review_rating
                            )
                        );
                        ?>
                    </div>

                <?php endif; ?>

                <div class="ws-review__content">

                    <?php
                    echo wp_kses_post(
                        wpautop(
                            $review->comment_content
                        )
                    );
                    ?>

                </div>

            </article>

            <?php
        }
    }

    /**
     * Add verified purchase badge to native review output.
     *
     * @param int $comment_id Comment ID.
     * @param object|null $comment    Comment object.
     * @return void
     */
    public function render_verified_badge(
        int $comment_id,
        ?object $comment
    ): void {

        if ( ! $comment instanceof WP_Comment ) {
            return;
        }

        if (
            ! wc_review_is_from_verified_owner(
                $comment->comment_ID
            )
        ) {
            return;
        }

        ?>
        <span class="ws-review__verified">
			<?php esc_html_e( 'Verified purchase', 'wooshop' ); ?>
		</span>
        <?php
    }

    /**
     * Get rating distribution.
     *
     * @param int $product_id Product ID.
     * @return array
     */
    protected function get_rating_distribution(
        int $product_id
    ): array {

        $distribution = array(
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
        );

        $reviews = get_comments(
            array(
                'post_id' => $product_id,
                'status'  => 'approve',
                'type'    => 'review',
                'number'  => 0,
            )
        );

        foreach ( $reviews as $review ) {

            $rating = (int) get_comment_meta(
                $review->comment_ID,
                'rating',
                true
            );

            if ( isset( $distribution[ $rating ] ) ) {
                $distribution[ $rating ]++;
            }
        }

        return $distribution;
    }

    /**
     * AJAX review filtering.
     *
     * @return void
     */
    public function filter_reviews(): void {

        check_ajax_referer(
            'wooshop_reviews',
            'nonce'
        );

        $product_id = isset( $_POST['product_id'] )
            ? absint( $_POST['product_id'] )
            : 0;

        $rating = isset( $_POST['rating'] )
            ? absint( $_POST['rating'] )
            : 0;

        $page = isset( $_POST['page'] )
            ? max(
                1,
                absint( $_POST['page'] )
            )
            : 1;

        if ( ! $product_id ) {

            wp_send_json_error(
                array(
                    'message' => __(
                        'Invalid product.',
                        'wooshop'
                    ),
                ),
                400
            );
        }

        ob_start();

        $this->render_reviews(
            $product_id,
            $rating,
            $page
        );

        $html = ob_get_clean();

        wp_send_json_success(
            array(
                'html' => $html,
            )
        );
    }
}