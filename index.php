<?php 

	try{

	    $bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}
	catch (Exception $e){

	        die('Erreur : ' . $e->getMessage());
	}

	// On récupère le numéro de la page avec $_GET['page']
	// Avec ceci, on pourra changer le 1er paramètre de LIMIT qui définit l'ID où commencer à lire la bdd
	// Pour ça, on prend simplement le numéro de page - 1 et on multiplie par le nombres de billets par pages
	// ici, 5. Exemple : (4 - 1) * 5 = LIMIT 15 --> à la 4ème page, on commencera à LIMIT 15 (donc id 16 !)

	if (isset($_GET['page'])) {
		$no_page = $_GET['page'];

		$no_limit = ($no_page -= 1) * 2;
		
	} else{
		location('index.php');
	}


	//On va chercher les 5 derniers billets dans la bdd
	$reponse = $bdd->query("SELECT id, titre, contenu, DATE_FORMAT(date_creation, '%d/%m/%Y à %Hh%i') AS date_creation_fr FROM billets ORDER BY id DESC LIMIT 0, 5");

?>


<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Blog</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

	<h1>Mon Super Blog !</h1>

	<p>Derniers billets du blog :</p>

	<div class="news">
	
<?php 
	while ($donnees = $reponse->fetch()){ 	
?>
		<h3><?php echo $donnees['titre'] . " le " . $donnees['date_creation_fr'] ?></h3>
		<p><?php echo nl2br(htmlspecialchars($donnees['contenu'])) ?></p>
		<!-- Dans le lien ci-dessous, on indique dans l'URL l'ID de l'article défini dans la table 'billets' -->
		<a href="commentaires.php?billet=<?php echo $donnees['id'] ?>">Commentaires</a>
<?php 
		} 

	$reponse->closeCursor();
?>
	<br>

<?php include('pages.php') ?>
	

	</div>
	
</body>
</html>