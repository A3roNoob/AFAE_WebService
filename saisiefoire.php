<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 05/04/2017
 * Time: 17:15
 */

require_once("resources/config.php");
require_once("resources/functions.php");
session_start();


$pagetitle = 'Cr&eacute;ation de foire';
include_once(TEMPLATES_PATH . '/header.php');

$foireNameErr = $dateErr = "";
if (isset($_SESSION['userobject']) && $_SESSION['userobject']->checkRank(Rank::loadFromName('Administrateur de foire'))) {

    if (isset($_POST['name']) && isset($_POST['datedebutsaisie']) && isset($_POST['datefinsaisie']) && isset($_POST['datedebutfoire']) && isset($_POST['datefinfoire'])) {
        if (isset($_POST['idassoc']) && isset($_POST['idadmin'])) {
            $idAdmin = test_input($_POST['idadmin']);
            $idAssoc = test_input($_POST['idassoc']);
        } else {
            $idAdmin = $_SESSION['userobject']->id();
            $idAssoc = Association::loadFromAdmin($_SESSION['userobject']->id())->idAssoc();
        }

        if (empty($_POST['name']) || empty($_POST['datedebutfoire']) || empty($_POST['datefinfoire']) || empty($_POST['datedebutsaisie']) || empty($_POST['datefinsaisie'])) {
            if (empty($_POST['name']))
                $foireNameErr = "Nom obligatoire. ";
            if (empty($_POST['datedebutsaisie']))
                $dateErr = $dateErr . "Date d&eacute;but saisie vide. ";
            if (empty($_POST['datefinsaisie']))
                $dateErr = $dateErr . "Date fin saisie vide. ";
            if (empty($_POST['datedebutfoire']))
                $dateErr = $dateErr . "Date d&eacute;but foire vide. ";
            if (empty($_POST['datefinfoire']))
                $dateErr = $dateErr . "Date fin foire vide. ";
            if (!(empty($_POST['datedebutfoire']) || empty($_POST['datefinfoire']) || empty($_POST['datedebutsaisie']) || empty($_POST['datefinsaisie'])))
                if (convertDateToSql($_POST['datedebutfoire']) > convertDateToSql($_POST['datefinfoire']) || convertDateToSql($_POST['datedebutsaisie']) > convertDateToSql($_POST['datefinsaisie']) || convertDateToSql($_POST['datefinsaisie']) > convertDateToSql($_POST['datedebutfoire'])) {
                    if (convertDateToSql($_POST['datedebutfoire']) > convertDateToSql($_POST['datefinfoire']))
                        $dateErr = $dateErr . "Date de fin de la foire ne peut &ecirc;tre inf&eacute;rieure &agrave; date de d&eacute;but de la foire. ";
                    if (convertDateToSql($_POST['datedebutsaisie']) > convertDateToSql($_POST['datefinsaisie']))
                        $dateErr = $dateErr . "Date de fin de saisie ne peut &ecirc;tre inf&eacute;rieure &agrave; date de d&eacute;but de saisie. ";
                    if (convertDateToSql($_POST['datefinsaisie']) > convertDateToSql($_POST['datedebutfoire']))
                        $dateErr = $dateErr . "Date de fin de saisie ne peut &ecirc;tre inf&eacute;rieure &agrave; date de d&eacute;but de la foire. ";
                }
            include_once(TEMPLATES_PATH . '/frmSaisirFoire.php');

        } else {
            $foireName = test_input($_POST['name']);
            if (!empty($_POST['prixbaisse']))
                $prixbaisse = test_input($_POST['prixbaisse']);
            else
                $prixbaisse = 10;

            if (!empty($_POST['maxobj']))
                $maxobj = test_input($_POST['maxobj']);
            else
                $maxobj = $config['max_object_user'];
            if (!empty($_POST['maxobjassoc']))
                $maxobjassoc = test_input($_POST['maxobjassoc']);
            else
                $maxobjassoc = $config['max_object_assoc'];

            if (!empty($_POST['retenue']))
                $retenue = test_input($_POST['retenue']);
            else
                $retenue = 10;


            $foire = Foire::createFoire($foireName, $idAssoc, $idAdmin, $_POST['datedebutfoire'], $_POST['datefinfoire'], $_POST['datedebutsaisie'], $_POST['datefinsaisie'], $prixbaisse, $maxobj, $maxobjassoc, $retenue);
            $foire->insertIntoDb();
            ?>
            <div id="container">
                <p><b>Nom de la foire&nbsp;:&nbsp;</b> <?php echo $foire->nomFoire(); ?></p>
                <p><b>Association&nbsp;:&nbsp;</b><?php echo Association::loadFromDb($foire->idAssoc())->nomAssoc(); ?>
                </p>
                <p><b>Administrateur&nbsp;:&nbsp;</b><?php echo User::loadUserWithId($foire->idAdmin())->name(); ?></p>
                <p><b>Prix minimum de baisse&nbsp;:&nbsp;</b><?php echo $prixbaisse; ?></p>
                <p><b>Max objets par vendeur&nbsp;:&nbsp;</b><?php echo $maxobj; ?></p>
                <p><b>Max objets par association&nbsp;:&nbsp;</b><?php echo $maxobjassoc; ?></p>
                <p><b>Pourcentage de retenue&nbsp;:&nbsp;</b><?php echo $retenue; ?></p>
                <p><b>Date d&eacute;but des saisies&nbsp;:&nbsp;</b><?php echo $_POST['datedebutsaisie']; ?></p>
                <p><b>Date fin des saisies&nbsp;&nbsp;</b><?php echo $_POST['datefinsaisie']; ?></p>
                <p><b>Date d&eacute;but de la foire&nbsp;:&nbsp;</b><?php echo $_POST['datedebutfoire']; ?></p>
                <p><b>Date fin de la foire&nbsp;&nbsp;</b><?php echo $_POST['datefinfoire']; ?></p>

            </div>
            <?php
        }
    } else {
        include_once(TEMPLATES_PATH . '/frmSaisirFoire.php');
    }
} else {
    accessForbidden();
}
include_once(TEMPLATES_PATH . '/footer.php');