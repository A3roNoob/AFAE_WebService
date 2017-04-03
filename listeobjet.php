<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 03/04/2017
 * Time: 16:49
 */
require_once("resources/config.php");
require_once("resources/functions.php");
require(CLASS_PATH."/User.php");
session_start();

$pagetitle = 'Liste des objets';
$level = Rank::loadFromName("Vendeur");

require_once(TEMPLATES_PATH.'/header.php');
$objMan = new ObjectManager($_SESSION['userobject']);
$userObjects = $objMan->objets();
//var_dump($userObjects);
?>
<div id="container">
    <table>
        <tr>
            <th>Num Objet</th>
            <th>Description</th>
            <th>Taille</th>
            <th>Nb Items</th>
            <th>Prix</th>
            <th>Baisse autorisée</th>
            <th>Vendu</th>
            <th>Prix Vendu</th>
        </tr>
        <?php
        global $userObjects;
        foreach($userObjects as $userObject)
        {
            echo "<tr>";

            echo "<td>";
            echo $userObject['numitem'];
            echo "</td>";

            echo "<td>";
            echo $userObject['description'];
            echo "</td>";

            echo "<td>";
            echo $userObject['taille'];
            echo "</td>";

            echo "<td>";
            echo $userObject['nbitem'];
            echo "</td>";

            echo "<td>";
            echo $userObject['prix'];
            echo "</td>";

            echo "<td>";
            echo $userObject['baisse'] ? "oui" : "non";
            echo "</td>";

            echo "<td>";
            echo $userObject['vendu'] ? "oui" : "non";
            echo "</td>";

            echo "<td>";
            if ($userObject['vendu'])
                echo "Pas implémenté";
            else
                echo "Non Vendu";
            echo "</td>";

            echo "</tr>";
        }

        ?>
    </table>
</div>

<?php
require_once(TEMPLATES_PATH.'/footer.php');
?>