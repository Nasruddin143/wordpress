
<?php

$phone = get_theme_mod( 'wooshop_header_phone' );
$email = get_theme_mod( 'wooshop_header_email' );

?>

<div class="container-fluid container-xl">
    <div class="row justify-content-between">

        <div class="col-6 text-start">
            <span><?php esc_html_e('Need help? Call us:', 'wooshop'); ?></span>
            <?php if ( ! empty( $phone ) ) : ?>
                <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="tel: <?php echo esc_attr( $phone ); ?>">
                    <?php echo esc_html( $phone ); ?>
                </a>
            <?php endif; ?>

        </div>


        <div class="col-6 text-end">

            <?php if ( ! empty( $email ) ) : ?>
                <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="mailto: <?php echo esc_attr( $email ); ?>">
                    <?php echo esc_html( $email ); ?>
                </a>
            <?php endif; ?>

        </div>

    </div>
</div>