<?php
require_once('inc/init.inc.php');

 $resultat = $pdo -> query("SELECT s.*,p.* FROM salle s , produit p WHERE s.id_salle = p.id_salle");

$afficher .= '<div border="2">';

$afficher .= '<div class="col-md-3 col-md-offset-3">';
	$afficher .= '<h2>Salles disponibles dans la catégorie </h2>';
$afficher .='</div>';
	
 		$afficher .= '<div class="row" >';

	 		$afficher .='<div class="col-sm-12 col-lg-6 col-md-6 col-xs-12 col-lg-offset-3 col-sm-offset-1 col-xs-offset-3">';	
 while ($produit = $resultat -> fetch(PDO::FETCH_ASSOC)) {
 
				$afficher .= '<div class="thumbnail" style ="float:left;border:1px solid black;display:block;margin:5px;padding:5px">';
					$afficher .= '<h3 style="text-align:center;padding:5px;font-size:1.2em">' . $produit['titre'] . '</h3>';
					$afficher .= '<p style="text-align:center" ><img src="' . RACINE_SITE . 'img/' . $produit['photo'] . '" height="200" width="200"/></p>';
					$afficher .= '<p><b> Prix :' . $produit['prix'] . '€</b></p>';
					$afficher .= '<p>' . substr($produit['description'], 0, 25) . '... </p>';
					$afficher .= '<p> Du '. $produit['date_depart'].' au '. $produit['date_arrivee'] . '</p>';
					$afficher .= '<p> Capacite : '. $produit['capacite'] . '</p>';
					$afficher .= '<p style="border-radius:3px;padding: 5px; background: #333; margin: 10px;display:block;text-align:center;"><a style="color:white;text-decoration:none" href="fiche_produit.php?id_produit=' . $produit['id_produit'] . '">Voir la fiche</a></p>';
				$afficher .= '</div>';
	
 }


			$afficher .= '</div>';
			$afficher .='<div class="row">';
			$afficher .='<div class="col-lg-1">';
 $afficher .='<aside>';
			$afficher .='<h3>Catégorie</h3>';
				$afficher .='<nav>';
					$afficher .='<ul>';
						$afficher .='<li><a href ="" >Réunion</a></li>';
						$afficher .='<li><a href ="" >Bureau</a></li>';
						$afficher .='<li><a href ="" >Formation</a></li>';
					$afficher .='</ul>';
				$afficher .='</nav><br><br>';

				$afficher .='<h3>Ville</h3>';			
				$afficher .='<nav>';
					$afficher .='<ul>';
						$afficher .='<li><a href ="" >Paris</a></li>';
						$afficher .='<li><a href ="" >Lyon</a></li>';
						$afficher .='<li><a href ="" >Marseille</a></li>';
					$afficher .='</ul>';
				$afficher .='</nav><br><br>';
		$afficher .='</aside>';

			$afficher .='<div>';
			$afficher .='</div>';

require_once('inc/haut.inc.php');
echo $afficher;
require_once('inc/bas.inc.php');
?>
