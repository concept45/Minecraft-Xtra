<?php
if (isset($_SESSION['pseudo']))
{
  ?>
<div class="menu_left">
<div class="content_menu">
	<div class="titre_menu">Menu utilisateur</div>
<p>Vous êtes connecté <?php echo $_SESSION['pseudo'];?></p>
<p><div id="avatar">
<?php avatar($_SESSION['pseudo']);
echo '<img src="./avatars/'.$_SESSION['pseudo'].'.png" />';
 ?>
 </div></p>
<p>
<?php 
echo ''.$monnaie.' : ';
$req=connecticonomy($_SESSION['pseudo']);
compte($req);
?></p>
<p><a href="?page=deconnexion";>Déconnexion</a>

<?php
}
else
{
?>


<div class="menu_left">

<div class="content_menu">
	<div class="titre_menu">Menu utilisateur</div>


	<form action="?page=connexion" method="post">
	<p>Pseudo :
    <input type="text" name="pseudo" /></br>
	Mot de passe :
	<input type="password" name="mdp"/></br>
    <input type="submit" value="Connexion" /></br>
	</p>
	</form>
	<a href ="?page=inscription">Inscription</a>
	<?php
}
	?>
	</div>
	
<div class="content_menu">
	<div class="titre_menu">Top contributor</div>
	<?php
	global $bdd;
	$req = $bdd->query('SELECT * FROM users ORDER BY dons DESC LIMIT 0,5');
	$chiffre=0;
	while ($donnees = $req->fetch())
	{
	$chiffre++;
	?>
	<p><?php echo ''.$chiffre.'.'.$donnees['pseudo'].' '.$donnees['dons'].''; }?></p>
	</div>
	
</div>