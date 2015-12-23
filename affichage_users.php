<?php

require_once 'inc/connexion.php';
include_once 'inc/header.php';

$bdd = new PDO('mysql:host=localhost;dbname=exo_blog_heineken;charset=utf8', 'root', '');

if (isset($_GET['recherche']) && !empty($_GET['recherche'])){
	$recherche = trim($_GET['recherche']);
	$reqUsers = $bdd->prepare('SELECT * FROM users WHERE nickname LIKE :recherche ORDER BY date_registred DESC');
	$reqUsers->bindValue(':recherche', '%'.$recherche.'%', PDO::PARAM_STR);
	if ($reqUsers->execute()){
		$users = $reqUsers->fetchALL(PDO::FETCH_ASSOC);
	}
}
else{
	$reqUsers = $bdd->prepare('SELECT * FROM users ORDER BY date_registred DESC');
	if ($reqUsers->execute()){
		$users = $reqUsers->fetchALL(PDO::FETCH_ASSOC);
	}
}

?>


<div class="container">
	<main>
		<form action="" method="GET">
			<label for="recherche">Rechercher un Utilisateur</label>
			<input class="search" type="text" id="recherche" name="recherche" placeholder=" entrez votre recherche...">
			<input class="search" type="submit" value="OK">
		</form>	
		<main>
		<?php
		if(!empty($users)){
			foreach($users as $user){ ?>
				<article>
					<h2><?php echo $user['nickname']; ?></h2>
					<p><strong>enregistré depuis le : </strong><?php echo date('d/m/Y à H:i', strtotime($user['date_registred'])); ?></p>							
				</article>

		<?php }
		}
		else{
			echo '<p style="color: red">Votre recherche "'.$recherche.'" n\'a rien donné</p>';
		}?>
	</main>
<div/>	


<?php include_once 'inc/footer.php'; ?>

