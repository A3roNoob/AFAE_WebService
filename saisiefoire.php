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

$foireNameErr = "";//Error message if foire name is empty
if (isset($_SESSION['userobject']) && $_SESSION['userobject']->checkRank(Rank::loadFromName('Administrateur de foire'))) {

    if (isset($_POST['name']) && isset($_POST['datedebut']) && isset($_POST['datefin'])) {
        if(isset($_POST['idassoc']) && isset($_POST['idadmin'])) {
            $idAdmin = test_input($_POST['idadmin']);
            $idAssoc = test_input($_POST['idassoc']);
        }
        else
        {
            $idAdmin = $_SESSION['userobject']->id();
            $idAssoc = Association::loadFromAdmin($_SESSION['userobject']->id())->idAssoc();
        }

        if(empty($_POST['name']))
        {
            $foireNameErr = "Nom obligatoire";
            include_once(TEMPLATES_PATH . '/frmSaisirFoire.php');
        }
        else {
            $foireName = test_input($_POST['name']);
            $foire = Foire::createFoire($foireName, $idAssoc, $idAdmin, $_POST['datedebut'], $_POST['datefin']);
            $foire->insertIntoDb();
            ?>
            <div id="container">
                <p><b>Nom de la foire&nbsp;:&nbsp;</b> <?php echo $foire->nomFoire();?></p>
                <p><b>Association&nbsp;:&nbsp;</b><?php echo Association::loadFromDb($foire->idAssoc())->nomAssoc(); ?></p>
                <p><b>Administrateur&nbsp;:&nbsp;</b><?php echo User::loadUserWithId($foire->idAdmin())->name(); ?></p>
                <p><b>Date d&eacute;but&nbsp;:&nbsp;</b><?php echo $_POST['datedebut']; ?></p>
                <p><b>Date fin&nbsp;&nbsp;</b><?php echo $_POST['datefin']; ?></p>
            </div>
            <?php
        }
    }
    else
        {
       include_once(TEMPLATES_PATH . '/frmSaisirFoire.php');
    }
} else {
    accessForbidden();
}
include_once(TEMPLATES_PATH . '/footer.php');