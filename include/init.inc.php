<?php 

// CONNEXION BDD

$bdd = new PDO('mysql:host=localhost;dbname=boutique','root','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

// SESSION
session_start();

// CHEMIN / CONSTANTE
define("RACINE_SITE", $_SERVER['DOCUMENT_ROOT'] . '/PHP/10-boutique/');

// $_SERVER['DOCUMENT_ROOT'] --> C:xampp/htdocs/
// echo '<pre>'; print_r($_SERVER); echo '</pre>';
// echo RACINE_SITE . '<hr>';

// Cette constante retourne le chemin physique du dossier 10-boutique sur le serveur
// Lors de l'enregistrement d'une image/photo, nous aurons besoin du chemin complet du dossier assts pour enregistrer la photo, on ne peux pas conserver la photo en BDD

define("URL", 'http://localhost/PHP/10-boutique/');
// Cette constante servira entre autre à enregistrer l'URL d'une image/photo dans la BDD

// INCLUSIONS
// On inclu le fichier fonction.inc.php directement dans le fichier init.inc.php
require_once('fonction.inc.php');