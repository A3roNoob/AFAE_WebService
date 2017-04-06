<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 05/04/2017
 * Time: 14:05
 * Si vous êtes super admin: liste utilisateur
 * Si vous êtes administrateur de foire : liste des vendeurs participants à vos foire, classé par foire
 */

require_once("resources/config.php");
require_once("resources/functions.php");
session_start();

$pagetitle = 'Liste des utilisateurs';
include_once(TEMPLATES_PATH . '/header.php');
if(isset($_SESSION['userobject']) && $_SESSION['userobject']->checkRank(Rank::loadFromName('Administrateur de foire'))) {
    $userList = new UserManager();
//Si l'utilisateur est superAdmin:
    ?>
    <div id="container">
    <?php
    if ($_SESSION['userobject']->checkRank(Rank::loadFromName('Super Administrateur'))) {
        $userList->loadUsersFromDb();
    } else // Si l'utilisateur est administrateur de foire
    {
        $foireList = new FoireManager();
        $foireList->loadFoiresFromFoireAdmin($_SESSION['userobject']);
        ?>

        <form action="listeutilisateur.php" method="POST">
            <label for="foire">Sélectionner une foire&nbsp;:</label>
            <select name="foire" id="foire">
                <?php
                foreach ($foireList->foires() as $foire) {
                    echo "<option value='" . $foire->idFoire() . "'>" . $foire->nomFoire() . "</option>";
                }
                ?>
            </select>
            <input type="submit" value="Selectionner"/>
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $foire = test_input($_POST['foire']);
            $userList->loadUsersFromFoire($foire);
            $f=Foire::loadFromDb($foire);
            echo "<span id='nomfoire'>" . (is_null($f)) ? "" : $f->nomFoire() . "</span>";
        }
    }
    if ($userList->users() != NULL) {
        ?>
        <table>
            <tr>
                <th>Id</th>
                <th>Nom</th>
                <th>Pr&eacute;nom</th>
                <th>Adresse</th>
                <th>Ville</th>
                <th>Code Postal</th>
                <th>T&eacute;l&eacute;phone</th>
                <th>E-mail</th>
                <?php if ($_SESSION['userobject']->checkRank(Rank::loadFromName('Super Administrateur'))) echo '<th>Modifier</th>'; ?>
            </tr>
            <?php
            foreach ($userList->users() as $user) {
                echo "<tr>";

                echo "<td>";
                echo $user->id();
                echo "</td>";

                echo "<td>";
                echo $user->name();
                echo "</td>";

                echo "<td>";
                echo $user->fname();
                echo "</td>";

                echo "<td>";
                echo $user->address();
                echo "</td>";

                echo "<td>";
                echo $user->city();
                echo "</td>";

                echo "<td>";
                echo $user->codePostal();
                echo "</td>";

                echo "<td>";
                echo $user->phone();
                echo "</td>";

                echo "<td>";
                echo $user->email();
                echo "</td>";

                if ($_SESSION['userobject']->checkRank(Rank::loadFromName('Super Administrateur'))) {
                    echo "<td>";
                    echo '<input type="button" value="Modifier"/>';
                    echo "</td>";
                }

                echo "</tr>";
            }

            ?>
        </table>
        </div>
        <?php
    }
}
else
{
    accessForbidden();
}
include_once(TEMPLATES_PATH . '/footer.php');