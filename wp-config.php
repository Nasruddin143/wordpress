<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */
 
define('WP_HOME', 'http://localhost:8080/wordpress');
define('WP_SITEURL', 'http://localhost:8080/wordpress');
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
define('WP_DEBUG_LOG', false);
// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'king_tailors_ozar' );
/** Database username */
define( 'DB_USER', 'root' );
/** Database password */
define( 'DB_PASSWORD', '' );
/** Database hostname */
define( 'DB_HOST', 'localhost' );
/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );
/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'tSGnzMEscf$e-+&Is*rV|M{l$6+[YsSH0=LAf]J(tBNy+CO%9znPSdcJt%+l6j$l' );
define( 'SECURE_AUTH_KEY',  'Kqj&X;8eL{BiC>rEATR*1BU|$Mi}3Ts=8(12Rx+<!p{fY&J}t8}Ahn.$(HhbnqmW' );
define( 'LOGGED_IN_KEY',    'tHJN}0i Uw9bx!iR@<tv09i Dt;IpZ!`RuT}63((q@QH+1U_1nwA%jB@qN-K4~M(' );
define( 'NONCE_KEY',        '3c(Lhjwe[,WGN*F)8ei?gS~&~zn2>1e.e} H`5dggaX2!+2Q]A;Y&K0-B@RPH4ui' );
define( 'AUTH_SALT',        'PhQ>$.7K+U]px.xVH_jI?s0vG;:eNWMM$&fW,dAFD+$+k#l28f8N@d{(B~FaQ?xE' );
define( 'SECURE_AUTH_SALT', '&Y5]?gM-!6XL,2#sa4.q]dA$Z#kTiJR2@xmaFV-PoGf515Kphb-C| p87=j(1Dcb' );
define( 'LOGGED_IN_SALT',   '[n-P*HSu|X&kd1:U$tu>zx8~0TEexhVuQE0h ( .CeIiE[{d/@F#JP2fX,Tn`7m(' );
define( 'NONCE_SALT',       'sbYzCR]ylWL,,.I?zS5rt,U/xqAF{Bf-%2(j>rKn2lwSgSdC]pPO^g;R96UbV^XG' );
/**#@-*/
/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
// define( 'WP_DEBUG', false );
// define( 'WC_REMOVE_ALL_DATA', true );
/* Add any custom values between this line and the "stop editing" line. */
/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';