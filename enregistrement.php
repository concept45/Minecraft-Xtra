<?php
$pseudo = htmlspecialchars($_POST['pseudo']);
$mdp = htmlspecialchars($_POST['mdp']);
$cmdp = htmlspecialchars($_POST['cmdp']);
$email = htmlspecialchars($_POST['email']);

try
{
$bdd = database();
$req = connexion($pseudo, $bdd);
inscription ($bdd, $pseudo, $mdp, $cmdp, $email);
}
	catch (Exception $e)
	{
        die('Erreur : ' . $e->getMessage());
	}
	

?>