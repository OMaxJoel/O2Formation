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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '~%w.63XNs1L94MOx>3-NyTBVvN~YKNmb98O}U0Y>_ODl+40,D[xQ6|0Kh&$Z;Yg~' );
define( 'SECURE_AUTH_KEY',   'Z.-yB]YE]Z!<pvM#$5yJjk(P]56+y9G~=I;JaS^Rr]i[<zNpuw%U;(bH9J-p|Z~P' );
define( 'LOGGED_IN_KEY',     'UTG%+[lFQ?CpP+DYe8BF$C]JH.^bwK2>.^2kxh];y3#H6ab`V{Y;17!BgK{z_6j7' );
define( 'NONCE_KEY',         'I9Jx<WA7^~.YDUc,cViT3tqd3OBW;.cR:O;oxd|k5IwBd5hD?hi_}WB3HBcx?2D1' );
define( 'AUTH_SALT',         '`:?]D5>C09rh|/}wlVkr^Iof/Z,VY<9<kNu*VRN{V9QU}66dI93!x41^}kN8qBI=' );
define( 'SECURE_AUTH_SALT',  '_JbR]Bw)>nH*;jbwe0-rPOw2!ckpPtqz9=_P$~F=]C/8pCS%,Vu,zOpr8?5y_Vfd' );
define( 'LOGGED_IN_SALT',    't_j8SYjg[[SqOY+H$}:AE0E{Z5#~m,@Kn2t/,cMZk]uz`N`U [i_BW{JN;jZkQj4' );
define( 'NONCE_SALT',        '+8lN73isg;b-vm=P?u0qe-k`d#/QDGo2=Pr{Kv-rn}=8gUge]Z_&KTh-bDn(NSR(' );
define( 'WP_CACHE_KEY_SALT', 'eRtEB#=C&5byI}V!%|POzY1[jn+ x;.y`Y;35HT0{[S!5E$PqYL;moS.th<{TVS2' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
