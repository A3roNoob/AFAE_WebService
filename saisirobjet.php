<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 03/04/2017
 * Time: 15:59
 */
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
        <form action="saisirobjet.php" method="GET" class="form-inline">
            <div class="form-group">
                <label for="foire">S&eacute;lectionner&nbsp;: </label>
                <select id="foire" name="foire" class="form-control">
                    <?php
                    $foireMan = new FoireManager();
                    $foireMan->loadFromDbParticipant($_SESSION['userobject']->id());
                    foreach ($foireMan->foires() as $foire) {
                        echo '<option value="' . $foire->idFoire() . '">' . $foire->nomFoire() . '</option>';
                    }
                    ?>
                </select>
            </div>
            <input type="submit" class="btn btn-default" value="S&eacute;lectionner"/>
        </form>
        <?php
        $idFoire = test_input($_GET['foire']);
        $foire = Foire::loadFromDb($idFoire);
        if (!is_null($foire)) {
            echo "<div class='page-header'>Nom de la foire&nbsp;:&nbsp" . $foire->nomFoire() . "</div>";
            include(TEMPLATES_PATH . '/frmSaisirObjet.php');
        }
    } else {
        ?>
        <form action="saisirobjet.php" method="GET" class="form-inline">
            <div class="form-group">
                <label for="foire">S&eacute;lectionner&nbsp;: </label>
                <select id="foire" name="foire" class="form-control">
                    <?php
                    $foireMan = new FoireManager();
                    $foireMan->loadFromDbParticipant($_SESSION['userobject']->id());
                    foreach ($foireMan->foires() as $foire) {
                        echo '<option value="' . $foire->idFoire() . '">' . $foire->nomFoire() . '</option>';
                    }
                    ?>
                </select>
            </div>
            <input type="submit" class="btn btn-default" value="S&eacute;lectionner"/>
        </form>
        <?php
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
            $baisse = isset($_POST['baisse']);



            if (!$descErr && !$tailleErr && !$prixErr && !$nbItemErr) {
                $obj = Object::createObject($_SESSION['userobject'], $foire->idFoire(), $desc, $baisse, $prix, false, $taille, $nbitem, false);
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
                include(TEMPLATES_PATH . '/frmSaisirObjet.php');
            }


        }
    }
} else {
    accessForbidden();
}
include_once(TEMPLATES_PATH . '/footer.php');
?>