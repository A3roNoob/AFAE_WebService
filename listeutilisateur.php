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

        <form method="GET" class="form-inline">
            <div class="form-group">
                <label for="foire">S&eacute;lectionner&nbsp;: </label>
                <select id="foire" name="foire" class="form-control">
                    <?php
                    $foireMan = new FoireManager();
                    $foireMan->loadFromDbParticipant($_SESSION['userobject']->id());
                    foreach ($foireMan->foires() as $foire) {
                        if ($foire->idFoire() == test_input($_GET['foire']))
                            $selec = "selected";
                        else
                            $selec = "";

                        echo '<option value="' . $foire->idFoire() . '" ' . $selec . '>' . $foire->nomFoire() . '</option>';
                    }
                    ?>
                </select>
            </div>
            <input type="button" class="btn btn-default"
                   onclick="window.location.href= '/liste/vendeur/foire/'+ document.getElementById('foire').value + '/'""
            value="S&eacute;lectionner"/>
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['foire']) && !isset($_GET['iduser'])) {
            $foire = test_input($_GET['foire']);
            $userList->loadUsersFromFoire($foire);
            $f = Foire::loadFromDb($foire);
            echo "<span id='nomfoire'>" . (!is_a($f, "Foire")) ? "" : $f->nomFoire() . "</span>";
            $idFoire = $f->idFoire();
            $_SESSION['userlist'] = $userList;
            $_SESSION['foire'] = $f;
        }
        else if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['idfoire']) && isset($_GET['iduser'])) {
            $idUser = test_input($_GET['iduser']);
            $idFoire = test_input($_GET['idfoire']);
            $_SESSION['foire'] = Foire::loadFromDb($idFoire);
            if (Participant::validerPart($idUser, $idFoire)) {
                echo '<div class="alert alert-success">Vendeur autoris&eacute; dans cette foire.</div>';
            } else {
                echo '<div class="alert alert-danger">Erreur lors de l\'autorisation du vendeur. <br />Veuillez r&eacute;essayer plus tard.</div>';
            }
            if (isset($_SESSION['userlist'])) {
                $userList = $_SESSION['userlist'];
            }
        }
    }
    if ($userList->users() != NULL) {

    ?>
        <br/>
        <form action="/impression/etiquette/" method="post">
            <input type="submit" name="vendeur" value="Etiquette vendeur" onClick="custAlert('Attention, vous n\'avez pas séléctionné de vendeur, vous aurez toute la liste.')" />
            <input type="submit" name="objet" value="Etiquette objet" onClick="custAlert('Attention, vous n\'avez pas séléctionné de vendeur, vous aurez toute la liste.')" />
            <input type="submit" name="note" value="Note vendeur" onClick="custAlert('Attention, vous n\'avez pas séléctionné de vendeur, vous aurez toute la liste.')" />
            <input type="button" name="restant" value="Restant" onClick="window.location.href='/impression/restant/'" />
            <input type="button" name="bordereau" value="Bordereau" onClick="window.location.href='/impression/bordereau/'" />
            <table style="margin-top: 10px;" class="table table-striped">
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
                else {
                    echo '<th>Valider</th>';
                    echo '<th>S&eacute;lectionner</th>';
                } ?>
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
                    echo '<input type="button" class="btn btn-default" value="Modifier" onClick="window.location.href=\'/modif/user/'.$user->id().'/\'"/>';
                    echo "</td>";
                } else {
                    if ($idFoire != 0) {

                        echo '<td>';
                        $part = Participant::loadFromDb($idFoire, $user->id());
                        if (!$part->valide()) {
                            echo '<form>
                                <input id="iduser" name="iduser" value="' . $user->id() . '" type="hidden"/>
                                <input id="idfoire" name="idfoire" value="' . $idFoire . '" type="hidden" />
                                <input type="button" class="btn btn-default" onclick="window.location.href= \'/liste/vendeur/\' + document.getElementById(\'iduser\').value + \'/\' + document.getElementById(\'idfoire\').value + \'/\'" value="Valider"/>
                          </form>';
                        } else {
                            echo 'Valid&eacute;';
                        }
                        echo '</td>';
                        echo '<td>';
                        echo '<input type="checkbox" class="vendeur" name="vendeur_'.$user->id().'" value='.$user->id().' />';
                        echo '</td>';
                    }
                }

                echo "</tr>";
            }

            ?>
        </table>
        </form>
        </div>
        <script>
            function custAlert(string)
            {
                var check, nbCheck = 0;
                check = document.getElementsByClassName("vendeur");
                for(var i = 0; i < check.length; i++){
                    if( check[i].checked == true){
                        nbCheck++;
                    }
                }

                if (nbCheck == 0)
                    alert(string)
            }
        </script>
        <?php
    }
} else {
    accessForbidden();
}
include_once(TEMPLATES_PATH . '/footer.php');