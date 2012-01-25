<?php
try
{
$bdd = database();
envoior($_SESSION['pseudo'],$_POST['choix'],$bdd,$_POST['somme']);
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
