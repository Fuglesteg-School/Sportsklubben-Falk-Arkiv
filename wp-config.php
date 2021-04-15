<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '/[%@nXQHGV6o^bO3Y[mU-wKm(VZvJYf$P0CpgNHJ({~C)5Zb8WICi[]&^w1Rlq[0' );
define( 'SECURE_AUTH_KEY',  'f}/tiJuhNmm`VchatBNuu<p^mWXG2MSbb-alFWxz`F48j/HUaQV~e`]01QAIWm>L' );
define( 'LOGGED_IN_KEY',    'CfJhZ*%!Pzk?k}C7%x1A{B}e%]?(d5;`kE6t#x%S@%AhzG|?:p24ra54arM#pgCj' );
define( 'NONCE_KEY',        'x<`&;Rxk.hFQ2wtv3B|EI 3wjb|10B[cSSKNWy<l_`a^y:FWbB}NvkJ=}JG,hfJB' );
define( 'AUTH_SALT',        'kKJPQe5u?KfV`TObqCnM<iV,.00Ng~va>!S0xDZJi_D8N~+{Wq(Z(gAGA*M[vsiY' );
define( 'SECURE_AUTH_SALT', 's-0d$8Me[}FlIP)m)sKm7jQ#:285A4,Y)aHz2p/cx^Af_xkoJ<GzsDdiw:]P9,,h' );
define( 'LOGGED_IN_SALT',   'fgLD}9%yYK#(yqKT>+VMn`GOIhw9?JR$aZUJvc#>3_vvF0fQ2sIyQ:*(}T8YaQk.' );
define( 'NONCE_SALT',       'l*@x;/Znf+MnFIb1Z<YjJ;%oPL=;DC#9#7AEmm/hHn3L>j=fNeU#[O.W^`~<u4_&' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
