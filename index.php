<?php
if (file_exists("config.php")) { //si l'installation a �t� �ffectu�
include("fonctions.php");
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

session_start(); // On ouvre la session
$monnaie = infoserveur('monnaie'); //on r�cup�re les infos
$ip = infoserveur('ip');
$dons = infoserveur('dons');
$allopass = infoserveur('allopass');
$acceuil = infoserveur('acceuil');
$perms = infomenus('perms');
$textures = infomenus('textures');
$menu_monnaie = infomenus('monnaie');
$externe = infomenus('externe');
$nomexterne = infomenus2('externe');


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>HappyWorld Xtra</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
       <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
   </head>
   <body>
<div id="header_bg">
<div id="connexion"><h3>Etat du serveur :<?php etat();?></h3></div>
<div id="logo"></div>
<div id ="version">Beta 1.0</div>
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
	try
	{
	$_POST['pseudo'] = htmlspecialchars($_POST['pseudo']);
	$bdd = database();
    $req=connexion($_POST['pseudo'], $bdd);
	$connexion= ligne($req);
	ident($connexion,$req);
	}
	
	catch (Exception $e)
	{
        die('Erreur : ' . $e->getMessage());
	}
	}

	if ($_GET['page'] == 'deconnexion') //Si le membre se d�connecte
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
		echo 'Votre compte n\'a pas encore �t� confirm�';
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
		echo 'Votre compte n\'a pas encore �t� confirm�';
		}
	}
	else
	{
	echo 'Vous devez vous enregistrer pour aller dans cette section';
	}
}
if ($_GET['page'] == 'achatpermok')//Achat permissions ok
{
echo'Achat de la permission r�ussi !';
}
	if ($_GET['page'] == 'inscription') //page d'inscription
	{
	include("inscription.php");
	}
	if ($_GET['page'] == 'confirm')
	{
	try
    {
    $bdd = database();
    if ($_GET['action'] == 'augmente')
    {
    $req = $bdd->prepare('UPDATE users SET rang = rang+1 WHERE pseudo = ?');
    $req->execute(array($_GET['pseudo']));
    echo 'L\'utilisateur '.$_GET['pseudo'].' a �t� grad�';
    }
    if ($_GET['action'] == 'diminue')
    {
    $req = $bdd->prepare('UPDATE users SET rang = rang-1 WHERE pseudo = ?');
    $req->execute(array($_GET['pseudo']));
    echo 'L\'utilisateur '.$_GET['pseudo'].' a �t� d�grad�';    
    }
	}
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
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
		echo 'Votre compte n\'a pas encore �t� confirm�';
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
		echo 'Votre compte n\'a pas encore �t� confirm�';
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
		echo 'Votre compte n\'a pas encore �t� confirm�';
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
       <td>Version</td>
       <td>Compatibilit�</td>
   </tr>
   <?php
while($file = readdir($dir)) {
	if($file != '.' && $file != '..' && !is_dir($dirname.$file))
	{
	if ($file == 'Quandary[Summer].4.1.6.zip')
	{
		echo '<span style="color:red">Texture officiel :</color></span> </br>';
	$nom=preg_replace('#([a-z0-9&[\]]+)\.([a-z0-9_]+)\.([0-9]+)\.([0-9]+)\.([a-z]{3})#i','$1',$file);
	$version=preg_replace('#([a-z0-9&[\]]+)\.([a-z0-9_])\.([0-9]+)\.([0-9]+)\.([a-z]{3})#i','$2',$file);
	$minecraft=preg_replace('#([a-z0-9&[\]]+)\.([a-z0-9_]+)\.([0-9]+)\.([0-9]+)\.([a-z]{3})#i','$3.$4',$file);
	echo '<a href="'.$dirname.$file.'">'.$nom.'</a>&nbsp;&nbsp;&nbsp;&nbsp; Version :'.$version.' &nbsp;&nbsp;&nbsp;&nbsp;Compatibilit� :'.$minecraft.'</br></br></br>' ;
	$i++;
	} 
	else{
	
	$nom=preg_replace('#([a-z0-9&[\]]+)\.([a-z0-9_]+)\.([0-9]+)\.([0-9]+)\.([a-z]{3})#i','$1',$file);
	$version=preg_replace('#([a-z0-9&[\]]+)\.([a-z0-9_]+)\.([0-9]+)\.([0-9]+)\.([a-z]{3})#i','$2',$file);
	$minecraft=preg_replace('#([a-z0-9&[\]]+)\.([a-z0-9_]+)\.([0-9]+)\.([0-9]+)\.([a-z]{3})#i','$3.$4',$file);
	echo '<tr><td><a href="'.$dirname.$file.'">'.$nom.' </a></td><td>'.$version.' </td><td> '.$minecraft.'</td></tr>';
	}
}
}

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
		echo 'Votre compte n\'a pas encore �t� confirm�';
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
else
header('Location: installation.php');
