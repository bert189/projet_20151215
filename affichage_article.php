<?php 

require_once 'inc/connexion.php'; // inclut la connexion à la base de donnée
include_once 'inc/header.php';

$reqArt = $bdd->prepare('SELECT * FROM articles WHERE id = :id');
$reqArt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
if($reqArt->execute()){
	$article = $reqArt->fetch(PDO::FETCH_ASSOC);
}

?>

<div class="container">	
	<main>
	<?php
	if (!empty($article)){?>
		<article>
			<h1><?php echo $article['title'] ?></h1>
			<img src="<?php echo $article['url_img'] ?>" alt="">
			<p><?php echo $article['content'] ?></p>
			<br>
			<p><strong>Commentaires :</strong></p>
			<?php 
			$reqCom = $bdd->prepare('SELECT * FROM comments WHERE id_article = :id_article');
			$reqCom->bindValue(':id_article', $article['id'], PDO::PARAM_INT);
			if ($reqCom->execute()){
				$comment = $reqCom->fetchAll(PDO::FETCH_ASSOC);
			}
			foreach($comment as $com){
				$reqUse = $bdd->prepare('SELECT * FROM users WHERE id = :id');
				$reqUse->bindValue(':id', $com['id_user'], PDO::PARAM_INT);
				if($reqUse->execute()){
					$user = $reqUse->fetch(PDO::FETCH_ASSOC);
				}?>
				<p><em>écrit le </em><?php echo $com['date'] ?><em> par </em><strong><?php echo $user['nickname'] ?></strong> :</p>
				<p class="comment"><?php echo $com['comment'] ?></p>								
				<br>
			<?php } ?>
			<a href="ajout_commentaire.php?id=<?php echo $article['id']; ?>">ajouter un commentaire...</a>
		</article>
	<?php } ?>
	</main>
<div/>	


<?php include_once 'inc/footer.php';?>