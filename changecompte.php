<?php
$newmdp = htmlspecialchars($_POST['newmdp']);
$cnewmdp = htmlspecialchars($_POST['cnewmdp']);
$newemail = htmlspecialchars($_POST['newemail']);

if ($newmdp == $cnewmdp)
{
$newmdp=hachage($newmdp);
try
{
$bdd = database();
if (isset($newmdp) AND !empty($_POST['newmdp']))
{
$req = $bdd->prepare('UPDATE users SET pass = :newmdp WHERE pseudo = :pseudo');
$req->execute(array(
    'newmdp' => $newmdp,
    'pseudo' => $_SESSION['pseudo']
    ));
echo 'Mot de passe modifié avec succès. </br>';
}

if (isset($newemail) AND !empty($newemail))
{
$req = $bdd->prepare('UPDATE users SET email = :email WHERE pseudo = :pseudo');
$req->execute(array(
    'email' => $newemail,
    'pseudo' => $_SESSION['pseudo']
    ));
echo 'E-mail changé avec succès. </br>';
}


}
	catch (Exception $e)
	{
        die('Erreur : ' . $e->getMessage());
	}
}
else
{
echo "Les mots de passe ne sont pas identique.";
}
?>