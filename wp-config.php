<?php

define('WP_HOME','http://developer2021.hotshot.nz');
define('WP_SITEURL','http://developer2021.hotshot.nz');

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
define( 'DB_NAME', 'hotshot_nz' );

/** MySQL database username */
define( 'DB_USER', 'hotshot_nz' );

/** MySQL database password */
define( 'DB_PASSWORD', 'kCdfrmximjtwS35N' );

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
define( 'AUTH_KEY',         'O}o4itL:90wP [|z`<_u3Zq0=F( 9=OcoY{u&:M-{PwPL>Wi ,e1xke](VfcY7O{' );
define( 'SECURE_AUTH_KEY',  '|3%7YJ6aKtOUb)UF=_y^`_E-LW3i>SY)LCTTnu}xzw?q7C)5%[mpTPsGYl}@K$]+' );
define( 'LOGGED_IN_KEY',    'A8ab47n~YPz1$Ya)yb*=KOLKs1?~X.]_G!SvtDG6a[|d^F0[*sRK8/`a0mBI9H~:' );
define( 'NONCE_KEY',        'I#{cPvlB!cxAh3i>>ZL`_^ajx(p^Nai(vf8|bd+qE8i&0ap,.KR_`sC*u:I_sAr&' );
define( 'AUTH_SALT',        'L(uxPig208&3a*GynYvp*Y-Y/Uq>iiW2vYm5 sDGk$W fjm JmpWaRyLA=^x~J1^' );
define( 'SECURE_AUTH_SALT', 'Jw+t}(R[tQ?r6uf<t+@3N4WD:;X5h`J$rhM=~`3)<H(aJq]80QCch?thW^#cM)RE' );
define( 'LOGGED_IN_SALT',   '|/-ak_Eo]~7.>>5R5H2f[3mPwL|ZKXGE?5r#YKM*U^xX:ONoNn!K}4WQH%H?{qQ=' );
define( 'NONCE_SALT',       '5b;ECb+yraFL#:y nE*uj!3yna~v~/&1_VX-[ISb$R}Jzvm6AXVV(^ycJ7)FFej#' );

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
