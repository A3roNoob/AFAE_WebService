<?php
require_once("resources/config.php");
require_once("resources/functions.php");
session_start();

///TODO un vendeur revendique association -> générations d'un user association avec mdp par défaut
$pagetitle = 'Accueil';
include_once(TEMPLATES_PATH.'/header.php');

if(isset($_GET['code']))
{
    include(ERROR_PATH . '/' . $_GET['code'] .'.php');
}else
{
    $foireMan = new FoireManager();
    $foireMan->loadFoiresFromDb();

    foreach($foireMan->foires() as $foire):
        if(compareDate(convertDateFromSql($foire->dateFinFoire()),today())):
    ?>
        <div class="well">
            <p><b><?php echo $foire->nomFoire(); ?></b></p>
            <p><b>D&eacute;but de la foire:&nbsp;</b><?php echo convertDateFromSql($foire->dateDebutFoire());  ?></p>
            <p><b>Fin de la foire:&nbsp;</b><?php echo convertDateFromSql($foire->dateFinFoire());?></p>
        </div>


    <?php
            endif;
    EndForEach;
}

include_once(TEMPLATES_PATH.'/footer.php');

?>