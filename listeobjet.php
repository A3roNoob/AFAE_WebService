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
if(isset($_SESSION['userobject']) && $_SESSION['userobject']->checkRank(Rank::loadFromId(1))) {


    $objMan = new ObjectManager();
    $objMan->loadObjectsFromUserFoire($_SESSION['userobject'], Foire::loadFromDb(1));
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
                echo ($userObject->verrou()) ? "" : "<button type='button' class='btn btn-primary'>Modifier</button>";
                echo "</td>";

                echo "<td>";
                echo  ($userObject->verrou()) ? "" : "<button type='button' class='btn btn-primary'>Supprimer</button>";
                echo "</td>";
                echo "</tr>";
            }

            ?>
        </table>
    </div>

    <?php
}
else
{
    accessForbidden();
}
include_once(TEMPLATES_PATH . '/footer.php');
?>