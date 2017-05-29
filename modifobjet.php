<?php
require_once("resources/config.php");
require_once("resources/functions.php");
session_start();

$pagetitle = 'Modification objet';
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
    if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['foire']) && isset($_GET['objet'])) {

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
                else if(!(Object::appartient(test_input($_GET['objet']), $_SESSION['userobject']->id(), $idFoire)))
                    echo "<div class='alert alert-warning'>Cet objet n'existe pas.</div>";
                else
                {
                    $obj = Object::loadObjectFromFoire(test_input($_GET['objet']), $_SESSION['userobject']->id(), $idFoire);
                    include(TEMPLATES_PATH . '/frmModifObjets.php');
                }

            } else {
                echo "<br /><div class='alert alert-warning'>Vous n'avez pas acc&egrave;s &agrave; cette foire.</div>";
            }
        } else {
            echo "<br /><div class='alert alert-danger'>Cette foire n'existe pas.</div>";
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['numitem']) && isset($_POST['idfoire'])) {
        $numItem = test_input($_POST['numitem']);
        $idFoire = test_input($_POST['idfoire']);
        $obj = Object::loadObjectFromFoire($numItem, $_SESSION['userobject']->id(), $idFoire);

        if (Object::appartient($numItem, $_SESSION['userobject']->id(), $idFoire)) {

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
                if (is_a($obj, 'Object')) {
                    if ($obj->updateObject($desc, $baisse, $prix, $taille, $nbitem)) {
                        ?>
                        <div class="alert alert-success">Objet modifi&eacute; avec succ&egrave;s !</div>
                        <p><b>Description&nbsp;: </b><?php echo $obj->desc(); ?></p>
                        <p><b>Taille&nbsp;: </b><?php echo $obj->taille(); ?></p>
                        <p><b>Prix&nbsp;: </b><?php echo $obj->prix(); ?></p>
                        <p><b>Nombre d'objets&nbsp;: </b><?php echo $obj->nbItems(); ?></p>
                        <p><b>Baisse&nbsp;: </b><?php echo ($obj->baisse()) ? "Oui" : "Non"; ?></p>
                        <?php
                    } else {
                        echo "<div class='alert alert-warning'>Erreur pendant la modification des informations de l'objet.</div>";
                    }
                } else {
                    echo "<div class='alert alert-warning'>Cet objet n'existe pas.</div>";
                }
            } else {
                    include(TEMPLATES_PATH . '/frmModifObjets.php');
            }
        } else {
            echo "<div class='alert alert-danger'>Vous essayez d'acc&eacute;der &agrave; un objet qui ne vous appartient pas ou n'existe pas.</div>";
        }
    }
} else {
    accessForbidden();
}
include_once(TEMPLATES_PATH . '/footer.php');
?>