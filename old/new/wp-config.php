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
define('DB_NAME', 'synagogudprod');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'synagogudprod');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'HvGKifXF');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'mysql5-27.perso');

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
define('AUTH_KEY',         '`cUE`S4hAz TZu|dO2/[s:J2:Rv)3(o[Qz$.E[-x#=}l-9MNYLtv:+V0<rJogHGT'); 
define('SECURE_AUTH_KEY',  'I,+GNzU@n$cy)NYN.niP(4K;?C<+9p%s|!!CvEY^n9f*#JI6Qm#2]WIh[-Ey[mc>'); 
define('LOGGED_IN_KEY',    'dLB@5 .uO-_eO+CUKKtN+?KprhU~UN+,,4=0{-IRx>z<_(t)O{2m!y*9Vo%t5P}Q'); 
define('NONCE_KEY',        ')# *+_7w%=5)dobli6`~]NS;;5$PQw_G-$G9-(uq$p:7Zw g&?`3>9QzbRH/dQ$n'); 
define('AUTH_SALT',        '*UuS>$VF((`$ (dP,(%{:,S14|R,sUXSV+$}pHY|0Kwo$,;a=2=Fd6@:nhC230H$'); 
define('SECURE_AUTH_SALT', 'a%~J$W+IXFrvS|#2k6zq^I^uxZvsAq}+*ZE)$BA8K/y<f5bNR3e*$AHZCZ|:;AJS'); 
define('LOGGED_IN_SALT',   '|)8D+)n/6X$9P4+e$@MvU>`s{UUka5(F6Z9PW+j1Rz7xWnRudx1-hKVlbSxwx,cX'); 
define('NONCE_SALT',       ':<wY>EvMy56pMwUD2W3%7srPO=N/-RHPtH`*sM:.t^{z_.x-`)`kIMO-rm{aGE;l'); 
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique. 
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'mt_';

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