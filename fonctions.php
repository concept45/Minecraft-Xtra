<?php

function database() //connection a mysql
{
    include('config.php');
    $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
    $bdd = new PDO('mysql:host='.$host.';dbname='.$dbname.'', ''.$user.'', ''.$mdp.'', $pdo_options); //mettez ici vos identifiants
	return $bdd;

}

function infoserveur($element)
{
 try
{
$bdd = database();
$req = $bdd->prepare('SELECT * FROM config WHERE nom=?');
$req->execute(array($element));
$reponse = $req->fetch();
return $reponse['valeur'];
}
	catch (Exception $e)
	{
        die('Erreur : ' . $e->getMessage());
	}
}

function infomenus($element)
{
 try
{
$bdd = database();
$req = $bdd->prepare('SELECT * FROM menus WHERE nom=?');
$req->execute(array($element));
$reponse = $req->fetch();
return $reponse['valeur'];
}
	catch (Exception $e)
	{
        die('Erreur : ' . $e->getMessage());
	}
}

function infomenus2($element)
{
 try
{
$bdd = database();
$req = $bdd->prepare('SELECT * FROM menus WHERE nom=?');
$req->execute(array($element));
$reponse = $req->fetch();
return $reponse['valeur2'];
}
	catch (Exception $e)
	{
        die('Erreur : ' . $e->getMessage());
	}
}

function listmenus ($etat)
{
    $bdd = database();
    $req = $bdd->prepare('SELECT * FROM menus WHERE valeur=?');
    $req->execute(array($etat));
    if ($etat == 0)
    {
    while ($donnees = $req->fetch())
	{
	echo ''.$donnees['nom'].' <a href=?admin=activer&nom='.$donnees['nom'].'>Activer</a></br>';
	}
    }
    if ($etat == 1)
    {
    while ($donnees = $req->fetch())
	{
	echo ''.$donnees['nom'].' <a href=?admin=desactiver&nom='.$donnees['nom'].'>Désactiver</a></br>';
	}
    }
	
}

function etat ()
{
$ip = infoserveur('ip');
$FTP = @fsockopen($ip, 25565, $errno, $errstr,1); // mettez ici l'adresse de votre serveur
if ($FTP) { 
?>
<img src="./images/redlampeon.gif" /> <?php
echo'Online';
fclose($FTP);
}
else
{
?>
<img src="./images/redlampeoff.gif" /> <?php
echo'Offline';
}
}

function connecticonomy ($user)
{
    $bdd = database();
    $req = $bdd->prepare('SELECT * FROM iconomy WHERE username=?');
	$req->execute(array($user));
	return $req;
	
}

function envoior ($user,$dest, $bdd, $or_saisit)
{
$or_saisit = htmlspecialchars($or_saisit);
if (preg_match("#^[0-9]+$#", $or_saisit)) //si $or est un chiffre
{
if ($or_saisit > 0)
{
$req = $bdd->prepare('SELECT balance FROM iconomy WHERE username = ?');
$req->execute(array($user)); //on recupère l'argent de l'envoyeur
$or_user = $req->fetch();
$or_user=$or_user['balance']-$or_saisit; // Or de l'user après envoie

if ($or_user < 0) //Si l'envoyeur aura < 0 or
{
echo 'Vous n\'avez pas assez d\'argent';
}
else
{
$req = $bdd->prepare('SELECT balance FROM iconomy WHERE username = ?');
$req->execute(array($dest)); //on récupère l'argent du destinateur
$or_destinataire = $req->fetch(); 
$or_destinataire=$or_destinataire['balance']+$or_saisit; // Or du destinataire après envoie

$req = $bdd->prepare('UPDATE iconomy SET balance=:balance WHERE username= :username');
$req->execute(array('balance' =>$or_user, 'username' => $user)); //On applique les changements

$req = $bdd->prepare('UPDATE iconomy SET balance=? WHERE username=?');
$req->execute(array($or_destinataire,$dest));
echo 'L\'argent a bien été envoyé';
}
}
else
{
echo 'Metter un chiffre différent de 0';
}
}
else
{
echo 'Seul les chiffres sont acceptés';
}


}

function connexion ($user, $bdd)
{
    $req = $bdd->prepare('SELECT * FROM users WHERE pseudo=?');
	$req->execute(array($user));
	return $req;
	
}

function listrang ($rang)
{
try
{
    $bdd = database();
    $req = $bdd->prepare('SELECT * FROM users WHERE rang=?');
    $req->execute(array($rang));
    while ($donnees = $req->fetch())
	{
	echo ''.$donnees['pseudo'].' [<a href=?page=confirm&action=augmente&pseudo='.$donnees['pseudo'].'>+</a>][<a href=?page=confirm&action=diminue&pseudo='.$donnees['pseudo'].'>-</a>]</br>';
	}
    
    }
	catch (Exception $e)
	{
        die('Erreur : ' . $e->getMessage());
	}
	
}
function listuser ($user,$bdd)
{
    ?>
    <form action="?page=envoior" method="post">
	Destinataire : 
	<select name="choix">
    <?php
    $req = $bdd->query('SELECT * FROM iconomy ORDER BY username');
    while ($donnees = $req->fetch())
    {
    if ($donnees['username'] != $user)
    {
	echo '<option value="'.$donnees['username'].'">'.$donnees['username'].'</br>';
    }
    }
    ?>
    </option><option value="----" selected="selected">----</option>
    </select>

	<?php
	
}
	
	

