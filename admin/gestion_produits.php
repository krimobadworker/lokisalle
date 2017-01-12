<?php
require_once('../inc/init.inc.php');

/* SUPPRESSION D'UN PRODUIT */

if (isset($_GET['action']) && $_GET['action'] == 'suppression'){
     $resultat = $pdo -> prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
     $resultat -> bindParam(':id_produit',$_GET['id_produit'],PDO::PARAM_INT);
     $resultat -> execute();
     // Le resultat doit apparaitre sousforme de tableau on fait un PDO::FETCH_ASSOC
      $produit_a_supprimer = $resultat -> fetch(PDO::FETCH_ASSOC);

      $chemin_photo_a_supprimer = $_SERVER['DOCUMENT_ROOT'] . RACINE_SITE . 'img/' . $produit_a_supprimer['photo'];

      unlink($chemin_photo_a_supprimer); // UNlink = fontion qui permet de suprimet un fichier de notre serveur

      $resultat = $pdo -> prepare("DELETE FROM produit WHERE id_produit = :id_produit");
      $resultat -> bindParam(':id_produit',$_GET['id_produit'],PDO::PARAM_INT);
      $resultat -> execute();
      $produit .= '<div class="validation">Le produit <b>'. $_GET['id_produit'] . '</b> a bien été supprimé</div>';
      //$_GET['action'] = 'affichage';
      header('location:?action=affichage');
 }

/* ENREGISTRER UN NOUVEAU PRODUIT DANS LA BDD */

if($_POST){

	if(isset($_GET['action']) && $_GET['action'] == 'ajout'){

	$resultat = $pdo -> prepare("INSERT INTO produit(id_salle, date_arrivee, date_depart, prix, etat) VALUES(:id_salle, :date_arrivee, :date_depart, :prix, :etat)");
	}
	else{
		$resultat = $pdo -> prepare("REPLACE INTO produit(id_produit, id_salle, date_arrivee, date_depart, prix, etat) VALUES(:id_produit, :id_salle, :date_arrivee, :date_depart, :prix, :etat)");

		$resultat -> bindParam(':id_produit',$_POST['id_produit'],PDO::PARAM_INT);
		$produit .= '<div class="validation">Le produit <b>'. $_POST['id_produit'] . '</b> a bien été modifier</div>';
	}

	print_r($_POST);

	$resultat -> bindParam(':id_salle',$_POST['id_salle'],PDO::PARAM_INT);
	$resultat -> bindParam(':date_arrivee',$_POST['date_arrivee'],PDO::PARAM_STR);
	$resultat -> bindParam(':date_depart',$_POST['date_depart'],PDO::PARAM_STR);
	$resultat -> bindParam(':prix',$_POST['prix'],PDO::PARAM_INT);
	$resultat -> bindParam(':etat',$_POST['etat'],PDO::PARAM_STR);

	$resultat -> execute();
	$produit .= '<div class="validation">Le produit <b>'. $_POST['id_salle'] . '</b> a bien été ajoute</div>';
}


	$produit .='<br/><a href="?action=affichage" style="margin-left:100px;color:orange;font-weight:bold;padding:10px;background:#3B444B;border-radius:3px">Affichage des produits </a><br/><br/>';
	$produit .='<br/><a href="?action=ajout" style="margin-left:100px;color:orange;font-weight:bold;padding:10px;background:#3B444B;border-radius:3px">Ajout d\'un produit </a><br/><br/><br/><br/>';

if(isset($_GET['action']) && $_GET['action'] == 'affichage'){

	$resultat = $pdo -> query("SELECT * FROM produit");
    $produit .= '<br><h2>Affichage de tous les produits</h2><br>';
    $produit .= '<table class="table table-striped" border="1">';
    $produit .= '<tr>';

    for ($i=0; $i < $resultat -> columnCount(); $i++){
    $meta = $resultat -> getColumnMeta($i);
    $produit .= '<th>' . $meta['name'] . '</th>';
	}

	$produit .= '<th colspan ="2">Action</th>';
	$produit .= '</tr>';

	while ($ligne = $resultat -> fetch(PDO::FETCH_ASSOC)){
	// echo '<pre>';
	// print_r($ligne);
	// echo '</pre>';
	    $produit .= '<tr>';
	    foreach($ligne as $indice => $valeur){
	        $produit .= '<td>' .$valeur. '</td>';
	    }


	/* LIENS PICTO */

    $produit .='<td><a href="?action=modification&id_produit='. $ligne['id_produit'] . '"><img src ="' . RACINE_SITE .'img/edit.png" />Modifier </a></td>';
    $produit .='<td><a href="?action=suppression&id_produit=' . $ligne['id_produit'] . '"><img src ="' . RACINE_SITE .'img/delete.png" />Supprimer </a></td>';

    $produit .= '</tr>';
	}

	$produit .= '</table>';
}
require_once('../inc/haut.inc.php');
echo $produit;

