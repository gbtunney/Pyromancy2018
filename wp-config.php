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
$_SERVER['SERVER_PORT'] = '8888';

define('WP_SITEURL',"http://pyromancy2018:8888/wp");
define('WP_HOME', 'http://pyromancy2018:8888/wp');


// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'pyromancy2018');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         ',ul`Y.xgC>Q& G{U*0?q2tVUtNEj1xU@T@ 0_#DBjwgO^|h:k`:oT,*!Vn=GR-)c');
define('SECURE_AUTH_KEY',  'j81?<-/M=oRy,]RkH! =D}9kt*WTPP.u2A5E!,}IjR?w^4}3x.e~0I,F2F^o8^sK');
define('LOGGED_IN_KEY',    '|}W(e=6f^~M+#|vxat(mK@hmYBiDL3~oss@A5uXky6Hn66.}0bd;Bx_N4l*GlonO');
define('NONCE_KEY',        '=$=#JL4Ov-LXkDhp]Sukt$gGT%K2>AWogs`8+}cOx,p+!z3cq>v}YpXtA-TGXZB$');
define('AUTH_SALT',        'l5-vm%AE~)Q)a|*8,#&KILch5h`Z?bEMO&yYu,`.DaM+|cCF2D@[EY^#o4jAWDO.');
define('SECURE_AUTH_SALT', '!q5Um.LOSeGc,T%D Xv/MI8K=  A>[wvQ-a-{{pPKX+N.0+Aa~2z?nyzU^G#^WmC');
define('LOGGED_IN_SALT',   'r#0ORB`blTwB LK*t t&r1[@NpLA,WUd;G[M-^PcX||!YILvKAMVCn,Ld0)nY28m');
define('NONCE_SALT',       '4fL+c0z*GM@3RLZHDyy.cm-CHUmTeq(KT-p*~0[PUB~H~Qe}><8Z#4*E3;p~0]YR');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
