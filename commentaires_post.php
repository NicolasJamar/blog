<?php 
	try{

	    $bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}
	catch (Exception $e){

	        die('Erreur : ' . $e->getMessage());
	}

	//Depuis le formulaire on récupère les $_POST et le numéro d'id du billet de l'URL avec $_GET
	if(isset($_POST['auteur']) AND isset($_POST['comment'])){
		$id_billet = $_GET['billet'];
		$auteur = htmlspecialchars($_POST['auteur']);
		$commentaire = htmlspecialchars($_POST['comment']);

	//On les injecte dans la bdd via une request préparée
	$req = $bdd->prepare("INSERT INTO commentaires(id_billet, auteur, commentaire, date_commentaire) VALUES(:id_billet, :auteur, :commentaire, NOW())");
	$req->execute(array(
		"id_billet" => $id_billet,
		"auteur" => $auteur,
		"commentaire" => $commentaire
		//la date est déjà mise avec 'NOW()'
	));

	// Puis rediriger vers commentaires.php comme ceci :
	header('Location: commentaires.php?billet=' . $_GET['billet']);

	}

	$req->closeCursor();


 ?>