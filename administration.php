<?php

if ($_GET['admin'] == 'changeinfo')
{
?>
<form action="?admin=changeinfoserveur" method="post">
<label for="monnaie">Nom de la monnaie : </label>
<input type="text" name="monnaie" value="<?php echo $monnaie;?>"></br>
<label for="ip">Adresse ip :</label>
<input type="text" name="ip" value="<?php echo $ip;?>"></br>
<label for="dons">Monnaie donnée pour un don :</label>
<input type="text" name="dons" value="<?php echo $dons?>"></br>
<label for="allopass">Code allopass (vide pour désactiver) :</label>
<input type="text" name="allopass" value='<?php echo $allopass ?>'></br></br>


<input type="submit" value="Changer">
</form>

<?php
}


elseif ($_GET['admin'] == 'changeinfoserveur')
{

if (isset($_POST['monnaie']))
{
$reqs = $bdd->prepare('UPDATE config SET valeur = ? WHERE nom = \'monnaie\'');
$reqs->execute(array($_POST['monnaie'],));
echo 'Monnaie changé avec succès ! </br>';
}
if (isset($_POST['ip']))
{
$reqs = $bdd->prepare('UPDATE config SET valeur = ? WHERE nom = \'ip\'');
$reqs->execute(array($_POST['ip'],));
echo 'Adresse ip changé avec succès !</br>';
}
if (isset($_POST['dons']))
{
$reqs = $bdd->prepare('UPDATE config SET valeur = ? WHERE nom = \'dons\'');
$reqs->execute(array($_POST['dons'],));
echo 'Dons changé avec succès !</br>';
}

if (isset($_POST['allopass']))
{
$reqs = $bdd->prepare('UPDATE config SET valeur = ? WHERE nom = \'allopass\'');
$reqs->execute(array($_POST['allopass'],));
echo 'Allopass changé avec succès !</br>';
}

echo '<a href="index.php">Acceuil</a>';
}

elseif ($_GET['admin'] == 'acceuil')
{
?>

<form action="?admin=changeacceuil" method="post">
Modifier la page d'acceuil (code html) : </br>
<textarea name="acceuil" rows="10" cols="50"><?php echo $acceuil; ?></textarea></br>
<input type="submit" value="Modifier">


</form>

<?php
}


elseif ($_GET['admin'] == 'changeacceuil')
{
{
$req = $bdd->prepare('UPDATE config SET valeur = ? WHERE nom = \'acceuil\'');
$req->execute(array($_POST['acceuil']));
echo 'Page d\'acceuil changé avec succès ! </br> <a href="index.php">Acceuil</a>';
}
}

elseif ($_GET['admin'] == 'membres')
{
echo'Administrateurs : </br></br>';
listrang(2);echo'</br>';

echo'Membres validés : </br></br>';
listrang(1);echo'</br>';

echo'Membres non validés :</br></br>';
listrang(0);echo'</br>';


}

elseif ($_GET['admin'] == 'menu')
{
listmenus(0);
listmenus(1);
echo'</br></br></br>';
?>
<form action="?admin=ajouter" method="post">
Nom du menu :</br> </br>
<input type="text" name="nom" value="<?php echo $nomexterne;?>"></br></br>
Adresse du lien externe :(laisser vide pour désactiver)</br> </br>
<input type="text" name="externe" value="<?php echo $externe;?>">
<input type="submit" value="Changer">

</form>
<?php
}

elseif ($_GET['admin'] == 'activer')
{
$req = $bdd->prepare('UPDATE menus SET valeur=1 WHERE nom=?');
$req->execute(array($_GET['nom']));
echo '<a href="?admin=menu">Retour gestion menu</a>';
}
elseif ($_GET['admin'] == 'desactiver')
{
$req = $bdd->prepare('UPDATE menus SET valeur=0 WHERE nom=?');
$req->execute(array($_GET['nom']));
echo '<a href="?admin=menu">Retour gestion menu</a>';
}


elseif ($_GET['admin'] == 'ajouter')
{
$req = $bdd->prepare('UPDATE menus SET valeur=?, valeur2=? WHERE nom = \'externe\'');
$req->execute(array($_POST['externe'],$_POST['nom']));

echo '<a href="?admin=menu">Retour gestion menu</a>';
}

elseif ($_GET['admin'] == 'perms')
{
echo 'Permission en vente : </br></br>';
$req = $bdd->query('SELECT * FROM prachat');

while ($donnee = $req->fetch())
{
echo ''.$donnee['permissions'].' <a href="?admin=enleverperms&nom='.$donnee['permissions'].'">Supprimer</a></br>';
echo 'Prix : '.$donnee['prix'].'</br></br>';
}

echo'</br></br></br>';
echo'Ajouter une permission : </br></br>
<form action="?admin=ajouterperms" method="post">
<label for="nom">Nom de la permission : </label>
<input type="text" name="nom"></br>
<label for="prix">Prix : </label>
<input type="text" name="prix"></br>
<input type="submit" value="Ajouter">
</form>
';
}

elseif ($_GET['admin'] == 'ajouterperms')
{
$bdd = database();
$req = $bdd->prepare('INSERT INTO prachat(permissions, prix) VALUES(?, ?)');
$req -> execute(array($_POST['nom'],$_POST['prix']));
echo 'Permission ajouté avec succès</br>';
echo '<a href="?admin=perms">Retour gestion permissions</a>';
}

elseif ($_GET['admin'] == 'enleverperms')
{
$bdd = database();
$req = $bdd->prepare('DELETE FROM prachat WHERE permissions=?');
$req -> execute(array($_GET['nom']));
echo 'Permission enlevé avec succès</br>';
echo '<a href="?admin=perms">Retour gestion permissions</a>';
}

else
{ ?>
<a href="?admin=changeinfo">Changer les infos du serveur</a></br>
<a href="?admin=menu">Modifier menu</a></br>
<a href="?admin=acceuil">Modifier page d'acceuil</a></br>
<a href="?admin=perms">Gérer permissions à acheter</a></br>
<a href="?admin=membres">Gérer les membres</a></br>

<?php }

?>
