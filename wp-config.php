<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur 
 * {@link http://codex.wordpress.org/Editing_wp-config.php Modifier
 * wp-config.php} (en anglais). C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'db_spip2wp');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'spip2wp');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'm4893!q57+XEq-I');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8');

/** Type de collation de la base de données. 
  * N'y touchez que si vous savez ce que vous faites. 
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant 
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '8sQP?j^[h[T.|hw+fnd&KQ47CjUbL*nC98Do@$bDK.4+6$2!)ZGr2RMUJ_0^5nl-');
define('SECURE_AUTH_KEY',  'a*CrTq)d%0CKGM$9-RlM+|+:d?D>yoPac6]>-X~BGaBVb,vx9+^KOlP4vMR>2DT5');
define('LOGGED_IN_KEY',    'jcJ9W!j4<)Y<GDbA9Cbn^33S.,B]- zHq >oiDYDdrp 5hH62!f^[RR=(}vV^h?L');
define('NONCE_KEY',        'r-@lg:c7P[r?794b^,|jU;-uSsM[Ft*,YSX>~[42bRJ-hg>Ipgl`HhOib2~7L2D-');
define('AUTH_SALT',        'HJ#]XY*EW/+AelmWSoyN,w^Gu=t2r|A{Mh^2 +1;Kokf1cUdAg|htty/-(Y69n:{');
define('SECURE_AUTH_SALT', '`(H[*9a@S|.7S;;:xpMkjCJ{+;ySB!l_n?w7TYgWM`zg-1[_<E&yGA$NI/m=BNsj');
define('LOGGED_IN_SALT',   ')oN;ut`b~2}Of=Kd7@@1o7KTZVP]~l_TWa9 8o,-JMt~GQ;xdikB+l.Rl>wQfv]B');
define('NONCE_SALT',       '4770f&h~~F-4J#a]Qj@TG^(.anB-(8^P_HyVS38TmS7`g~vb:F>swP.u,NOcheFU');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique. 
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/**
 * Langue de localisation de WordPress, par défaut en Anglais.
 *
 * Modifiez cette valeur pour localiser WordPress. Un fichier MO correspondant
 * au langage choisi doit être installé dans le dossier wp-content/languages.
 * Par exemple, pour mettre en place une traduction française, mettez le fichier
 * fr_FR.mo dans wp-content/languages, et réglez l'option ci-dessous à "fr_FR".
 */
define('WPLANG', 'fr_FR');

/** 
 * Pour les développeurs : le mode deboguage de WordPress.
 * 
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant votre essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de 
 * développement.
 */ 
define('WP_DEBUG', false); 

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
