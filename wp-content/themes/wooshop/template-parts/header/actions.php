
<div class="d-flex align-items-center gap-3">

    <?php do_action( 'wooshop_header_actions' ); ?>

    <a
            class="text-decoration-none"
            href="<?php echo esc_url( home_url( '/wishlist/' ) ); ?>"
            aria-label="<?php esc_attr_e( 'Wishlist', 'wooshop' ); ?>"
    >
        <i class="bi bi-heart" aria-hidden="true"></i>
        <span class="d-none d-lg-inline">
			<?php esc_html_e( 'Wishlist', 'wooshop' ); ?>
		</span>
    </a>

    <a
            class="text-decoration-none"
            href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url() ); ?>"
            aria-label="<?php esc_attr_e( 'My Account', 'wooshop' ); ?>"
    >
        <i class="bi bi-person" aria-hidden="true"></i>
        <span class="d-none d-lg-inline">
			<?php esc_html_e( 'My Account', 'wooshop' ); ?>
		</span>
    </a>

</div>