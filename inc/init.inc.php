<?php

/* CONNEXION A LA BDD */

$pdo = new PDO("mysql:host=localhost;dbname=lokisalle", 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));


/* CHEMIN */
define("RACINE_SITE", "/PHP/lokisalle/");


/* SESSION */

session_start();


/* VARIABLES */
$option= '';
$produit = '';
$salles= '';
$photo_bdd ='';
$salle = '';
$afficher ='';

?>
