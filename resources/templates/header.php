<?php ///TODO générations des documents ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="/resources/css/style.css">
    <link rel="stylesheet" href="https://bootswatch.com/paper/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker3.min.css">
    <title><?php echo $pagetitle; ?></title>
</head>
<body>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" ><img class="img-responsive img-thumbnail" alt="Afae" src="/resources/media/logo-afae.jpg"></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">

                <?php

                if (!isset($_SESSION['userobject']) || isset($_GET['deco'])) {
                    ?>
                    <li><a href="/">Accueil</a></li>
                    <li><a href="/connexion/">Se connecter</a></li>
                    <li><a href="/inscription/">S'inscrire</a></li>
                    <?php

                } else {
                    $_SESSION['userobject']->checkUser();
                    ?>
                    <li><a href="/">Accueil</a></li>
                    <li><a href="/saisir/objet/">Saisir des objets</a></li>
                    <li><a href="/liste/objet/">Vos objets</a></li>
                    <?php
                    if ($_SESSION['userobject']->checkRank(Rank::loadFromName("Super Administrateur"))) {
                        ?>
                        <li><a href="/liste/utilisateur/"> Liste utilisateur </a></li>
                        <?php
                    } else if ($_SESSION['userobject']->checkRank(Rank::loadFromName("Administrateur de foire"))) {
                        ?>
                        <li><a href="/liste/vendeur/">Liste des vendeurs</a></li>
                        <?php
                    }
                    if ($_SESSION['userobject']->checkRank(Rank::loadFromName("Administrateur de foire"))) {
                        ?>
                        <li><a href="/saisir/foire/">Cr&eacute;er une foire</a></li>
                        <?php
                    }
                    ?>
                    <li><a href="/enregistrement/foire/">S'inscrire à une foire</a></li>
                    <li><a href="/perso/">Infos personnelles</a></li>
                    <li><a href="/deconnexion/"> Se d&eacute;connecter </a></li>
                    <?php

                }

                ?>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container">
