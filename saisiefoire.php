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

$foireNameErr = "";
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
            $foireNameErr = "Nom obligatoire";
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
    else{
        ?>
        <div id="container">
            <form action="saisiefoire.php" method="POST">
                <div>
                    <label for="name">Nom de la foire&nbsp;:</label>
                    <span class="error">* <?php echo $foireNameErr;?></span>
                    <input type="text" name="name" id="name"/>
                </div>

                <?php
                if ($_SESSION['userobject']->checkRank(Rank::loadFromName('Super Administrateur'))) {

                    $assocList = new AssociationManager();
                    $assocList->loadAssocFromDb();
                    $userList = new UserManager();

                    ?>
                    <div>
                        <label for="idassoc">Association&nbsp;:</label>
                        <select id="idassoc" name="idassoc">
                            <?php
                            foreach ($assocList->assoc() as $assoc) {
                                echo '<option value="' . $assoc->idAssoc() . '">' . (is_null($assoc->sigle()) ? $assoc->nomAssoc() : $assoc->sigle()) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label for="idadmin">Administrateur&nbsp;:</label>
                        <select id="idadmin" name="idadmin">
                            <?php
                            $userList->loadSuperAdmins();
                            foreach ($userList->users() as $user) {
                                echo '<option value="' . $user->id() . '">' . $user->name() . ' ' . $user->fname() . '</option>';
                            }
                            $userList->loadFoireAdminUser();
                            foreach ($userList->users() as $user) {
                                echo '<option value="' . $user->id() . '">' . $user->name() . ' ' . $user->fname() . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <?php
                } else {
                    $assoc = Association::loadFromAdmin($_SESSION['userobject']->id());
                    ?>
                    <div>
                        <label for="idassoc">Association&nbsp;:</label>
                        <span><?php echo $assoc->nomAssoc() ?></span>
                    </div>
                    <div>
                        <label for="idadmin">Administrateur&nbsp;:</label>
                        <span><?php echo $_SESSION['userobject']->name() . ' ' . $_SESSION['userobject']->fname(); ?></span>
                    </div>
                    <?php

                }
                ?>
                <div>
                <label for="datedebut">Date d&eacute;but&nbsp;:</label>
                <input type="datetime-local" id="datedebut" name="datedebut" />
                </div>
                <div>
                    <label for="datefin">Date fin&nbsp;:</label>
                    <input type="datetime-local" id="datefin" name="datefin"/>
                </div>
                <input type="submit" value="Cr&eacute;er foire"/>
            </form>
        </div>
        <?php
    }
} else {
    accessForbidden();
}
include_once(TEMPLATES_PATH . '/footer.php');