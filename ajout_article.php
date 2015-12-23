<?php

require_once 'inc/connexion.php';
include_once 'inc/header.php';

$formValid = false;
$erreurs = array();

$maxSize = 1024 * 1000;
$mimeTypeAllowed = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif');


if(!empty($_POST)){
	foreach ($_POST as $key =>$value){
		$post[$key] = trim(strip_tags($value));
	}
	if(empty($post['newtitle'])){
		$erreurs[] = 'Veuillez entrer un titre';
	}
	// if(empty($post['newurl'])){
	// 	$erreurs[] = 'Veuillez entrer une url d\'image';
	// }
	if(empty($_FILES['newimage']['size'])){
		// par défaut, sans avoir envoyé de fichier, $_FILES éxiste mais est vide
		$erreurs[] = 'L\'image ne peut être vide'; 
	}
	elseif($_FILES['newimage']['size'] > $maxSize){
		$erreurs[] = 'L\'image est trop lourde';
	}
	// in_array(valeur, tableau) : cherche uen valeur dans un tableau
	// vérifiera que le MIME type de l'image correspond à ceux autorisés + haut
	// elseif(!in_array($_FILES['newimage']['type'], $mimeTypeAllowed)){ // pas ultra securisé
	$fileMimeType = $finfo->file($_FILES['newimage']['tmp_name'], FILEINFO_MIME_TYPE);
	elseif(!in_array($fileMimeType, $mimeTypeAllowed)){
		$erreurs[] = 'Le fichier n\'est pas une image';
	}
	elseif(isset($_FILES['newimage'])){
		$chemin = 'upload/';
		$nomFichier = $_FILES['newimage']['name']; // Récupère le nom du fichier
		
		$search = array(' ', 'é', 'è', 'à', 'ù'); 
		$replace = array('-', 'e', 'e', 'a', 'u'); // array complets sur Slack (par Geoffroy)

		$nomFichier = str_replace($search, $replace, time().'-'.$nomFichier);
		// fichier nettoyé des espaces et accents

		$tmpFichier = $_FILES['newimage']['tmp_name']; // stockage temporaire du fichier

		$newUrlImage = $chemin.$nomFichier;

		if(move_uploaded_file($tmpFichier, $newUrlImage)){
				$success = 'Fichier envoyé ! \o/';
		}
	}
	else{
		$erreurs[] = 'Veuillez ajouter une image';
	}

	if(empty($post['newcontent'])){
		$erreurs[] = 'Veuillez entrer un contenu';
	}
	if(count($erreurs) == 0){
		$reqArt = $bdd->prepare('INSERT INTO articles (title, url_img, content, date ) VALUES (:title, :url_img, :content, NOW())');
		$reqArt->bindValue(':title', $post['newtitle'], PDO::PARAM_STR);
		// $reqArt->bindValue(':url_img', $post['newurl'], PDO::PARAM_STR);
		$reqArt->bindValue(':url_img', $newUrlImage, PDO::PARAM_STR);
		$reqArt->bindValue(':content', $post['newcontent'], PDO::PARAM_STR);
		
		if($reqArt->execute()){
			$formValid = true;
		}			
	}
}

?>

<div class="container">
	<main>
		<!-- attribut enctype pour accepter les fichiers -->
		<form action="" method="POST" enctype="multipart/form-data">
			<!-- limitation de la taille des fichiers uploader à 1Mo --> 
			<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxSize ?>">

			<h1>Ajouter un nouvel article :</h1>
			<label for="newtitle">Titre de l'article :</label>
			<input type="text" id="newtitle" name="newtitle" placeholder="titre...">
			
			<!-- <label for="newurl">Url de l'image de l'article :</label>
			<input type="text" id="newurl" name="newurl" placeholder="url..."> -->
			<!-- champ type file : -->
			<label for="newurl">Choisir une image :</label>
			<input type="file" name="newimage">

			<label for="newcontent">Contenu de l'article :</label>
			<textarea name="newcontent" id="newcontent" cols="30" rows="10" placeholder="contenu..."></textarea>
			<input type="submit" value="Poster votre Nouvel Article">
			<?php
			if (count($erreurs) > 0){?>
				<p style="color: red"><?php echo implode('<br>', $erreurs); ?></p>
			<?php
			}
			else if($formValid){?>
				<p style="color: green">Votre article a bien été ajouté</p>
			<?php } ?>
		</form>	
	</main>
<div/>	


<?php include_once 'inc/footer.php';?>


