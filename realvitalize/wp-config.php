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
define( 'DB_NAME', 'wordpress_site' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'Tiger@123' );

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
define( 'AUTH_KEY',          '?#LJ0]<NlM:zI9<CQ5#AUZ4P2B3+eFd/U<ayp[ooP~!er*K$[R(frl`PH`c ~#7Y' );
define( 'SECURE_AUTH_KEY',   'b<#fzg/S_3gxgU#CFnD>fxj^Cw]EEcApw61FdeNOQ#h?Jfdg$0ieJgp]i:r)*Umv' );
define( 'LOGGED_IN_KEY',     'YD;.f~o2ul]H3>5}@vqB&x-P >M`tud.3ZG/;lg91B39,c@%s^HA)GqwB$p3aQ^$' );
define( 'NONCE_KEY',         'mFDB7?)d`NRtI<8GW=ET2Lz~k[z}b8WnIr1VES[ )N31 Q{>w!_!;JrJ1)~.&+}u' );
define( 'AUTH_SALT',         'ykRt2W)|h)+K??@x2LqpEX6D{p3lOLpIh.YP`L5DH lz$YBGbuANcS`c`:y*1(/;' );
define( 'SECURE_AUTH_SALT',  'ta&DD=sKm}@m=l*cSaQf:456nggqH)bJ|/6%S~:EK WE$^C3Fidvo!@bTRV5-`E=' );
define( 'LOGGED_IN_SALT',    '2K5YE] txw7impd;>obWeFIW39zC&<f8lD1:*R^2HJRt&-M0tSZ-zzV4Jop1BPVU' );
define( 'NONCE_SALT',        'b=%zM@lS@Yy5//GvI`^GB8O6W0yK5G>5_^p!J-:hv1qt>2`&=O!Ja[ji)Ys:bC:c' );
define( 'WP_CACHE_KEY_SALT', '*]iZX5Y9w4@U(]~Rz1Vf_V3n #Y[,Vv+t,p4=I/b1qLLy@O`et&-0oz`/BruIH*_' );


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



define( 'WP_MEMORY_LIMIT', '256M' );
define( 'WP_REDIS_HOST', '10.44.10.10' );
define( 'WP_REDIS_PREFIX', 'TENWEBLXC-822204-object-cache-' );
define( 'WP_REDIS_PASSWORD', ["redis_user_822204","JV2pYh8sqQ47Ba1GAxyFgms9BMoL5yzxEe"] );
define( 'WP_REDIS_MAXTTL', '360' );
define( 'WP_REDIS_IGNORED_GROUPS', ["comment","counts","plugins","wc_session_id"] );
define( 'WP_REDIS_GLOBAL_GROUPS', ["users","userlogins","useremail","userslugs","usermeta","user_meta","site-transient","site-options","site-lookup","site-details","blog-lookup","blog-details","blog-id-cache","rss","global-posts","global-cache-test"] );
define( 'WP_REDIS_TIMEOUT', '5' );
define( 'WP_REDIS_READ_TIMEOUT', '5' );
define( 'TENWEB_OBJECT_CACHE', '1' );
define( 'TENWEB_CACHE', '1' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