$resultat = $pdo -> query("SELECT * FROM salle");
// $resultat -> bindParam(':id_salle',$_POST['id_salle'],PDO::PARAM_INT);




while($salle = $resultat -> fetch(PDO::FETCH_ASSOC)){

	$id_salle_actuelle = ''; 
	if(isset($_GET['id_produit'])){ // S'il y a un id_produit dans l'url on est dans le cadre d'une modification.

		$resultat2 = $pdo -> prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
		$resultat2 -> bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
		$resultat2 -> execute();
		$salle_actuelle = $resultat2 -> fetch(PDO::FETCH_ASSOC);
		$id_salle_actuelle = $salle_actuelle['id_salle'];
	}
	
	if($salle['id_salle'] == $id_salle_actuelle){
		$option .= '<option selected value="' .$salle['id_salle'] .	'">' . $salle['id_salle'] . ' - ' . $salle['titre'] . ' - ' .$salle['adresse'] . ' - ' .$salle['cp'] . ' - ' .$salle['ville'] . ' - ' . $salle['capacite'] . '</option>';
	}
	else{
		$option .= '<option value="' . $salle['id_salle'] .	'">' . $salle['id_salle'] . ' - ' . $salle['titre'] . ' - ' .$salle['adresse'] . ' - ' .$salle['cp'] . ' - ' .$salle['ville'] . ' - ' . $salle['capacite'] . '</option>';
	}
}

if(isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification')){

	if(isset($_GET['id_produit'])){ // S'il y a un id_produit dans l'url on est dans le cadre d'une modification.

		$resultat = $pdo -> prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
		$resultat -> bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
		$resultat -> execute();

$produit_actuel = $resultat -> fetch(PDO::FETCH_ASSOC);
}

	$id_salle = 		(isset($produit_actuel['id_salle'])) 		? $produit_actuel['id_salle'] : '';
	$id_produit = 		(isset($produit_actuel['id_produit'])) 		? $produit_actuel['id_produit'] : '';
	$salle = 			(isset($produit_actuel['salle'])) 			? $produit_actuel['salle'] : '';
	$date_arrivee = 	(isset($produit_actuel['date_arrivee'])) 	? $produit_actuel['date_arrivee'] : '';
	$date_depart = 		(isset($produit_actuel['date_depart'])) 	? $produit_actuel['date_depart'] : '';
	$prix = 			(isset($produit_actuel['prix'])) 			? $produit_actuel['prix'] : '';
	$etat = 			(isset($produit_actuel['etat'])) 			? $produit_actuel['etat'] : '';

?>

<!-- HTML -->

<html>
	<form action="" method="post" enctype=""> 
	<style>
		form
		{
		margin-left: 500px;
		padding:20px;
		border:1px solid black ;
		border-radius:5px;
		display: inline-block;

		 }
		 label{
		 	color : orange;
		 }
		 input {
		 	border: 1px solid black;
		 	border-radius:4px;
		 }
	</style>

		<input type="hidden" name="id_produit" value="<?= $id_produit ?>">

		<label>Date d'arrivée</label><br>
		<input type="date" name="date_arrivee" value="<?= $date_arrivee ?>"><br><br>
		
		<label>Date de départ</label><br>
		<input type="date" name="date_depart" value="<?= $date_depart ?>"><br><br>
		
		<label>Salle</label><br>
		<select name="id_salle">
			<?= $option ?>
		</select><br><br>

		<label>Tarif</label><br>
		<input type="date" name="prix" value="<?= $prix ?>"><br><br>

		<label>Etat</label><br>
		<select name="etat">
			<option value="<?= $etat='libre' ?>">Libre</option>
			<option value="<?= $etat='reservation' ?>">Réservé</option>
		</select><br><br>

		<input type="submit" value="Enregistrer">
	</form>
</html>

<?php
}
require_once('../inc/bas.inc.php');
?>