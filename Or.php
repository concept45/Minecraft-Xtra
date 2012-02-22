<?php
	if (isset($_SESSION['pseudo']))
	{
	if ($_SESSION['rang'] >= 1)
	{
	if (isset($_GET['code']))
	{
try
{
$bdd = database();
	
    $req = $bdd->prepare('SELECT * FROM allopass WHERE code = ?');
	$req->execute(array($_GET['code']));

$nb_ligne = $req->rowCount();

if ($nb_ligne ==0)
{
    
    // On change les donn�es 
$reqe = $bdd->prepare('INSERT INTO allopass(code, pseudo) VALUES (?,?)');
$reqe->execute(array($_GET['code'], $_SESSION['pseudo']));

$reqs = $bdd->prepare('UPDATE users SET dons=dons+1 WHERE pseudo = ?');
$reqs->execute(array($_SESSION['pseudo']));
    // On change les donn�es 
$reqa = $bdd->prepare('UPDATE iConomy SET balance=balance+? WHERE username = ?');
$reqa->execute(array($dons,$_SESSION['pseudo']));
echo"Vous avez bien effectu� le don";
}
else
{
echo 'code deja utlis�';
}
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
}
	else
	{
    if ($allopass)
{
	?>
<div id="achat">
	<!-- Begin Allopass Checkout-Button Code -->
<?php 
echo $allopass ;
?>
<!-- End Allopass Checkout-Button Code -->
<p>Recevez <?php echo ''.$dons.' '.$monnaie.'';?> pour tous achat !!! </p>
</div>

<?php
}
try
{
echo '</br></br><h2>Envoie d\'argent</h2></br></br>';
$bdd = database();
listuser($_SESSION['pseudo'], $bdd);
?>
Somme : <input class='chiffre' type="text" name="somme" /></br>
    <input type="submit" value="Envoyer" /></br>
</form>
<?php
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
?>
<?php

}
}
else
{
echo 'Votre compte n\'a pas encore �t� confirm�';
}
}
else
{
	echo 'Vous devez vous enregistrer pour aller dans cette section';
}
