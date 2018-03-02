<?php
	//On compte le nombre de billets total
	$reponse_page = $bdd->query("SELECT COUNT(*) AS nb_billets FROM billets");
	$donnees_page = $reponse_page->fetch();

	$nb_de_billet_par_page = 2;

	$numero_page = ceil($donnees_page['nb_billets']/$nb_de_billet_par_page);
?>
	Pages :
<?php
	for($i = 1; $i <= $numero_page; $i++) { 	
?>
		<a href="index.php?page=<?php echo $i ?>"><?php echo $i ?></a>
<?php	
	}

	$reponse_page->closeCursor();
?>