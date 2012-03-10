<!DOCTYPE html>
<html  >
   <head>
       <title>HappyWorld Xtra</title>
       <meta charset="utf-8"" />
        <style>
label
{
display:block;
width:300px;
float:left;
}
        </style>
   </head>
   <body>
  <header style='background-image: url("images/logo.png");top: 0;width: 480px;height: 180px;margin:auto;"'></header>
  
  <?php
if (isset($_GET['page']))
{
if ($_GET['page'] == 'step2')
{
$fichier = fopen("config.php", "a+",777);
$code = '<?php
$host = \''.$_POST['bdd'].'\';
$dbname = \''.$_POST['table'].'\';
$user = \''.$_POST['user'].'\';
$mdp = \''.$_POST['mdp'].'\';
?>
';

fputs($fichier, $code);
fclose($fichier);
echo 'Installation "config.php" réussit !</br>';


include('config.php');
$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
$bdd = new PDO('mysql:host='.$host.';dbname='.$dbname.'', ''.$user.'', ''.$mdp.'', $pdo_options);

$req = $bdd->query('CREATE TABLE IF NOT EXISTS `allopass` (
  `Pseudo` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;');
echo'Installation de "allopass" réussi !</br>';


$req = $bdd->query('CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `valeur` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;');


$bdd->exec('INSERT INTO `config` (`id`, `nom`, `valeur`) VALUES
(1, \'monnaie\', \'Or\'),
(2, \'ip\', \'88.190.237.44\'),
(3, \'acceuil\', \'<p>Bienvenue sur HappyWorld Xtra</p>\r\n<p>Vous pourez ici gérer votre compte, gérer vos impots, encherir.</p>\r\n<p>Pour vous connecter, vous devez vous inscrires et vous devez être accepté a la white-list.</p>\'),
(4, \'perms\', \'1\'),
(5, \'textures\', \'1\'),
(6, \'menu_monnaie\', \'1\'),
(7, \'dons\', \'75\'),
(8, \'allopass\', \'\');');
echo'Installation de "config" réussi !</br>';

$req = $bdd->query('CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `valeur` varchar(255) NOT NULL,
  `valeur2` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;');

$bdd->exec('INSERT INTO `menus` (`id`, `nom`, `valeur`, `valeur2`) VALUES
(1, \'perms\', \'1\', \'\'),
(2, \'textures\', \'1\', \'\'),
(3, \'monnaie\', \'1\', \'\'),
(4, \'externe\', \'\', \'\');');
echo'Installation de "menus" réussi !</br>';

$req = $bdd->query('CREATE TABLE IF NOT EXISTS `prachat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permissions` varchar(255) NOT NULL,
  `prix` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;');

$bdd->exec('INSERT INTO `prachat` (`id`, `permissions`, `prix`) VALUES
(5, \'falsebook.blocks.bridge\', 35),
(2, \'falsebook.ic.mc1110\', 30),
(3, \'falsebook.ic.mc0111\', 30),
(4, \'falsebook.blocks.hiddenswitch.create\', 25),
(6, \'falsebook.blocks.gate\', 40);');
echo'Installation de "prachat" réussi !</br>';

$req = $bdd->query('CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) NOT NULL,
  `pass` text NOT NULL,
  `email` text NOT NULL,
  `rang` int(11) NOT NULL DEFAULT \'0\',
  `dons` int(11) NOT NULL DEFAULT \'0\',
  `dateattaque` date NOT NULL,
  `nombreattaques` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;');
echo'Installation de "users" réussi !</br></br></br></br>';
echo'Veuillez saisir les informations du compte administrateur :</br></br>';
?>
<form action="?page=step3" method="post">
<label for="pseudo">Pseudo :</label>
<input type="text" name="pseudo"></br>
<label for="mdp">Mot de passe :</label>
<input type="password" name="mdp"></br>
<input type="submit" value="Suivant">
</form>

<?php

}

elseif ($_GET['page'] == 'step3')
{
include('config.php');
$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
$bdd = new PDO('mysql:host='.$host.';dbname='.$dbname.'', ''.$user.'', ''.$mdp.'', $pdo_options);
$prefix = 'lsfdjlsds-è_ççsà)dsf)isdjfdsbkh';
$sufix = 'dsfhsdhf_eyaàçzeruzyfshsdf,';
$mdp =''.$prefix.''.$_POST['mdp'].''.$sufix.'';
$mdp = sha1($mdp);
$req = $bdd->prepare('INSERT INTO users(pseudo, pass, rang) VALUES(?, ?, ?)');
$req -> execute(array($_POST['pseudo'],$mdp,2));
echo 'Installation effectué avec succès, supprimer "installation.php" pour des raisons de sécurité';
}
}
else
{
?>
  Bienvenue dans le script d'installation de Minecraft Xtra.</br>
  Nous avons besoin de plusieurs données pour continuer l'installation.</br>
  Merci de bien vouloir completer le formulaire suivant.</br></br>
  
  <form action="?page=step2" method="post">
  <label>Adresse de la base de données :</label>
  <input type="text" name="bdd"></br>
  <label>Nom de la table :</label>
  <input type="text" name="table"></br>
  <label>Identifiant :</label>
  <input type="text" name="user"></br>
  <label>Mot de passe :</label>
  <input type="password" name="mdp"></br>
  <input type="submit" value="Suivant">
  </form>
<?php
}
?>
 

</body>
</html>
