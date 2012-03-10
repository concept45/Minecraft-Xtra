<?php
class membre
{
	private $pseudo;
	private $email;
	private $or;

    public function getPseudo()
    {
        return $this->pseudo;
    }
        
	public function setPseudo($nouveauPseudo)
    {
        if (!empty($nouveauPseudo))
        {
            $this->pseudo = $nouveauPseudo;
        }
    }   

	public function getOr()
	{
	$bdd = database();
	
	}

	public function envoieor ($dest,$or_saisit)
	{
	global $bdd;
	$dest = htmlspecialchars($dest);
	$or_saisit = htmlspecialchars($or_saisit);
	$or_saisit = htmlspecialchars($or_saisit);
		if (preg_match("#^[0-9]+$#", $or_saisit)) //si $or est un chiffre
		{
			if ($or_saisit > 0)
			{
			$req = $bdd->prepare('SELECT blance FROM iconomy WHERE username = ?');
			$req->execute(array($this->pseudo)); //on recupère l'argent de l'envoyeur
			$or_user = $req->fetch();
			$or_user=$or_user['balance']-$or_saisit; // Or de l'user après envoie

				if ($or_user < 0) //Si l'envoyeur aura < 0 or
				{
				echo 'Vous n\'avez pas assez d\'argent';
				echo $this->pseudo;
				}
				else
				{
				$req = $bdd->prepare('SELECT balance FROM iconomy WHERE username = ?');
				$req->execute(array($dest)); //on récupère l'argent du destinateur
				$or_destinataire = $req->fetch(); 
				$or_destinataire=$or_destinataire['balance']+$or_saisit; // Or du destinataire après envoie

				$req = $bdd->prepare('UPDATE iconomy SET balance=:balance WHERE username= :username');
				$req->execute(array('balance' =>$or_user, 'username' => $this->pseudo)); //On applique les changements

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
	

	public function setNewEmail($newemail)
	{
	$newemail = htmlspecialchars($newemail);

	if (isset($newemail) AND !empty($newemail))
	{
	global $bdd;
	$req = $bdd->prepare('UPDATE users SET email = :email WHERE pseudo = :pseudo');
	$req->execute(array(
    'email' => $newemail,
    'pseudo' => $this->pseudo
    ));
	echo 'E-mail changé avec succès. </br>';
	}

	}
	
	
	public function setNewMotdepasse($newmdp,$cnewmdp)
	{
	$newmdp = htmlspecialchars($_POST['newmdp']);
	$cnewmdp = htmlspecialchars($_POST['cnewmdp']);
	if ($newmdp == $cnewmdp)
	{
	$newmdp=hachage($newmdp);
	global $bdd;
		if (isset($newmdp) AND !empty($_POST['newmdp']))
		{
		$req = $bdd->prepare('UPDATE users SET pass = :newmdp WHERE pseudo = :pseudo');
		$req->execute(array(
    	'newmdp' => $newmdp,
    	'pseudo' => $this->pseudo
    	));
		echo 'Mot de passe modifié avec succès. </br>';
		}
	}
	else
	{
	echo "Les mots de passe ne sont pas identique.";
	}

	}
	


public function achat_permissions($permission,$prix)
	{
$permission = htmlspecialchars($permission);
$prix = htmlspecialchars($prix);
	if (isset($permission))
{
	global $bdd;
	$req = $bdd->prepare('SELECT * FROM prachat WHERE permissions = ? AND prix = ?');
	$req->execute(array($permission, $prix));
	$ligne = ligne($req);

if ($ligne == 1)
{

$req = $bdd->prepare('SELECT balance FROM iconomy WHERE username = ?');
$req->execute(array($this->pseudo)); //on recupère l'argent de l'user
$or = $req->fetch();
    if ($or['balance'] >= $prix)
    {
    $or=$or['balance']-$prix;
    $req = $bdd->prepare('UPDATE iconomy SET balance=:balance WHERE username= :username');
    $req->execute(array('balance' =>$or, 'username' => $this->pseudo));
    
        
    $req = $bdd->prepare('INSERT INTO permissions(name,permission) VALUES(:name, :permission)');
    $req->execute(array(
        'name' => $this->pseudo,
        'permission' => $permission
        ));
    
	echo 'Vous avez bien acheté la permission : -'.$permission.' pour '.$prix.' Or.';
    }
    else
    {
    echo 'Vous n\'avez pas assez d\'argent';
    }
}
else
{
echo 'Ne modifiez pas les variables !!!';
}

}

	}


}

?>

