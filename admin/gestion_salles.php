<?php  
require_once('../inc/init.inc.php');

/* SUPRESSION */

if (isset($_GET['action']) && $_GET['action'] == 'suppression'){
     $resultat = $pdo -> prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
     $resultat -> bindParam(':id_salle',$_GET['id_salle'],PDO::PARAM_INT);
     $resultat -> execute();
     // Le resultat doit apparaitre sousforme de tableau on fait un PDO::FETCH_ASSOC
      $produit_a_supprimer = $resultat -> fetch(PDO::FETCH_ASSOC);

      $chemin_photo_a_supprimer = $_SERVER['DOCUMENT_ROOT'] . RACINE_SITE . 'img/' . $produit_a_supprimer['photo'];

      unlink($chemin_photo_a_supprimer); // UNlink = fontion qui permet de suprimet un fichier de notre serveur

      $resultat = $pdo -> prepare("DELETE FROM salle WHERE id_salle = :id_salle");
      $resultat -> bindParam(':id_salle',$_GET['id_salle'],PDO::PARAM_INT);
      $resultat -> execute();
      $salle .= '<div class="validation">Le produit <b>'. $_GET['id_salle'] . '</b> a bien été supprimé</div>';
      //$_GET['action'] = 'affichage';
      header('location:?action=affichage');
 }

if(!empty($_POST)){

	/* AJOUT ET MODIFICATION D'UNE SALLE */

	if (isset($_GET['action']) && $_GET['action'] == 'modification') {
        $photo_bdd = $_POST['photo_actuelle'];
    }

    if (!empty($_FILES['photo']['name'])) {
        $photo_bdd = $_POST['id_salle'] . '_' . $_FILES['photo']['name'];

        $photo_dossier = $_SERVER['DOCUMENT_ROOT'] . RACINE_SITE . 'img/' . $photo_bdd;

        copy($_FILES['photo']['tmp_name'], $photo_dossier); // Copie la photo depuis son emplacement temporaire,vers son emplacement definitif.
    }

	if(isset($_GET['action']) && $_GET['action'] == 'ajout'){

		$resultat = $pdo -> prepare("INSERT INTO salle(titre, description, photo, pays, ville, adresse, cp, capacite, categorie) VALUES(:titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)");
	}
	else{
		$resultat = $pdo -> prepare("REPLACE INTO salle(id_salle, titre, description, photo, pays, ville, adresse, cp, capacite, categorie) VALUES(:id_salle, :titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)");

		$resultat -> bindParam(':id_salle',$_POST['id_salle'],PDO::PARAM_INT);
		$salle .= '<div class="validation">Le salle <b>'. $_POST['id_salle'] . '</b> a bien été modifier</div>';
	}
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";

	$resultat -> bindParam(':titre',$_POST['titre'],PDO::PARAM_STR);
	$resultat -> bindParam(':description',$_POST['description'],PDO::PARAM_STR);
	$resultat -> bindParam(':photo',$photo_bdd,PDO::PARAM_STR);
	$resultat -> bindParam(':pays',$_POST['pays'],PDO::PARAM_STR);
	$resultat -> bindParam(':ville',$_POST['ville'],PDO::PARAM_STR);
	$resultat -> bindParam(':adresse',$_POST['adresse'],PDO::PARAM_STR);
	$resultat -> bindParam(':cp',$_POST['cp'],PDO::PARAM_INT);
	$resultat -> bindParam(':capacite',$_POST['capacite'],PDO::PARAM_INT);
	$resultat -> bindParam(':categorie',$_POST['categorie'],PDO::PARAM_STR);

	$resultat -> execute();
	//$salle .= '<div class="validation">Le salle <b>'. $_POST['id_salle'] . '</b> a bien été ajoute</div>';
	header('location:?action=affichage');
}


	/* AFFICHAGE DES SALLES */

	$salle .='<br/><a href="?action=affichage" style="margin:50px;color:orange;font-weight:bold;padding:10px;background:#3B444B;border-radius:3px">Affichage des salles </a><br/><br/>';
	$salle .='<br/><a href="?action=ajout" style="margin:50px;color:orange;font-weight:bold;padding:10px;background:#3B444B;border-radius:3px">Ajout d\'une salle </a><br/><br/><br/><br/>';

