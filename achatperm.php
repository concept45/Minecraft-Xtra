<?php
if ($_GET['confirme']== 'oui')
{
echo 'Vous avez bien acheté la permission : -'.$_GET['perm'].' pour '.$_GET['prix'].' Or.';
}
else
{
if (isset($_GET['perm']))
{
try
{
$bdd = database();

$req = $bdd->prepare('SELECT * FROM prachat WHERE permissions = ? AND prix = ?');
$req->execute(array($_GET['perm'], $_GET['prix']));
$ligne = ligne($req);

if ($ligne == 1)
{

$req = $bdd->prepare('SELECT balance FROM iconomy WHERE username = ?');
$req->execute(array($_SESSION['pseudo'])); //on recupère l'argent de l'user
$or = $req->fetch();
    if ($or['balance'] >= $_GET['prix'])
    {
    $or=$or['balance']-$_GET['prix'];
    $req = $bdd->prepare('UPDATE iconomy SET balance=:balance WHERE username= :username');
    $req->execute(array('balance' =>$or, 'username' => $_SESSION['pseudo']));
    
        
    $req = $bdd->prepare('INSERT INTO permissions(name,permission) VALUES(:name, :permission)');
    $req->execute(array(
        'name' => $_SESSION['pseudo'],
        'permission' => $_GET['perm']
        ));
    
    header('Location: index.php?page=achatperm&confirme=oui&perm='.$_GET['perm'].'&prix='.$_GET['prix'].'');
    }
    else
    {
    echo 'Vous n\'avez pas assez d\'argent';
    }
}
else
{
echo 'Ne modifiez pas les variables !!!';
echo $ligne;
}
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
}
else
{
echo 'fail';
}
}
?>
