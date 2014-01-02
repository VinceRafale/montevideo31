<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'synagogudprod');

/** MySQL database username */
define('DB_USER', 'synagogudprod');

/** MySQL database password */
define('DB_PASSWORD', 'HvGKifXF');

/** MySQL hostname */
define('DB_HOST', 'mysql5-27.perso');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'L$}:Uwc?o-0wR{e}KruX?Xr)f5If[[%{s~R)I7tU[zB#C|-{@#$~Q@*;@Vp_v]T|');
define('SECURE_AUTH_KEY',  'Cm3_M40>oh]1b@>|+@$=6cT[.=zx50&O(~2G$: xO(/p;|.Ddz|H|8b-%ekSpSaJ');
define('LOGGED_IN_KEY',    'NUQPekH Z=FEy)t-x*Ii^i$[{0E&G,ABXk|qxKJXlzN5|3qlSxg@u23=lXN.D:|~');
define('NONCE_KEY',        'vYyd{oeSunq+Ch9dFXjX}F@`{+|xm@ZVH~taIa!+QJF$/w+u&rV?`^-x|%3<X5UD');
define('AUTH_SALT',        '*08W)!k-P-.[dqw6|<&bkk0}5s0{-e!-k5NB6+c5u@xTv!2{bAKGh0=1?1J%fdQk');
define('SECURE_AUTH_SALT', '^[_RmpnpznKl+vw )J2S|@5|DHD+ua:NCuiAMFG5;j2y((H*^E =D13)wx/2l.En');
define('LOGGED_IN_SALT',   '4aLV1dj|PcdSs-{,(j;Q$]$m:1-N+MM-U2,g@+o^_5IJ6h0Ef3L!oRX1tHRZSBJw');
define('NONCE_SALT',       '3yR<2Oi@YYMTX;dF(KX+)(I]&gzx_$Mg]b/$4ZFWzzn|&_g3*ZQY^LY7*h{mE6A`');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'test_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
