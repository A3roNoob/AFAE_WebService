<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 07/04/2017
 * Time: 12:39
 */
require_once('resources/config.php');
require_once('resources/functions.php');
session_start();
$pagetitle = 'S\'inscrire &agrave; une foire';

include_once(TEMPLATES_PATH . '/header.php');
if(isset($_SESSION['userobject']) && $_SESSION['userobject']->checkRank(Rank::loadFromId(1)))
{
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $part = new Participant();
        $part->createParticipant(test_input($_POST['foire']), $_SESSION['userobject']->id());
        if($part->insertIntoDb())
        {
            echo '<div class="alert alert-success">Vous &ecirc;tes inscrit dans cette foire ! Veuillez attendre votre autorisation si elle n\'apparait pas dans vos foires.</div>';
        }
        else{
            echo '<div class="alert alert-warning">Vous &ecirc;tes d&eacute;j&agrave; inscrit dans cette foire ! Veuillez attendre votre autorisation si elle n\'apparait pas dans vos foires.</div>';

        }

    }else{
        $foireMan = new FoireManager();
        $foireMan->loadFromDbNonParticipant($_SESSION['userobject']->id());
        if(!empty($foireMan->foires())) {

            ?>
            <form action="/enregistrement/foire/" method="POST" class="form-inline">
                <div class="form-group">
                    <label for="foire">S'inscrire &agrave;&nbsp;: </label>
                    <select id="foire" name="foire" class="form-control">
                        <?php

                        foreach ($foireMan->foires() as $foire) {
                            echo '<option value="' . $foire->idFoire() . '">' . $foire->nomFoire() . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <input type="submit" class="btn btn-default" value="S'inscrire"/>
            </form>
            <?php
        }
        else
        {
            echo '<div class="alert alert-info">Vous &ecirc;tes inscrits &agrave toutes les foires.<br/> Si elle n\'apparaissent pas, veuillez attendre d\'&ecirc;tre autoris&eacute; par les administrateurs des foires en question.</div>';
        }
    }
}
else
{
    accessForbidden();
}
include_once(TEMPLATES_PATH . '/footer.php');