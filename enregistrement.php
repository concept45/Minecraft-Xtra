<?php
$pseudo = htmlspecialchars($_POST['pseudo']);
$mdp = htmlspecialchars($_POST['mdp']);
$cmdp = htmlspecialchars($_POST['cmdp']);
$email = htmlspecialchars($_POST['email']);

global $bdd;
inscription ($pseudo, $mdp, $cmdp, $email);

	

?>
