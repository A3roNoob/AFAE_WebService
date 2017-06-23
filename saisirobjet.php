<?php
require_once("resources/config.php");
require_once("resources/functions.php");
session_start();

$pagetitle = 'Saisie des objets';
$desc = $taille = $prix = $nbitem = $baisse = "";
$descErr = $tailleErr = $prixErr = $nbItemErr = false;
include_once(TEMPLATES_PATH . '/header.php');
function spanError($b)
{
    if ($b)
        echo "<span class='glyphicon glyphicon-remove form-control-feedback'></span>";
}

function hasError($b)
{
    if ($b)
        echo "has-error";
}

$foire = null;
if (isset($_SESSION['userobject']) && $_SESSION['userobject']->checkRank(Rank::loadFromName('Vendeur'))) {
    if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['foire'])) {

        ?>
        <form method="GET" class="form-inline">
            <div class="form-group">
                <label for="foire">S&eacute;lectionner&nbsp;: </label>
                <select id="foire" name="foire" class="form-control">
                    <?php
                    $foireMan = new FoireManager();
                    $foireMan->loadFromDbParticipant($_SESSION['userobject']->id());
                    foreach ($foireMan->foires() as $foire) {
                        if ($foire->idFoire() == test_input($_GET['foire']))
                            $selec = "selected";
                        else
                            $selec = "";

                        echo '<option value="' . $foire->idFoire() . '" ' . $selec . '>' . $foire->nomFoire() . '</option>';
                    }
                    ?>
                </select>
            </div>
            <input type="button" class="btn btn-default"
                   onclick="window.location.href= '/saisir/objet/foire/'+ document.getElementById('foire').value + '/'""
            value="S&eacute;lectionner"/>
            <br/>
        </form>
        <?php

        $parti = Participant::loadFromDb(test_input($_GET['foire']), $_SESSION['userobject']->id());

        $idFoire = test_input($_GET['foire']);
        $foire = Foire::loadFromDb($idFoire);
        if (is_a($foire, "Foire")) {


            if (is_a($parti, "Participant") && $parti->valide()) {
                echo "<div class='page-header'>Nom de la foire&nbsp;:&nbsp" . $foire->nomFoire() . "</div>";
                if (today() > $foire->dateFinSaisie())
                    echo "<div class='alert alert-info'>Date de saisie d&eacute;pass&eacute;e.</div>";
                else if (today() < $foire->dateDebutSaisie())
                    echo "<div class='alert alert-info'>Les saisies n'ont pas encore d&eacute;but&eacute; pour cette foire.</div>";
                else
                    include(TEMPLATES_PATH . '/frmSaisirObjet.php');
            }
            else{
                echo "<div class='alert alert-danger'>Vous essayez d'acc&eacute;der &agrave; un objet qui ne vous appartient pas.</div>";
            }
        }
    } else {
        $foireMan = new FoireManager();
        $foireMan->loadFromDbParticipant($_SESSION['userobject']->id());
        if (!empty($foireMan->foires())) {


            ?>
            <form method="GET" class="form-inline">
                <div class="form-group">
                    <label for="foire">S&eacute;lectionner&nbsp;: </label>
                    <select id="foire" name="foire" class="form-control">
                        <?php

                        foreach ($foireMan->foires() as $foire) {
                            echo '<option value="' . $foire->idFoire() . '">' . $foire->nomFoire() . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <input type="button" class="btn btn-default"
                       onclick="window.location.href= '/saisir/objet/foire/'+ document.getElementById('foire').value + '/'""
                value="S&eacute;lectionner"/>
                <br/>
            </form>

            <?php
        } else {
            echo '<div class="alert alert-info">Vous n\'&ecirc;tes inscrit/accept&eacute; dans aucune foire. <br />Revenez plus tard si vous attendez une confirmation.</div>';

        }

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_POST['desc']) && !empty($_POST['desc']))
                $desc = test_input($_POST['desc']);
            else
                $descErr = true;
            if (isset($_POST['taille']) && !empty($_POST['taille']))
                $taille = test_input($_POST['taille']);
            else
                $tailleErr = true;
            if (isset($_POST['prix']) && !empty($_POST['prix']))
                $prix = test_input($_POST['prix']);
            else
                $prixErr = true;
            if (isset($_POST['nbitem']) && !empty($_POST['nbitem']))
                $nbitem = test_input($_POST['nbitem']);
            else
                $nbItemErr = true;
            if (isset($_POST['idfoire']) && !empty($_POST['idfoire']))
                $idFoire = test_input($_POST['idfoire']);
            $baisse = isset($_POST['baisse']);


            if (!$descErr && !$tailleErr && !$prixErr && !$nbItemErr) {
                $obj = Object::createObject($_SESSION['userobject'], $idFoire, $desc, $baisse, $prix, false, $taille, $nbitem, false);
                $insert = $obj->insertObjectIntoDb();
                if (is_bool($insert) && $insert) {
                    ?>
                    <div class="alert alert-success">Objet ajout&eacute; avec succ&egrave;s !</div>
                    <p><b>Description&nbsp;: </b><?php echo $obj->desc(); ?></p>
                    <p><b>Taille&nbsp;: </b><?php echo $obj->taille(); ?></p>
                    <p><b>Prix&nbsp;: </b><?php echo $obj->prix(); ?></p>
                    <p><b>Nombre d'objets&nbsp;: </b><?php echo $obj->nbItems(); ?></p>
                    <p><b>Baisse&nbsp;: </b><?php echo ($obj->baisse()) ? "Oui" : "Non"; ?></p>
                    <?php
                } else {
                    echo '<div class="alert alert-danger">Une erreur est survenue pendant l\'insertion de l\'objet.<br />Veuillez r&eacute;essayez plus tard.</div>';
                }
            } else {
                if (today() > $foire->dateFinSaisie())
                    echo "<div class='alert alert-info'>Date de saisie d&eacute;pass&eacute;e.</div>";
                else if (today() < $foire->dateDebutSaisie())
                    echo "<div class='alert alert-info'>Les saisies n'ont pas encore d&eacute;but&eacute; pour cette foire.</div>";
                else

                    include(TEMPLATES_PATH . '/frmSaisirObjet.php');
            }


        }
    }
} else {
    accessForbidden();
}
include_once(TEMPLATES_PATH . '/footer.php');
?>