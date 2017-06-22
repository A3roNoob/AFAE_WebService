<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 03/04/2017
 * Time: 16:49
 */
require_once("resources/config.php");
require_once("resources/functions.php");
session_start();

$pagetitle = 'Liste des objets';

include_once(TEMPLATES_PATH . '/header.php');
if (isset($_SESSION['userobject']) && $_SESSION['userobject']->checkRank(Rank::loadFromId(1))) {

    if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['foire'])) {

        $foireMan = new FoireManager();
        $foireMan->loadFromDbParticipant($_SESSION['userobject']->id());

        ?>
        <form method="GET" class="form-inline">
            <div class="form-group">
                <label for="foire">S&eacute;lectionner&nbsp;: </label>
                <select id="foire" name="foire" class="form-control">
                    <?php
                    $foireMan = new FoireManager();
                    $foireMan->loadFromDbParticipant($_SESSION['userobject']->id());
                    foreach ($foireMan->foires() as $foire) {
                        $select = $foire->idFoire() == test_input($_GET['foire']) ? "selected" : "";
                        echo '<option value="' . $foire->idFoire() . '" ' . $selec . '>' . $foire->nomFoire() . '</option>';
                    }
                    ?>
                </select>
            </div>
            <input type="button" class="btn btn-default"
                   onclick="window.location.href= '/liste/objet/foire/'+ document.getElementById('foire').value + '/'""
            value="S&eacute;lectionner"/>
            <br/>
        </form>
        <?php
        $parti = Participant::loadFromDb(test_input($_GET['foire']), $_SESSION['userobject']->id());

        $idFoire = test_input($_GET['foire']);
        $foire = Foire::loadFromDb($idFoire);
        if (is_a($foire, "Foire")) {


            if (is_a($parti, "Participant") && $parti->valide()) {


                if (isset($_GET['objet']) && isset($_GET['foire']) && $foire->dateFinSaisie() > today() && Object::appartient(test_input($_GET['objet']), $_SESSION['userobject']->id(), test_input($_GET['foire']))) {
                    echo "<br />";
                    $retour = ObjectManager::deleteItem($_SESSION['userobject']->id(), (int)$idFoire, (int)test_input($_GET['objet']));
                    if ($retour) {
                        echo "<div class='alert alert-success'>Votre objet a &eacute;t&eacute; supprim&eacute; avec succ&egrave;s</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Votre objet n'a pas pu &ecirc;tre supprim&eacute;</div>";
                    }
                } else if (isset($_GET['objet']) && isset($_GET['foire']) && $foire->dateFinSaisie() < today()) {
                    echo "<br />";
                    echo "<div class='alert alert-warning'>Les saisies sont termin&eacute;es, vous ne pouvez plus supprimer d'objets.</div>";
                } else if (isset($_GET['objet']) && isset($_GET['foire']) && !Object::appartient(test_input($_GET['objet']), $_SESSION['userobject']->id(), test_input($_GET['foire']))) {
                    echo "<br />";
                    echo "<div class='alert alert-warning'>Cet objet n'existe pas !</div>";
                }

                echo "<div class='page-header'>Nom de la foire&nbsp;:&nbsp" . $foire->nomFoire() . "</div>";


                $objMan = new ObjectManager();
                $objMan->loadObjectsFromUserFoire($_SESSION['userobject'], $foire->idFoire());
                ?>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>Num Objet</th>
                            <th>Description</th>
                            <th>Taille</th>
                            <th>Nb Items</th>
                            <th>Prix</th>
                            <th>Baisse autorisée</th>
                            <th>Vendu</th>
                            <th>Prix Vendu</th>
                            <th>Modifier</th>
                            <th>Supprimer</th>
                        </tr>
                        <?php
                        global $objMan;
                        foreach ($objMan->objets() as $userObject) {
                            echo "<tr>";
                            echo "<td>";
                            echo $userObject->numItem();
                            echo "</td>";

                            echo "<td>";
                            echo $userObject->desc();
                            echo "</td>";

                            echo "<td>";
                            echo $userObject->taille();
                            echo "</td>";

                            echo "<td>";
                            echo $userObject->nbItems();
                            echo "</td>";

                            echo "<td>";
                            echo $userObject->prix();
                            echo "</td>";

                            echo "<td>";
                            echo $userObject->baisse() ? "oui" : "non";
                            echo "</td>";

                            echo "<td>";
                            echo $userObject->vendu() ? "oui" : "non";
                            echo "</td>";

                            echo "<td>";///Prix Vendu
                            echo ($userObject->vendu()) ? "Pas implémenté" : "Non Vendu";
                            echo "</td>";

                            echo "<td>";
                            echo ($userObject->verrou() || $userObject->vendu() ) ? "" : "<button type='button' onclick=\"window.location.href = '/modifier/foire/" . $userObject->idFoire() . "/objet/" . $userObject->numItem() . "/'\" class='btn btn-primary'>Modifier</button>";
                            echo "</td>";

                            echo "<td>";
                            echo ($userObject->verrou() || $userObject->vendu()) ? "" : "<button type='button' onclick=\"window.location.href = '/liste/objet/foire/" . $userObject->idFoire() . "/supprimer/" . $userObject->numItem() . "/'\" class='btn btn-primary'>Supprimer</button>";
                            echo "</td>";
                            echo "</tr>";
                        }

                        ?>
                    </table>
                </div>

                <?php
            } else {
                echo "<br /><div class='alert alert-warning'>Vous n'avez pas acc&egrave;s &agrave; cette foire.</div>";
            }
        } else {
            echo "<br /><div class='alert alert-danger'>Cette foire n'existe pas.</div>";
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
                       onclick="window.location.href= '/liste/objet/foire/'+ document.getElementById('foire').value + '/'""
                value="S&eacute;lectionner"/>
                <br/>
            </form>
            <?php
        } else {
            echo '<div class="alert alert-info">Vous n\'&ecirc;tes inscrit/accept&eacute; dans aucune foire. <br />Revenez plus tard si vous attendez une confirmation.</div>';
        }

    }
} else {
    accessForbidden();
}
include_once(TEMPLATES_PATH . '/footer.php');
?>