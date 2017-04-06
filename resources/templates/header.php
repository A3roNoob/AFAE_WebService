<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <title><?php echo $pagetitle; ?></title>
</head>
<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">AFAE</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">

<?php

if (!isset($_SESSION['userobject']) || isset($_GET['deco'])) {
    ?>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="connexion.php">Se connecter</a></li>
                    <li><a href="inscription.php">S'inscrire</a></li>
    <?php

} else {
    $_SESSION['userobject']->checkUser();
    ?>
                    <li><a href="index.php">Accueil</a> </li>
                    <li><a href="saisirobjet.php">Saisir des objets</a></li>
                    <li> <a href="listeobjet.php">Vos objets</a></li>
                    <?php
                    if ($_SESSION['userobject']->checkRank(Rank::loadFromName("Super Administrateur"))) {
                        ?>
                        <li><a href="listeutilisateur.php"> Liste utilisateur </a></li>
                        <?php
                    } else if ($_SESSION['userobject']->checkRank(Rank::loadFromName("Administrateur de foire"))) {
                        ?>
                        <li><a href="listeutilisateur.php">Liste des vendeurs</a></li>
                        <?php
                    }
                    if ($_SESSION['userobject']->checkRank(Rank::loadFromName("Administrateur de foire"))) {
                        ?>
                        <li><a href="saisiefoire.php">Cr&eacute;er une foire</a></li>
                        <?php
                    }
                    ?>
                    <li><a href="deconnexion.php?deco=1"> Se d&eacute;connecter </a></li>
    <?php

}

?>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>

<div class="container">
