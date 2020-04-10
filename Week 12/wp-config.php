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
define( 'DB_NAME', 'usmcamgr_wdd223' );

/** MySQL database username */
define( 'DB_USER', 'usmcamgr_adrian' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Jessica8' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define('AUTH_KEY',         'R),Bs6 frn4w-|11 *2I>SOR@zy,U|eZ}pZ|1>jD(C!_KK{a`BBN2h.iy|C9psed');
define('SECURE_AUTH_KEY',  'I.o=-Rb*}u&BP%bN&*ic}/%78r2n:Ou*? b1jjCeA}-y~DXYE>i+M{N+/sjUfdme');
define('LOGGED_IN_KEY',    '~Sc{WV)xn}%81M)B-9:~W7-R>1^WX.trSHQ!qy+B()<?QUFzEv8e%):qL+Mni,R4');
define('NONCE_KEY',        '`|zW=!3bd5d*-ws~/2+k|[GxiU-WN>VBHU*GlJY3Lo,-APD}a,rOE3$%xXA.{RcF');
define('AUTH_SALT',        ')d:fc82C[8Y(=j7ak&v1M7,hdMJd},_4jA(7#<9wf)pG,c*wbqHg}UII%M8xw}0[');
define('SECURE_AUTH_SALT', '}L*m98d Z`:#Rges8-+eBV1m8db}9*~Mx63;UNVi^tS&:%}~bJ+ p*4+CSMA3TX}');
define('LOGGED_IN_SALT',   ':C*1pR77yVE?oxd?%2&5^n$,V,oD~tYAW;ZX7h57JY.s%GJhpo=JP`Z8-CGxh]Cy');
define('NONCE_SALT',       'jFPdx>zi(S^| ]_{a#Unc=$3 Y^_SW;pUlH*y@ZnD)6L)Hp|(bfaLF955gqVp[<R');

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
define( 'WC_REMOVE_ALL_DATA', true );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );

/* Disable file editing in the dashboard */
define('DISALLOW_FILE_EDIT', true);