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
define('AUTH_KEY',         'Q=K=VT5OUb- =U%yR3C2ZluGfZPb%69n$txilyf4E@dPpiT}%-OUd!FX]k/qOj65'); 
define('SECURE_AUTH_KEY',  'i9Z`}>:slCk*XxwOBR7.r=yG}g8&^}P-UaXd5B/>C6Boka$5qDx|U2:0ai4C|!N#'); 
define('LOGGED_IN_KEY',    '.vw*M,+s:mfdjJ2I6Bf`4.3<FNbZy8?]Ubt>HtR}=~Gh21e;JL6SDO.6`^VfRM,V'); 
define('NONCE_KEY',        ' y+uL^G9Y~,AMJR6b<#cS>mu}?L,mf)25;0.9r`cm3~-V&@PzyOA,(wD`+cR/^8%'); 
define('AUTH_SALT',        'ZT?d2@`eBU$wDBs7oLreDZFpG`R+kfmk|Wc@:ASxmrWmJ7Wqo2$8n8HmWt&yV~o '); 
define('SECURE_AUTH_SALT', '{1!PK7aK[[|V:=G1ItO c|UH@Khq:&UXTXeEPAZ. mWq=D ~VAHYXA4-#JSt>#m^'); 
define('LOGGED_IN_SALT',   '+yb_Lbpg{g=0+zB+rYNghs-IhF`E_QvtSk.)06l(5+?VXtLpLW)#m6 Heh5V5G,:'); 
define('NONCE_SALT',       'iQn6|GWNaBPZA.DKU4U2IEltn@eR,pR&fL!g`mX.rfmA:<Eb>_e0]?sSF!.$;N05'); 
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
define ('WPLANG', 'fr_FR');

/** 
 * Pour les développeurs : le mode deboguage de WordPress.
 * 
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant votre essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de 
 * développement.
 */ 
define('WP_DEBUG', true);


define('RELOCATE', true);

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');