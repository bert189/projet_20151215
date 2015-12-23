<?php 

require_once 'inc/connexion.php';
include_once 'inc/header.php';

$query = $bdd->prepare('SELECT * FROM articles');
if ($query->execute()){
	$articles = $query->fetchALL(PDO::FETCH_ASSOC);
};

?>

<div class="container">	
	<main>
	<?php
	if(!empty($articles)){
		foreach($articles as $article){ ?>
			<article>
				<div class="extrait-image">
					<img src="<?php echo $article['url_img'] ?>" alt="">
				</div>
				<div class="extrait-article">
					<h2><?php echo $article['title']; ?></h2>
					<p><?php echo mb_substr($article['content'], 0, 200)."..."; ?></p>
					<a href="affichage_article.php?id=<?php echo $article['id']; ?>">article complet...</a>
				</div>			
			</article>

	<?php }
	} ?>
	<div class="ajout">
		<a href="ajout_article.php">AJOUTER UN ARTICLE</a>
	</div>
	<div class="ajout">
		<a href="affichage_users.php">AFFICHER LES UTILISATEURS</a>
	</div>
	</main>
<div/>	




<?php include_once 'inc/footer.php';?>
