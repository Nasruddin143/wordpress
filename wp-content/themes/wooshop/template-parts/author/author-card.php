<?php
/**
 * Author Card
 *
 * Displays information about the current post author.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

$author_id = (int) get_the_author_meta( 'ID' );

if ( ! $author_id ) {
    return;
}

$author_name = get_the_author();
$author_url  = get_author_posts_url( $author_id );
$author_bio  = get_the_author_meta( 'description', $author_id );
?>

<section
    class="ws-author-card"
    aria-label="<?php esc_attr_e( 'About the author', 'wooshop' ); ?>">

    <div class="ws-author-card__avatar">

        <?php
        echo get_avatar(
            $author_id,
            96,
            '',
            $author_name,
            [
                'class' => [
                    'ws-author-card__image',
                ],
            ]
        );
        ?>

    </div>

    <div class="ws-author-card__content">

        <h2 class="ws-author-card__name">

            <a
                href="<?php echo esc_url( $author_url ); ?>">

                <?php
                echo esc_html( $author_name );
                ?>

            </a>

        </h2>

        <?php if ( $author_bio ) : ?>

            <div class="ws-author-card__bio">

                <?php
                echo wp_kses_post(
                    wpautop( $author_bio )
                );
                ?>

            </div>

        <?php endif; ?>

        <a
            class="ws-author-card__link"
            href="<?php echo esc_url( $author_url ); ?>">

            <?php
            esc_html_e(
                'View all posts by this author',
                'wooshop'
            );
            ?>

        </a>

    </div>

</section>