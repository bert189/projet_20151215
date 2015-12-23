
<?php

require_once 'inc/connexion.php';
include_once 'inc/header.php';

$reqArt = $bdd->prepare('SELECT * FROM articles WHERE id = :id');
$reqArt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
if($reqArt->execute()){
	$article = $reqArt->fetch(PDO::FETCH_ASSOC);
}

$erreurs = array();
$formValid = false;

if(!empty($_POST)){
	foreach ($_POST as $key =>$value){
		$post[$key] = trim(strip_tags($value));
	}
	if(empty($post['pseudo'])){
		$erreurs[] = 'Veuillez entrer votre pseudo';
	}
	if(empty($post['newcomment'])){
		$erreurs[] = 'Veuillez entrer un commentaire';
	}
	if(count($erreurs) == 0){
		$reqUse = $bdd->prepare('INSERT INTO users (nickname, date_registred) VALUES (:nickname, NOW())');
		$reqUse->bindValue(':nickname', $post['pseudo'], PDO::PARAM_STR);
		$reqUse->execute();

		$id_user = $bdd->lastInsertId();
		// permet d'obtenir l'id de la dernière entrée
			
		$reqCom = $bdd->prepare('INSERT INTO comments (comment, id_article, id_user, date) VALUES (:comment, :id_article, :id_user, NOW())');
		$reqCom->bindValue(':comment', $post['newcomment'], PDO::PARAM_STR);
		$reqCom->bindValue(':id_article', $article['id'], PDO::PARAM_INT);
		$reqCom->bindValue('id_user', $id_user, PDO::PARAM_INT);
		if($reqCom->execute()){
			$formValid = true;	
		}			
	}
}

?>

<div class="container">	
	<main>
		<form action="" method="POST">
			<h1>Ajouter un commentaire à l'article : <?php echo $article['title']; ?></h1>
			<label for="pseudo">Votre Pseudo :</label>
			<input type="text" id="pseudo" name="pseudo" placeholder="pseudo...">
			<label for="newcomment">Nouveau Commentaire :</label>
			<textarea name="newcomment" id="newcomment" cols="30" rows="10" placeholder="commentaire..."></textarea>
			<input type="submit" value="Envoyer Votre Commentaire">
			<?php
			if (count($erreurs) > 0){?>
				<p style="color: red"><?php echo implode('<br>', $erreurs); ?></p>
			<?php
			}
			else if($formValid){?>
				<p style="color: green">Votre commentaire a bien été pris en compte</p>		
				<a href="affichage_article.php?id=<?php echo $article['id']; ?>">Revenir à l'article</a>
			<?php } ?>
		</form>	
	</main>
<div/>	


<?php include_once 'inc/footer.php';?>