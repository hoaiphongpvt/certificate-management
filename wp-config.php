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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'myblog' );

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
define( 'AUTH_KEY',         'AWO=.?grk%9 4o.;Zh!DdQ-65&Sdw9/:~^~7CV_;^++9&biZ~Ocj.cU$!]w4nR$V' );
define( 'SECURE_AUTH_KEY',  'C;[iv`>TS/>bIQ)z6On+)aU~2?w~#rXL<zi9}{;-!vs*3ou(izHLNH h3t0{X^xU' );
define( 'LOGGED_IN_KEY',    'iaaNrx/19EAO1??$9P-<8[mAY@/(b3RPubJIdAvyXne?WEKH-Fe`njt7Y<H:=$cn' );
define( 'NONCE_KEY',        '+fU%`E#Ak0*%G/udnVI=k2*jo_?/l]>[ro?MDP:o=t1askv89Ehp68~8]u?eu`vt' );
define( 'AUTH_SALT',        'Y{~`^,yL! +<_u>7c_?zXhNu[}g/EUMZ.N>/_wC-(=yQ 8.-,v[&,X0]uZ|Up5Hb' );
define( 'SECURE_AUTH_SALT', 'vNCpP/} JJ_jlb^%z8x*xJ2IeMH(#z 8}{=`-gfVCW&S(I;U26RGXfNo^}09Mp=T' );
define( 'LOGGED_IN_SALT',   'tTKk5$F_&Bst%lRPOXN2G9@2a9yh)P~nB(zE%f8:hPF[rM{i//f=3|.vdKD&2@E}' );
define( 'NONCE_SALT',       'F,ZWJ)?2xVi_s5$).k9WYez7<SQOsNS;Wm;!;%8)9dSCLH12L;5oL|NE@84e_TVp' );

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define('WP_DEBUG', false);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
