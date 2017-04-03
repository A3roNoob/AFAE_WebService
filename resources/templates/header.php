<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="resources/css/style.css"/>
    <title><?php echo $pagetitle; ?></title>
</head>
<body>

<?php
if (!isset($_SESSION['userobject']) || isset($_GET['deco'])) {
    ?>
    <ul id="menu">
        <li>
            <a href="index.php">Accueil</a>
        </li>
        <li>
            <a href="connexion.php">Se connecter</a>
        </li>
        <li>
            <a href="inscription.php">S'inscrire</a>
        </li>
    </ul>
    <?php
    if ($pagetitle != "Accueil" && $pagetitle != "Connexion")
        echo "<div style='display: none;'";

} else {
    $_SESSION['userobject']->checkUser();
    ?>
    <ul id="menu">
        <li>
            <a href="index.php">Accueil</a>
        </li>
        <li>
            <a href="saisirobjet.php">Saisir des objets</a>
        </li>
        <li>
            <a href="listeobjet.php">Vos objets</a>
        </li>
        <li>
            <a href="deconnexion.php?deco=1">Se d&eacute;connecter</a>
        </li>

    </ul>
    <?php
    if (isset($level)) {
        if (is_a($level, "Rank") && !$_SESSION['userobject']->checkRank($level))
            echo "<div class='messagebox'>Acc&eacute; non autoris&eacute;</div><div style='display: none;'";
    }
}

?>

