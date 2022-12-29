<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

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
define( 'AUTH_KEY',         '+:%^l:nxC,#tk*C:kxVDO{$h;U&`fgp$!y;/9wC[fJsga!*v-%EHlE/~?*1JyHWY' );
define( 'SECURE_AUTH_KEY',  '~*&2tuLs7PGZ.L,&~VOruX|%,M9x4-}NX6FSR/~I%cN^v,;.bn&PI6s$||>wj9Y*' );
define( 'LOGGED_IN_KEY',    ' [: opm>`.G$4P26u,2Ir-m2qU B{7ca@m~9*@j;P{sXem|TlJ?6Lk=[V1_G/~BI' );
define( 'NONCE_KEY',        '/zRBSr$F(ML 9QVMJF~9%CT$eLnwxU#X^{gq0)G&b}IwJ@IL5(9+6j8xZ7ydcOnV' );
define( 'AUTH_SALT',        ',**QJ9ZzxNPRTYA=qqshC`03d/l~z2>)5!oK &;`jld,n5u~Wpt+([<E}#L`BL]L' );
define( 'SECURE_AUTH_SALT', '5yvU[y5Yh%g>c 8X>0vbfy!CMEiMI&JHH/0~@waTvn,R=ujHYI0Ga/uatrrVEo9~' );
define( 'LOGGED_IN_SALT',   '{N<Rnz{2An@D78Zv>bOh@wvfM4u[1H26sEH&[&;B<JNGDnq_WLiN.plJ0tl~t(bp' );
define( 'NONCE_SALT',       'lmz$(bHGNLVFGuS`?wNd1]FyL0pAh-oo?1}bGmPrU@=%)S`c^a|p`Sk Ai #s7*#' );

/**#@-*/

/**
 * WordPress database table prefix.
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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
