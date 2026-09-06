
<?php

$phone = get_theme_mod( 'wooshop_header_phone' );
$email = get_theme_mod( 'wooshop_header_email' );

?>

<div class="container-fluid container-xl">
    <div class="row justify-content-between">

        <div class="col-6 text-start">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" class="main-grid-item-icon me-2" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
            </svg>

            <span><?php esc_html_e('Need help? Call us:', 'wooshop'); ?></span>
            <?php if ( ! empty( $phone ) ) : ?>
                <a class="link-offset-1 link-offset-1-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="tel: <?php echo esc_attr( $phone ); ?>">
                    <?php echo esc_html( $phone ); ?>
                </a>
            <?php endif; ?>

        </div>


        <div class="col-6 text-end">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" class="main-grid-item-icon me-2" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                <polyline points="22,6 12,13 2,6" />
            </svg>

            <?php if ( ! empty( $email ) ) : ?>
                <a class="link-offset-1 link-offset-1-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="mailto: <?php echo esc_attr( $email ); ?>">
                    <?php echo esc_html( $email ); ?>
                </a>
            <?php endif; ?>

        </div>

    </div>
</div>