<?php
/**
 * Comments Template
 *
 * Displays comments and the comment form.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

/*
 * Do not load comments for password-protected posts
 * until the password has been entered.
 */
if ( post_password_required() ) {
    return;
}

/**
 * Render the modular comments' template.
 */
get_template_part(
        'template-parts/comments/comments'
);