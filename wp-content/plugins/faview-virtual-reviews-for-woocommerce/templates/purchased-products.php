<?php
defined( 'ABSPATH' ) || exit;

if ( $string ) {
	?>
    <div class="wvr-comments-group">
        <i class="wvr-icon-purchased wvr-purchased-format"> </i>
		<?php echo wp_kses_post( $string ); ?>
    </div>
	<?php
}