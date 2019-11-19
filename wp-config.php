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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'wordpress' );

/** MySQL database password */
define( 'DB_PASSWORD', 'wordpress' );

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
define( 'AUTH_KEY',         ';4G5@2nE8W<NU63pe7x!L=xC2y6aSMaMHuje+{G?;#`{rqGuG+<Foh W{C`f3R/X' );
define( 'SECURE_AUTH_KEY',  '_)I1GwFEW-mf>kDS05M5jXs~n7%#stDaxV<d#`=;qV2v#*`U;Z2FNa-S[#?PG2Rn' );
define( 'LOGGED_IN_KEY',    'SI+z7P3b;](>dwM]FTd<UY2MS4TYytcjPBybXw_f$QUA2>a QYNG6<y&c}w3SZna' );
define( 'NONCE_KEY',        'd!!k`N<8|O6^oyNBp7/t8{2(j?s)z1~zW5~}Rdx=dy*x +jI,# I3,>Jq8]!Jj28' );
define( 'AUTH_SALT',        '%4|G#8)(;%KH^i}Ed?XYdqV5X_5:;gdFT|c%1RIzsLbb#Sf`inE|v7E_X~C}zT+Z' );
define( 'SECURE_AUTH_SALT', '315]`2?Bv)uob0zUaNo:0.l~>49mwzNn!wxCC$KK: ?F+S!4Gq89aMWA8JZW8~J&' );
define( 'LOGGED_IN_SALT',   'F_e{Qy]+hIev^P_et)kl&ep-F:4;TBJbd3~:e&-a5?t dg p1Mhim*;!uDtq-n&2' );
define( 'NONCE_SALT',       'P;ON+m<d|7k8*t)TF!y<F)D`Fm[%BB4,j^-8>N_*[/>?$0vehA4LPvo< hq!~QJ)' );

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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
