<?php defined( 'ABSPATH' ) || exit; ?>
    <div class="wvr-wrapper">
        <h1><?php esc_html_e( 'Reviews', 'faview-virtual-reviews-for-woocommerce' ); ?></h1>
        <div id="wvr-review-from-setting" class="vi-ui segment form small">
            <h3><?php esc_html_e( 'Add multiple review', 'faview-virtual-reviews-for-woocommerce' ); ?></h3>

            <div class="field">
                <label><?php esc_html_e( 'Use random quantity in range', 'faview-virtual-reviews-for-woocommerce' ) ?></label>
                <div class="vi-ui toggle checkbox">
                    <input type="checkbox" class="wvr-use-quantity-range" <?php checked( $use_quantity_range, 1 ) ?>>
                    <label><?php esc_html_e( 'Max is 50 reviews per product', 'faview-virtual-reviews-for-woocommerce' ) ?></label>
                </div>
            </div>

            <div class="field wvr-fixed-quantity-field" style="display: <?php echo $use_quantity_range ? 'none' : 'block'; ?>">
                <label><?php esc_html_e( 'Quantity of reviews per each product', 'faview-virtual-reviews-for-woocommerce' ) ?></label>
                <input type="number" class="wvr-review-per-product" value="1" min="1">
            </div>

            <div class="two fields wvr-random-quantity-field" style="display: <?php echo $use_quantity_range ? 'flex' : 'none'; ?>">
                <div class="field">
                    <label><?php esc_html_e( 'Quantity from', 'faview-virtual-reviews-for-woocommerce' ) ?></label>
                    <input type="number" class="wvr-review-per-product-from" value="1" min="1">
                </div>
                <div class="field">
                    <label><?php esc_html_e( 'Quantity to', 'faview-virtual-reviews-for-woocommerce' ) ?></label>
                    <input type="number" class="wvr-review-per-product-to" value="50" min="1">
                </div>
            </div>

            <div class="two fields">
                <div class="field">
                    <label><?php esc_html_e( 'From', 'faview-virtual-reviews-for-woocommerce' ) ?></label>
                    <div class="vi-ui calendar" id="wvr-date-start">
                        <div class="vi-ui input left icon">
                            <i class="calendar icon"></i>
                            <input type="text" class="wvr-date-from" value="<?php echo esc_attr( $current_time ) ?>">
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label><?php esc_html_e( 'To', 'faview-virtual-reviews-for-woocommerce' ) ?></label>
                    <div class="vi-ui calendar" id="wvr-date-end">
                        <div class="vi-ui input left icon">
                            <i class="calendar icon"></i>
                            <input type="text" class="wvr-date-to" value="<?php echo esc_attr( $current_time ) ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="field">
                <label><?php esc_html_e( 'Include categories', 'faview-virtual-reviews-for-woocommerce' ) ?></label>
                <select class="vi-ui dropdown search selection wvr-include-product-cat" multiple>
					<?php
					if ( ! empty( $categories ) ) {
						foreach ( $categories as $cat_id => $cat_name ) {
							printf( '<option value="%s" >%s</option>', esc_attr( $cat_id ), esc_html( $cat_name ) );
						}
					}
					?>
                </select>
            </div>
            <div class="field">
                <label><?php esc_html_e( 'Exclude categories', 'faview-virtual-reviews-for-woocommerce' ) ?></label>
                <select class="vi-ui dropdown search selection wvr-exclude-product-cat" multiple>
					<?php
					if ( ! empty( $categories ) ) {
						foreach ( $categories as $cat_id => $cat_name ) {
							printf( '<option value="%s" >%s</option>', esc_attr( $cat_id ), esc_html( $cat_name ) );
						}
					}
					?>
                </select>
            </div>
            <div class="field">
                <label><?php esc_html_e( 'Include products', 'faview-virtual-reviews-for-woocommerce' ) ?></label>
                <select class="wvr-include-products wvr-products-search" placeholder="<?php esc_html_e( 'Search products', 'faview-virtual-reviews-for-woocommerce' ); ?>"> </select>
            </div>
            <div class="field">
                <label><?php esc_html_e( 'Exclude products', 'faview-virtual-reviews-for-woocommerce' ) ?></label>
                <select class="wvr-exclude-products wvr-products-search" placeholder="<?php esc_html_e( 'Search products', 'faview-virtual-reviews-for-woocommerce' ); ?>"> </select>
            </div>
            <button type="button" class="vi-ui button small wvr-add-multi-reviews">
				<?php esc_html_e( 'Add reviews', 'faview-virtual-reviews-for-woocommerce' ); ?>
            </button>

            <div class="vi-ui teal progress" data-percent="0" id="wvr-processing-bar">
                <div class="bar">
                    <div class="progress"></div>
                </div>
            </div>

            <div class="wvr-error-product-list-wrapper">
                <div class="vi-ui orange message">
                    <div class="header">
						<?php esc_html_e( 'Error:', 'faview-virtual-reviews-for-woocommerce' ); ?>
                    </div>
                    <ul class="wvr-error-products">

                    </ul>
                </div>
            </div>

        </div>

        <div id="wvr-custom-review" class="vi-ui segment form small">
            <h3><?php esc_html_e( 'Add single review', 'faview-virtual-reviews-for-woocommerce' ); ?></h3>

            <div class="field">
                <label><?php esc_html_e( 'Date', 'faview-virtual-reviews-for-woocommerce' ) ?></label>
                <div class="vi-ui calendar" id="wvr-single-picker-time">
                    <div class="vi-ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" class="wvr-time" value="<?php echo esc_attr( $current_time . 'T' . date( 'H:i', $timestamp ) ) ?>">
                    </div>
                </div>
            </div>

            <div class="field">
                <label><?php esc_html_e( 'Rating', 'faview-virtual-reviews-for-woocommerce' ) ?></label>
                <select class="wvr-rating">
					<?php
					for ( $i = 1; $i <= 5; $i ++ ) {
						printf( '<option value="%d" %s>%d</option>', esc_attr( $i ), esc_attr( $i == 5 ? 'selected' : '' ), esc_attr( $i ) );
					}
					?>
                </select>
            </div>
            <div class="field">
                <label><?php esc_html_e( 'Review', 'faview-virtual-reviews-for-woocommerce' ) ?></label>
                <textarea class="wvr-review" rows="3"></textarea>
            </div>
            <div class="field">
                <label><?php esc_html_e( 'Author', 'faview-virtual-reviews-for-woocommerce' ) ?></label>
                <input class="wvr-author">
            </div>
            <div class="field">
                <label><?php esc_html_e( 'Products', 'faview-virtual-reviews-for-woocommerce' ) ?></label>
                <select class="wvr-products wvr-products-search" placeholder="<?php esc_html_e( 'Search product to add review', 'faview-virtual-reviews-for-woocommerce' ); ?>"> </select>
            </div>
            <button type="button" class="vi-ui button small wvr-add-review">
				<?php esc_html_e( 'Add review', 'faview-virtual-reviews-for-woocommerce' ); ?>
            </button>

        </div>
    </div>
<?php
