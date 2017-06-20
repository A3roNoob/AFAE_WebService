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
        if(compareDate($foire->dateFinFoire(),today())):
    ?>
        <div class="well">
            <p><b><?php echo $foire->nomFoire(); ?></b></p>
            <p><b>D&eacute;but de la foire:&nbsp;</b><?php echo $foire->dateDebutFoire();  ?></p>
            <p><b>Fin de la foire:&nbsp;</b><?php echo $foire->dateFinFoire();?></p>
        </div>
        <h2>A propos de ce site</h2>
            <p>Ce site à été créé pour l'Association des familles d'Arpajon et ses environs (AFAE). Sa réalisation entre dans le cadre du projet tutoré de S2 de l'IUT d'Orsay.</p>
            <p>Le maître d'ouvrage du projet de ce site est l'AFAE. <br>
            Le maître d'oeuvre est le groupe composé des personnes suivantes: <br></p>
            <ul>
                <li class="custom-bullet">Guillaume Boutry, guillaume.boutry@u-psud.fr</li>
                <li class="custom-bullet">Adam Desiles, adam.desiles@u-psud.fr</li>
                <li class="custom-bullet">Victor Leignadier, victor.leignadier@u-psud.fr</li>
                <li class="custom-bullet">Mehdi Mjahad, mehdi-gabriel.mjahad@u-psud.fr</li>
            </ul>
            <p>Le projet est tutoré par M. Julien Ciaffi, jciaffi@gmail.com</p>
            <p>Le site utilise les technologies suivantes: <br></p>
            <ul>
                <li class="custom-bullet">Serveur Apache 2.2 avec PHP 5.6</li>
                <li class="custom-bullet"><a href="http://getbootstrap.com/">Bootstrap 3.3.7</a></li>
                <li class="custom-bullet"><a href="https://bootswatch.com/paper/">Theme Bootstrap Paper</a></li>
                <li class="custom-bullet"><a href="http://www.freenom.com/fr/index.html">Nom de domaine .tk chez Freenom</a></li>
                <li class="custom-bullet"><a href="https://letsencrypt.org/">Certificat SSL Let's Encrypt</a></li>
                <li class="custom-bullet"><a href="https://mariadb.org/">Base de données MariaDB</a></li>
            </ul>

    <?php
            endif;
    EndForEach;
}

include_once(TEMPLATES_PATH.'/footer.php');

?>