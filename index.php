<?php
	try
{
if (file_exists("config.php")) { //si l'installation a été éffectué
include("fonctions.php");
include_once("class/membre.class.php");
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$bdd = database();

session_start(); // On ouvre la session
$monnaie = infoserveur('monnaie'); //on récupère les infos
$ip = infoserveur('ip');
$dons = infoserveur('dons');
$allopass = infoserveur('allopass');
$acceuil = infoserveur('acceuil');
$perms = infomenus('perms');
$textures = infomenus('textures');
$menu_monnaie = infomenus('monnaie');
$externe = infomenus('externe');
$nomexterne = infomenus2('externe');

$membre = new membre();
$membre->setPseudo($_SESSION['pseudo']);

?>
<!DOCTYPE html>
<html>
   <head>
       <title>HappyWorld Xtra</title>
       <meta charset="utf-8" />
       <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
   </head>
   <body>
<div id="header_bg">
<div id="connexion"><h3>Etat du serveur :<?php etat();?></h3></div>
<div id="logo"></div>
<div id ="version">Beta 1.1</div>
<div id="achev"><p>Adresse ip :</p><p><?php echo $ip;?></p></div>
</div>
<nav>
<ul>
<li><a href="index.php">Acceuil</a></li>
<?php if ($perms == 1){echo'<li><a href="?page=perms">Perms</a></li>';}
if ($textures == 1){echo'<li><a href="?page=textures">Textures</a></li>';}
if ($menu_monnaie == 1){echo'<li><a href="?page=or">'.$monnaie.'</a></li>';}
echo '<li><a href="?page=compte">Compte</a></li>';
	if (isset($_SESSION['pseudo']))
	{
		if ($_SESSION['rang'] == 2)
		{
        echo'<li><a href="?page=administration">Administration</a></li>';
        }
    }
if (!empty($externe)){echo'<li><a href="'.$externe.'">'.$nomexterne.'</a></li>';}
?>
</ul>
</nav>

<?php include("menu_left.php"); // On inclut le menu de gauche

?>
<div id="page">
<?php
if (isset($_GET['page'])) // Si la variable $_GET['page'] existe
{
	if ($_GET['page'] == 'connexion'&&isset($_POST['pseudo'])) //Si le membre se connecte
	{
	$_POST['pseudo'] = htmlspecialchars($_POST['pseudo']);
    $req=connexion($_POST['pseudo'], $bdd);
	$connexion= ligne($req);
	ident($connexion,$req);
	
	}

	if ($_GET['page'] == 'deconnexion') //Si le membre se déconnecte
	{
	session_destroy();
	header('Location: index.php');
	}
if ($_GET['page'] == 'perms') //page des permissions
{
	if (isset($_SESSION['pseudo']))
	{
		if ($_SESSION['rang'] >= 1)
		{
        include"perms.php";
		}
		else
		{
		echo 'Votre compte n\'a pas encore été confirmé';
		}
	}
	else
	{
	echo 'Vous devez vous enregistrer pour aller dans cette section';
	}
}
if ($_GET['page'] == 'achatperm') //Acheter des permissions
{
	if (isset($_SESSION['pseudo']))
	{
		if ($_SESSION['rang'] >= 1)
		{
        include"achatperm.php";
		}
		else
		{
		echo 'Votre compte n\'a pas encore été confirmé';
		}
	}
	else
	{
	echo 'Vous devez vous enregistrer pour aller dans cette section';
	}
}
if ($_GET['page'] == 'achatpermok')//Achat permissions ok
{
echo'Achat de la permission réussi !';
}
	if ($_GET['page'] == 'inscription') //page d'inscription
	{
	include("inscription.php");
	}
	if ($_GET['page'] == 'confirm')
	{
    if ($_GET['action'] == 'augmente')
    {
    $req = $bdd->prepare('UPDATE users SET rang = rang+1 WHERE pseudo = ?');
    $req->execute(array($_GET['pseudo']));
    echo 'L\'utilisateur '.$_GET['pseudo'].' a été gradé';
    }
    if ($_GET['action'] == 'diminue')
    {
    $req = $bdd->prepare('UPDATE users SET rang = rang-1 WHERE pseudo = ?');
    $req->execute(array($_GET['pseudo']));
    echo 'L\'utilisateur '.$_GET['pseudo'].' a été dégradé';    
    }

    }
	if ($_GET['page'] == 'enregistrement') //traitement enregistrement
	{
	include("enregistrement.php");
	}
	if ($_GET['page'] == 'envoior')
	{
	include("envoior.php");
	}
    
    
	if ($_GET['page'] == 'compte')
    {

    if (isset($_SESSION['pseudo']))
	{
	if ($_SESSION['rang'] >= 1)
		{
    include("compte.php");
		}
		else
		{
		echo 'Votre compte n\'a pas encore été confirmé';
		}
	}
	else
	{
	echo 'Vous devez vous enregistrer pour aller dans cette section';
	}
}

	if ($_GET['page'] == 'changecompte')
    {

    if (isset($_SESSION['pseudo']))
	{
	if ($_SESSION['rang'] >= 1)
		{
    include("changecompte.php");
		}
		else
		{
		echo 'Votre compte n\'a pas encore été confirmé';
		}
	}
	else
	{
	echo 'Vous devez vous enregistrer pour aller dans cette section';
	}
}


	if ($_GET['page'] == 'administration')
    {

    if (isset($_SESSION['pseudo']))
	{
	if ($_SESSION['rang'] == 2) //Seul les admins peuvent acceder a la page
		{
    include("administration.php");
		}
		else
		{
		echo 'Votre compte n\'a pas encore été confirmé';
		}
	}
	else
	{
	echo 'Vous devez vous enregistrer pour aller dans cette section';
	}
}

	if ($_GET['page'] =='or')
{
include "Or.php";
}
	if ($_GET['page'] == 'textures')
	{
	if (isset($_SESSION['pseudo']))
	{
	
$dirname = "./telechargement/textures/";

$dir = opendir($dirname); 
$i = 0;
?>
<table>
   <tr>
       <td>Nom</td>
       <td style="padding-left:100px";>Version</td>
       <td style="padding-left:40px;">Compatibilité</td>
   </tr>
   <?php
while($file = readdir($dir)) {
	if($file != '.' && $file != '..' && !is_dir($dirname.$file))
	{

	$nom=preg_replace('#([a-z0-9&[\]]+)\.([a-z0-9_]+)\.([0-9]+)\.([0-9]+)\.([a-z]{3})#i','$1',$file);
	$version=preg_replace('#([a-z0-9&[\]]+)\.([a-z0-9_]+)\.([0-9]+)\.([0-9]+)\.([a-z]{3})#i','$2',$file);
	$minecraft=preg_replace('#([a-z0-9&[\]]+)\.([a-z0-9_]+)\.([0-9]+)\.([0-9]+)\.([a-z]{3})#i','$3.$4',$file);
	echo '<tr><td><a href="'.$dirname.$file.'">'.$nom.' </a></td><td style="padding-left:100px;">'.$version.' </td><td style="padding-left:40px;"> '.$minecraft.'</td></tr>';
	} 
}
echo '</table>';
closedir($dir);
}
	else
	{
	echo 'Vous devez vous enregistrer pour aller dans cette section';
	}
	
}
}
else
{
    if ($_GET['admin']) //Pour tout choix dans la partie administration
    {
    if ($_SESSION['rang'] == 2)
    {
    include("administration.php");
    }
    else
		{
		echo 'Votre compte n\'a pas encore été confirmé';
		}
	}
    else
    {
    
if (!isset($_SESSION['pseudo'])||isset($_SESSION['rang']))
{
echo $acceuil;
}
}
}

?>

<div id=footer>Johnrazeur production</div>
</div>

   </body>
</html>
<?php
}
else {
header('Location: installation.php'); }


	}
	catch (Exception $e)
	{
        die('Erreur : ' . $e->getMessage());
	}