if(isset($_GET['action']) && $_GET['action'] == 'affichage'){

	$resultat = $pdo -> query("SELECT * FROM salle");
    $salle .= '<br><h2>Affichage de tous les salles</h2><br>';
    $salle .= '<table class="table table-striped" border="1">';
    $salle .= '<tr>';

    for ($i=0; $i < $resultat -> columnCount(); $i++){
    $meta = $resultat -> getColumnMeta($i);
    $salle .= '<th>' . $meta['name'] . '</th>';
	}

	$salle .= '<th colspan ="2">Action</th>';
	$salle .= '</tr>';

	while ($ligne = $resultat -> fetch(PDO::FETCH_ASSOC)){
	// echo '<pre>';
	// print_r($ligne);
	// echo '</pre>';
	    $salle .= '<tr>';
	    foreach($ligne as $indice => $valeur){
	    	if($indice == 'photo'){
	    		$salle .= '<td><img src="'. RACINE_SITE . 'img/'. $valeur . '"height="80"/></td>';
	    	}
	    	else{
	        	$salle .= '<td>' .$valeur. '</td>';
	        }
	    }


	/* LIENS PICTO */

    $salle .='<td><a href="?action=modification&id_salle='. $ligne['id_salle'] . '"><img src ="' . RACINE_SITE .'img/edit.png" />Modifier </a></td>';
    $salle .='<td><a href="?action=suppression&id_salle=' . $ligne['id_salle'] . '"><img src ="' . RACINE_SITE .'img/delete.png" />Supprimer </a></td>';

    $salle .= '</tr>';
	}

	$salle .= '</table>';
}
require_once('../inc/haut.inc.php');
echo $salle;


if (isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification')){ // Si une action est demandée dans l'URL et que cette action est soit "modification" SOIT "ajout" alors on va afficher le formulaire.

//Modification : j'ai un salle dans l'URL
if(isset($_GET['id_salle'])){ // S'il y a un salle dans l'url on est dans le cadre d'une modification.

	$resultat = $pdo -> prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
	$resultat -> bindParam(':id_salle', $_GET['id_salle'], PDO::PARAM_INT);
	$resultat -> execute();

	// je récupère un array avec toutes les infos du produit à modifier :
	$salle_actuel = $resultat -> fetch(PDO::FETCH_ASSOC);
}
$id_salle = 			(isset($salle_actuel['id_salle'])) 		? $salle_actuel['id_salle'] : '';
$titre =         		(isset($salle_actuel['titre'])) 		? $salle_actuel['titre'] : '';
$description =         	(isset($salle_actuel['description'])) 	? $salle_actuel['description'] : '';
$photo =         		(isset($salle_actuel['photo'])) 		? $salle_actuel['photo'] : '';
$pays =         		(isset($salle_actuel['pays'])) 			? $salle_actuel['pays'] : '';
$ville =         		(isset($salle_actuel['ville'])) 		? $salle_actuel['ville'] : '';
$adresse =         		(isset($salle_actuel['adresse'])) 		? $salle_actuel['adresse'] : '';
$cp =        			(isset($salle_actuel['cp'])) 			? $salle_actuel['cp'] : '';
$capacite =        		(isset($salle_actuel['capacite'])) 		? $salle_actuel['capacite'] : '';
$categorie =        	(isset($salle_actuel['categorie'])) 	? $salle_actuel['categorie'] : '';
$bouton =         		(isset($salle_actuel)) 					? 'Modifier' : 'Enregistrer';

 ?>



<!-- HTML -->

<html>
	<form action="" method="post" enctype="multipart/form-data">
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
		<input type="hidden" name="id_salle" value="<?= $id_salle ?> " />

		<label>Titre</label><br>
		<input type="text" name="titre" value="<?= $titre ?>"><br><br>

		<label>Description</label><br>
		<textarea name="description" cols="30" rows="10"><?= $description?></textarea><br><br>

		<label>Photo</label><br/>
		    <?php
		        if (!empty($photo)){
		            echo '<img src ="' . RACINE_SITE . 'img/' .$photo. '" height="80"/> ';
		        }
		    ?>
   		<input type="hidden" name="photo_actuelle" value="<?= $photo ?>" /> <br/><br/>
		<input type="file" name="photo"><br><br>

		<label>Capacité </label>
		<input type="text" name="capacite" value="<?= $capacite ?>" /> <br><br>

		<label>Catégorie </label><br>
		<select name="categorie">
			<option <?php if($categorie == 'bureau'){echo 'selected';} ?>>Bureau</option>
			<option <?php if($categorie == 'reunion'){echo 'selected';} ?>>Réunion</option>
			<option <?php if($categorie == 'formation'){echo 'selected';} ?>>Formation</option>
		</select><br><br>

		<label>Pays</label><br>
		<select name="pays">
			<option <?php if($pays == 'france'){echo 'selected';} ?> >France</option>
			<option <?php if($pays == 'belgique'){echo 'selected';} ?> >Belgique</option>
		</select><br><br>

		<label>Ville</label><br>
		<select name="ville">
			<option <?php if($ville == 'paris'){echo 'selected';} ?> >Paris</option>
			<option <?php if($ville == 'lyon'){echo 'selected';} ?> >Lyon</option>
		</select><br><br>

		<label>Adresse</label><br>
		<input type="text" name="adresse" value="<?= $adresse ?>" /><br><br>

		<label>Code postal</label>
		<input type="text" name="cp" value="<?= $cp ?>"><br><br>

		<input type="submit" value="Enregistrer" value="<?= $bouton ?>">
	</form>
</html>

<?php
}
require_once('../inc/bas.inc.php');
?>