function ligne($req)
{
    $nb_ligne = $req->rowCount();
	return $nb_ligne;
}

function hachage($mdp) //hachage du mot de passe
{
$prefix = 'lsfdjlsds-è_ççsà)dsf)isdjfdsbkh';
$sufix = 'dsfhsdhf_eyaàçzeruzyfshsdf,';
$mdp =''.$prefix.''.$mdp.''.$sufix.'';
$mdp = sha1($mdp);
return $mdp;

}

function inscription ($bdd, $pseudo, $mdp, $cmdp, $email)
{

if (!empty($pseudo)&&!empty($mdp)&&!empty($cmdp)&&!empty($email)) //Si les champs ne sont pas vide
{
$req = connexion($pseudo, $bdd);
$nb_ligne = ligne($req);
$mdp =htmlspecialchars($mdp);
$cmdp = htmlspecialchars($cmdp);
$email = htmlspecialchars($email);
	if ($nb_ligne ==0)
		{
		if ($mdp ==$cmdp)
        {
        if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email)) //si l'adresse e-mail est valide
        {
		$mdp=hachage($mdp);
		$req = $bdd->prepare('INSERT INTO users(pseudo, pass, email) VALUES(?, ?, ?)');
		$req -> execute(array($pseudo,$mdp,$email));
		echo 'Vous vous êtes bien fait enregistrer';
        }
        else
        {
        echo 'Adresse e-mail invalide';
        }
		}
		else
		{
		echo 'Les mots de passes sont différent';
		}
}
else
{
echo 'Un compte existant utilise les mêmes identifiants';
}

}
else
{
echo 'Informations manquantes';
}
}
function ident ($nb_ligne,$req)
{
$_POST['mdp']= htmlspecialchars($_POST['mdp']);
$mdp = hachage($_POST['mdp']);
while ($donnees = $req->fetch())
{
$sqlmdp = $donnees['pass'];
$rang = $donnees['rang'];
$email = $donnees['email'];
$tempsbdd = $donnees['dateattaque'];
$attaques = $donnees['nombreattaques'];
$bdd_ip = $donnees['ip'];
$ip = $_SERVER['REMOTE_ADDR'];
}
$date = date('Y-m-d');


if (($attaques < 5 && $date == $tempsbdd ) || ($tempsbdd != $date) || ($ip != $bdd_ip))
{
if (($nb_ligne ==0)||($sqlmdp !=$mdp))
	{
    if ($tempsbdd != $date) //Si pas la même date, on fait une reset du compteur
    {
    $bdd = database();
    $req = $bdd->prepare('UPDATE users SET dateattaque = NOW(), nombreattaques = 1, ip=? WHERE pseudo = ?');
    $req->execute(array($ip,$_POST['pseudo']));
	echo 'Connexion échoué';
    }
    else
    {
    $bdd = database();
    $req = $bdd->prepare('UPDATE users SET dateattaque = NOW(), nombreattaques = nombreattaques+1, ip=? WHERE pseudo = ?');
    $req->execute(array($ip,$_POST['pseudo']));
	echo 'Connexion échoué';
    }
    }
	else
	{
	$resultat = $nb_ligne;
	$_SESSION['pseudo'] = $_POST['pseudo'];
	$_SESSION['rang'] = $rang;
    $_SESSION['email'] = $email;
	header('Location: index.php');
	
	}
}
else
{
echo'Le nombre de connexion maximal est dépassé.';
}

}


function compte ($req)
{
while ($donnees = $req->fetch())
{
$argent = round($donnees['balance'],0);
echo $argent;
}
}

function avatar ($pseudo) //génération de l'avatar
{

if (!$_SESSION['avatar']) //génération un seul fois par session
{

        if(imagecreatefrompng("http://minecraft.net/skin/".$pseudo.".png")) //Si le joueur existe
        {

        $source = imagecreatefrompng("http://minecraft.net/skin/".$pseudo.".png"); //on récupère l'image

        $destination = imagecreate(8,8); //On créer une image de 8x8


        $largeur_source = imagesx($source); //On récupère la largeur

        $hauteur_source = imagesy($source); //On récupère la hauteur

        $largeur_destination = imagesx($destination);

        $hauteur_destination = imagesy($destination);


        $destination_x = 0;

        $destination_y =  0;
        imagecopymerge($destination, $source, $destination_x, $destination_y, 8, 8, $largeur_source, $hauteur_source, 100);


        imagepng($destination, "./avatars/".$pseudo.".png" );//On enregistre
        $dest = imagecreate(64, 64); //On agrandit l'image
        $src = imagecreatefrompng("./avatars/".$pseudo.".png");
        imagecopyresized($dest, $src, 0, 0, 0, 0, 64, 64, 8, 8);
        imagepng($dest, "./avatars/".$pseudo.".png" );
        $_SESSION['avatar'] = 1; //L'avatar a été généré
        }
}

}
?>
