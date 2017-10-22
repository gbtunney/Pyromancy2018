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
$_SERVER['SERVER_NAME'] = '';
define('WP_SITEURL',"http://pyromancy2018:8888/wp");
define('WP_HOME', 'http://pyromancy2018:8888/wp');

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'pyromancy2018' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ']D<{r|6&%L@:NZAL8a6Yj`9Eq,-iQU5z]5J?JNm=oQ341F{ZsOd*I*9dR[k|Z>R#');
define('SECURE_AUTH_KEY',  'Go/)q!dgMe6</s%A)g$hZjW]yLf0v|/i.@%X5N|COdt}Nn|@L8Xv+NdO%{{l%m>x');
define('LOGGED_IN_KEY',    'bmYmh[oL`b:/!I1EMJ!._eD:.{&c+hQ#x9[cri-D+C2Ahr!g.s4/(N+zm^Db<43k');
define('NONCE_KEY',        'd3 +FT_5O+cthaqdo+rY%#xfydFA.={u15+i7LgXns#o.u+QHZt8jpY:hD`gVXI+');
define('AUTH_SALT',        'NSH0^l@$R1z=KB=+P@_kj{/k[ 5H?M80E#aSqU#pgF;CRRE;;v3/JT`i.:/rzMdz');
define('SECURE_AUTH_SALT', '$esTmWr8V0Uco(5{ny;o<c>_{JCPZUk25vMkfrh|uS1v^Rl7<[6T0:8(asLXgt[+');
define('LOGGED_IN_SALT',   '3<J{H%<Mn{Sp-4z+o8YyCl}_&V;`KR1Ao=lzui3*J/zmvi8vGR+d_9,!C6G|hwcF');
define('NONCE_SALT',       '*?Z.=Fie 3YQ/KQH+W,IH6@c[|/Bl3*QC`;MN+YlA&NE0]7HHHL^fPE~hcavPBSd');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
