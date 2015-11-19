<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/home/cfdemo/public_html/dentist5/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'cfdemo_dentist5');

/** MySQL database username */
define('DB_USER', 'cfdemo_5dentist');

/** MySQL database password */
define('DB_PASSWORD', 'tsit12345!!');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'bquUr:]b^c@.W+V]F)Ja-KFy%y8zGd$SX=l>hbkze2tYXeS+]dwBoN hPw@](9iW');
define('SECURE_AUTH_KEY',  '>^;~E}@PztAm@C6mwfQ+MLp/cr;)U#1mro;^eF|^ m%P3Er!g~s(L1I62{Sx+kw$');
define('LOGGED_IN_KEY',    '6(Q8B4[(X?N#piaNswy{N:BEAYqU1Owhgq|z;%##3>6Q.B,b@E$7XDCPhxp0JbFK');
define('NONCE_KEY',        'hqxd`e8/*34tQ:]XBo]pCjVD>}u$*.EF~K{qI!q$(E7/(=Wkkau3a7i/f(RtZAfL');
define('AUTH_SALT',        'fMto*|6:[;44Z8hR9D&1r{v31ZkgX,gLYGo;,vp.B5-hd02GQd`q`P>F*!Uz%EfM');
define('SECURE_AUTH_SALT', '/}YuxJNMz8/nuGTY<hnpx%D5Zy5mibZ[54.] x|=]Qv+ts!QV@0Anu@qd_u<OAA_');
define('LOGGED_IN_SALT',   ',3q5Q(gA/9L9`Hq|JkN R%<w50@&9,)zXShr:U+v?lEF[pyhN&{N5xjg[id]GXXw');
define('NONCE_SALT',       'K[Qsg-VrH.r?%p]:#wq/Cb5Obo7_CA#@)TxBqZg6x`^OglaE2xj=wJcX/h;?o53B');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
