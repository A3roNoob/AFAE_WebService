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
$idFoire = 0;
if (isset($_SESSION['userobject']) && $_SESSION['userobject']->checkRank(Rank::loadFromName('Administrateur de foire'))) {
    $userList = new UserManager();
//Si l'utilisateur est superAdmin:
    ?>
    <div class="table-responsive">
    <?php
    if ($_SESSION['userobject']->checkRank(Rank::loadFromName('Super Administrateur'))) {
        $userList->loadUsersFromDb();
    } else // Si l'utilisateur est administrateur de foire
    {
        $foireList = new FoireManager();
        $foireList->loadFoiresFromFoireAdmin($_SESSION['userobject']);
        ?>

        <form class="form-inline" action="listeutilisateur.php" method="POST">
            <div class="form-group">
                <label for="foire">Sélectionner une foire&nbsp;:</label>
                <select name="foire" id="foire">
                    <?php
                    foreach ($foireList->foires() as $foire) {
                        echo "<option value='" . $foire->idFoire() . "'>" . $foire->nomFoire() . "</option>";
                    }
                    ?>
                </select>
            </div>
            <input type="submit" class="btn btn-default" value="Selectionner"/>
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['foire'])) {
            $foire = test_input($_POST['foire']);
            $userList->loadUsersFromFoire($foire);
            $f = Foire::loadFromDb($foire);
            echo "<span id='nomfoire'>" . (is_null($f)) ? "" : $f->nomFoire() . "</span>";
            $idFoire = $f->idFoire();
            $_SESSION['userlist'] = $userList;
        }
        if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['idfoire']) && isset($_GET['iduser']))
        {
            $idUser = test_input($_GET['iduser']);
            $idFoire = test_input($_GET['idfoire']);
            if(Participant::validerPart($idUser, $idFoire))
            {
                echo '<div class="alert alert-success">Vendeur autoris&eacute; dans cette foire.</div>';
            }
            else
            {
                echo '<div class="alert alert-danger">Erreur lors de l\'autorisation du vendeur. <br />Veuillez r&eacute;essayer plus tard.</div>';
            }
            if(isset($_SESSION['userlist']))
            {
                $userList = $_SESSION['userlist'];
            }
        }
    }
    if ($userList->users() != NULL) {
        ?>
        <table class="table table-striped">
            <tr>
                <th>Id</th>
                <th>Nom</th>
                <th>Pr&eacute;nom</th>
                <th>Adresse</th>
                <th>Ville</th>
                <th>Code Postal</th>
                <th>T&eacute;l&eacute;phone</th>
                <th>E-mail</th>
                <?php if ($_SESSION['userobject']->checkRank(Rank::loadFromName('Super Administrateur')))
                    echo '<th>Modifier</th>';
                else
                    echo '<th>Valider</th>' ?>
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
                    echo '<input type="button" class="btn btn-default" value="Modifier"/>';
                    echo "</td>";
                } else {
                    if ($idFoire != 0) {

                        echo '<td>';
                        $part = Participant::loadFromDb($idFoire, $user->id());
                        if (!$part->valide()) {
                            echo '<form method="GET" action="listeutilisateur.php">
                                <input name="iduser" value="' . $user->id() . '" type="hidden"/>
                                <input name="idfoire" value="' . $idFoire . '" type="hidden" />
                                <input type="submit" class="btn btn-default" value="Valider"/>
                          </form>';
                        }else
                        {
                            echo 'Valid&eacute;';
                        }
                        echo '</td>';
                    }
                }

                echo "</tr>";
            }

            ?>
        </table>
        </div>
        <?php
    }
} else {
    accessForbidden();
}
include_once(TEMPLATES_PATH . '/footer.php');