<form action="?page=changecompte" method="post">

<p> <label for="newmdp">Nouveau mot de passe :</label>
<input type="password" name="newmdp"></br>
<label for="cnexmdp">Confirmer mot de passe :</label>
<input type="password" name="cnewmdp"></br></br>
Adresse e-mail actuelle :
 <?php 
global $bdd;
$req = $bdd->prepare('SELECT * FROM users WHERE pseudo=?');
$req->execute(array($_SESSION['pseudo']));
$email = $req->fetch();
echo $email['email'];

 ?></br>
<label for="newemail">Nouvelle adresse e-mail :</label>
<input type="text" name="newemail"></br></br>
<input type="submit" value="Modifier">

</form>
