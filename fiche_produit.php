<?php
require_once('inc/init.inc.php');

if(isset($_GET['id_produit'])){
	$resultat = $pdo -> prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
	$resultat -> bindParam(":id_produit",$_GET['id_produit'],PDO::PARAM_INT);
	$resultat -> execute();
	if($resultat -> rowCount() < 1 || !is_numeric($_GET['id_produit'])){
		header("location:index.php");
	}

	$resultat2 = $pdo -> query("SELECT s.*,p.* FROM salle s , produit p WHERE s.id_salle = p.id_salle AND s.id_salle = $_GET[id_produit]");

	$produit = $resultat2 -> fetch(PDO::FETCH_ASSOC);

		$afficher .= '<div class="container">';
		$afficher .= '	<div class="row">';

			if($produit['etat'] == 'libre'){
				$afficher .= '<a style="margin-top:10px" class="btn btn-primary col-md-1 col-md-offset-11" href="fiche_produit.php?action='.$produit['etat'].'" role="button">Réserver</a>';

			}
			else{
				$afficher .= '<p style="background=red; padding=5px">Salle déjà réservée.</p>';
			}
	


	

			$afficher .= '<div class="col-md-11"><h2 style="text-align:center;padding:5px;font-size:1.6em; float:left;">' . $produit['titre'] . '	</h2></div>';
			$afficher .= '<div class="col-md-8">';
				$afficher .= '<img src="' . RACINE_SITE . 'img/' . $produit['photo'] . '"width="100%"/></div>';
				$afficher .= '<div class="col-md-4">';
				$afficher .= '<h4>Description</h4>';
				$afficher .= '<p>' . $produit['description'] . '</p>';
				$afficher .= '<h4>Localisation</h4>';
				$afficher .= '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.7639754870684!2d2.2944552156738567!3d48.84364057928608!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e6701638ac7671%3A0x6f9d1060581bb832!2s30+Rue+Mademoiselle%2C+75015+Paris!5e0!3m2!1sfr!2sfr!4v1478688472114" width="400" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>';
			$afficher .= '</div>';
		$afficher .= '</div>';

		$afficher .= '<h4>Informations complémentaires</h4>';
			$afficher .= '<div class="col-md-4" style="border-left:3px solid #333">';
				$afficher .= '<p> Arrivée : '. $produit['date_depart']. '<br>' . 'Départ : '. $produit['date_arrivee'] . '</p>';
			$afficher .= '</div>';
			$afficher .= '<div class="col-md-4" style="border-left:3px solid black">';
				$afficher .= '<p> Capacité : '. $produit['capacite']. '<br>' . 'Catégorie : '. $produit['categorie'] . '</p>';
			$afficher .= '</div>';

			$afficher .= '<div class="col-md-4" style="border-left:3px solid black">';
				$afficher .= '<p> Adresse : '. $produit['adresse']. '<br>' . 'Tarif : '. $produit['prix'] . '</p>';
			$afficher .= '</div>';


	/* AFFICHER LES PRODUITS SIMILAIRES */

	/*$afficher .= '<br><hr/><h2>Suggestions de produit</h2>';
	$resultat2 = $pdo -> query("SELECT * FROM salle WHERE id_salle != $_GET[id_produit]");
	$afficher .= '<div class="row">';
	while ($produit = $resultat2 -> fetch(PDO::FETCH_ASSOC)) {
		$afficher .= '<div class="col-md-3 "><h2 style="text-align:center;padding:5px;font-size:1.6em; display:inline-block;">' . $produit['titre'] . '	</h2></div>';
		$afficher .= '<img class="col-md-3 col-md-offset-1" height="150"src="' . RACINE_SITE . 'img/' . $produit['photo'] . '"/></div><br>';
		$afficher .= '<p class="col-md-3 col-md-offset-1" style="padding: 5px; background: #333; margin: 10px;display:inline-block;text-align:center;"><a style="color:white;text-decoration:none" href="fiche_produit.php?id_produit=' . $produit['id_salle'] . '">Voir la fiche</a></p>';
		$afficher .= '</div>';
	}
	$afficher .= '</div>';*/

	// Ajout de suggestions
    $afficher .= '<br/><hr/><h3>Autres Produits</h3>';
    
    $resultat = $pdo -> query("SELECT id_salle,photo FROM salle WHERE id_salle != $_GET[id_produit]");
        $afficher .= '<div class="row">';
    while($fiche = $resultat -> fetch(PDO::FETCH_ASSOC)){
        // debug($produit);
        $afficher .= '<div class="col-md-3"><a href="fiche_produit.php?id_produit='.$fiche['id_salle'].'"><img src="' . RACINE_SITE . 'img/' . $fiche['photo'] . '" height="130" width="250" style="margin-bottom:45px; border:none;"/></a></div>';
    }
        $afficher .= '</div>';


    $afficher .= '<div class="row">';
    $afficher .= '<div class="col-md-12">';
    $afficher .= '<form method="POST" action="">';
        $afficher .= '<input type="text" class="form-control" placeholder="Déposer un commentaire et une note..."><br>';
        $afficher .= '<input type="submit"  class="btn btn-primary col-md-offset-11" name="commenter" value="Commenter">';
    $afficher .= '</form>';
    $afficher .= '</div>';
    $afficher .= '<div class="col-md-4">';
        $afficher .= '<a href="index.php">Retour vers le catalogue</a>';
    $afficher .= '</div>';
    $afficher .= '</div>';
    
    

        /*echo'<pre>';
        echo'</pre>';*/




}
require_once('inc/haut.inc.php');
echo $afficher;


?>

<?php
require_once('inc/bas.inc.php');
?>