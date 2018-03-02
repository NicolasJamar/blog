<?php 

	try{

	    $bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}
	catch (Exception $e){

	        die('Erreur : ' . $e->getMessage());
	}

	$req = $bdd->prepare("SELECT id, titre, contenu, DATE_FORMAT(date_creation, '%d/%m/%Y à %Hh%i') AS date_creation_fr FROM billets WHERE id = ? ");
	// '?' est un marqueur
	$req->execute(array($_GET['billet'])); //on récupère la valeur de l'ID dans l'URL (via le nom 'billet'), elle va automatiquement se mettre sur le marqueur. 
	//On aurait pu utiliser un marqueur nominatif, comme 'id = :billet' puis 'billet => $_GET['billet']'
	$donnees = $req->fetch();

	//On affiche la page si $donnees a du contenu, sinon, on renvoie un message d'erreur si le billet n'existe pas !
	if(!EMPTY($donnees)){

 ?>


<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Commentaires du blog</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

	<a href="index.php">Retour à la liste des billets</a>

	<div class="news">
	<!-- On affiche les billets  -->
		<h3><?php echo $donnees['titre'] . " le " . $donnees['date_creation_fr'] ?></h3>
		<p><?php echo nl2br(htmlspecialchars($donnees['contenu'])) ?></p>
	
	<?php
		$req->closeCursor();
	?>
	</div>

	<h2>Commentaires</h2>
	<!-- ! il faut transmettre l'id vers la page commentaire_post.php pour pour la récupérée avec $_GET !  -->
	<form action="commentaires_post.php?billet=<?php echo $donnees['id'] ?>" method="POST">
			Pseudo : <input type="text" name="auteur">
			<br>
			<textarea type="text" name="comment" rows="3" cols="30"></textarea>
			<br>
			<input type="submit" value="Ajouter un commentaire">
	</form>

	
	<?php 
		// ! on ne se connecte à la base de données qu'une fois par page
		$req = $bdd->prepare("SELECT id, id_billet, auteur, commentaire, DATE_FORMAT(date_commentaire, '%d/%m/%Y à %Hh%i') AS date_commentaire_fr FROM commentaires WHERE id_billet=? ORDER BY date_commentaire");
		$req->execute(array($_GET['billet'])); //on va chercher la valeur de 'billet' défini dans l'URL
		
		while ($donnees = $req->fetch()) {
	?>

		<p><strong><?php echo $donnees['auteur']?></strong> le <?php echo $donnees['date_commentaire_fr'] ?> :</p>
		<p><?php echo $donnees['commentaire'] ?></p>
		<br>		
	<?php	
		}
	
	}else{
		echo "Le billet " . $_GET['billet'] . " est vide !";
	}

	$req->closeCursor();
	?>

</body>
</html>