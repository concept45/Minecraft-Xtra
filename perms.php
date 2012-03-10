<?php

global $bdd;



$req = $bdd->prepare('SELECT * FROM prachat WHERE permissions NOT IN(SELECT permission FROM permissions WHERE name = ?)');
$req->execute(array($_SESSION['pseudo']));
echo 'Permissions a acheter :</br></br>';

while ($donnee = $req->fetch())
{
echo ''.$donnee['permissions'].'</br>';
echo 'Prix :'.$donnee['prix'].' Or. <a href =?page=achatperm&perm='.$donnee['permissions'].'&prix='.$donnee['prix'].'>Acheter maintenant</a></br></br>';
}

$req = $bdd->prepare('SELECT * FROM permissions WHERE name = ?');
$req->execute(array($_SESSION['pseudo']));


echo'</br></br>Liste des permissions possedés :</br></br>';
while ($donnee = $req->fetch())
{
echo ''.$donnee['permission'].'</br>';
}

	


?>
