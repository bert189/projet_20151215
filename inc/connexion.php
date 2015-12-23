<?php 

try{
	$bdd = new PDO('mysql:host=localhost;dbname=exo_blog_heineken;charset=utf8', 'root', '');
}

catch(PDOExeption $e){
	die('Erreur de connexion à MySQL : '.$e->getMessage());
}