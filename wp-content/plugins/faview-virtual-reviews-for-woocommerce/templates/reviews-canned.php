<?php
defined( 'ABSPATH' ) || exit;

if ( $canned_style_desktop == 'slide' ) {
	?>
    <div class="wvr-customer-sample-cmt wvr-desktop-style">
        <div style="display: flex">
            <div class="wvr-customer-pick">
				<?php
				foreach ( $sample_cmts as $sample_cmt ) {
					printf( "<span class='wvr-select-sample-cmt'>%s</span>", esc_html( stripslashes( $sample_cmt ) ) );
				}
				?>
            </div>
        </div>
    </div>
	<?php
} elseif ( $canned_style_desktop == 'select' ) {
	?>
    <div class="wvr-customer-sample-cmt wvr-desktop-style">
        <div>
            <select class="wvr-customer-select">
                <option value="">
					<?php esc_html_e( "Sample comments", "woo-virtual-reviews" ) ?>
                </option>
				<?php
				foreach ( $sample_cmts as $sample_cmt ) {
					printf( "<option>%s</option>", esc_html( stripslashes( $sample_cmt ) ) );
				}
				?>
            </select>
        </div>
    </div>
	<?php
}

if ( $canned_style_mobile == 'slide' ) {
	?>
    <div class="wvr-customer-sample-cmt wvr-mobile-style">
        <div style="display: flex">
            <div class="wvr-customer-pick">
				<?php
				foreach ( $sample_cmts as $sample_cmt ) {
					printf( "<span class='wvr-select-sample-cmt'>%s</span>", esc_html( stripslashes( $sample_cmt ) ) );
				}
				?>
            </div>
        </div>
    </div>
	<?php
} elseif ( $canned_style_mobile == 'select' ) {
	?>
    <div class="wvr-customer-sample-cmt wvr-mobile-style">
        <div>
            <select class="wvr-customer-select">
                <option value="">
					<?php esc_html_e( "Sample comments", "woo-virtual-reviews" ) ?>
                </option>
				<?php
				foreach ( $sample_cmts as $sample_cmt ) {
					printf( "<option>%s</option>", esc_html( stripslashes( $sample_cmt ) ) );
				}
				?>
            </select>
        </div>
    </div>
	<?php
}
