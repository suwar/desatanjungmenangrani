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
define( 'DB_NAME', 'desatanjungmenang' );

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
define( 'AUTH_KEY',         '^;>X`~y7^_44HvF_h|liQE:`wT*Ap[-We-]w4VCSGE&v,jhkYS%b0R^.&gE475ci' );
define( 'SECURE_AUTH_KEY',  'l$?jv}d?ZYxON9%20C&@Zioh$!yGSm9j8zxfr:2Twob4n?!h*A!qR[jKr(L5@cR6' );
define( 'LOGGED_IN_KEY',    'SMbw/%3Jm1-^zLG}MIy||]s,soj3$QRBTbg24#u  ?Hu9QI|Np,<,b0Wt:x&LCkS' );
define( 'NONCE_KEY',        '?XRpoqN,?F)X/.w3{S?dQnrqF8M+rSSe&aMdx#`yn<{v)f4=dfk 6?!7E1jc~IDE' );
define( 'AUTH_SALT',        '8U~@J>s<hfg4#-{:sK,@%7&hP;DrREw V>bFhCkwF*|4+SSX*,< ZP40{]cfqJs(' );
define( 'SECURE_AUTH_SALT', '!FKO]HsfW7=HJ5lHyRhzS{|>y<zM4CMMIUyR%! +cQkrdh[OKF.F+OgDA];-Y_{o' );
define( 'LOGGED_IN_SALT',   '>XJisnl.w5D#ypf=c4kAwAT&H%`6m g<hdm*mnsxXcZC7DAg{Km?~Aop%])8/HtS' );
define( 'NONCE_SALT',       '2F)Iu$9pL#`)Ybsb0HA*J.kmbP+a0f* =32e]=,mac:kZ22@W.7T9Z.la+LlM0J5' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp1_';

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
