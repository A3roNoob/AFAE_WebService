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
            <form action="saisiefoire.php" method="POST" class="col-xs-6">
                <div class="form-group">
                    <label for="name">Nom de la foire&nbsp;:</label>
                    <span class="alert alert-danger">* <?php echo $foireNameErr;?></span>
                    <input type="text" class="form-control" name="name" id="name"/>
                </div>

                <?php
                if ($_SESSION['userobject']->checkRank(Rank::loadFromName('Super Administrateur'))) {

                    $assocList = new AssociationManager();
                    $assocList->loadAssocFromDb();
                    $userList = new UserManager();

                    ?>
                    <div class="form-group">
                        <label for="idassoc" class="control-label">Association&nbsp;:</label>
                        <select id="idassoc" class="form-control" name="idassoc">
                            <?php
                            foreach ($assocList->assoc() as $assoc) {
                                echo '<option value="' . $assoc->idAssoc() . '">' . (is_null($assoc->sigle()) ? $assoc->nomAssoc() : $assoc->sigle()) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="idadmin" class="control-label">Administrateur&nbsp;:</label>
                        <select id="idadmin" class="form-control" name="idadmin">
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
                    <div class="input-group date" data-provide="datepicker">
                        <input type="text" class="form-control" id="datedebut" name="datedebut">
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="datefin">Date fin&nbsp;:</label>
                    <div class="input-group date" data-provide="datepicker">
                        <input type="text" class="form-control" id="datefin" name="datefin">
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </div>
                    </div>
                </div>
                <!--<div>
                    <label for="datedebut">Date d&eacute;but&nbsp;:</label>
                    <input type="datetime-local" id="datedebut" name="datedebut" />
                </div>
                <div>
                    <label for="datefin">Date fin&nbsp;:</label>
                    <input type="datetime-local" id="datefin" name="datefin"/>
                </div>-->
                <input type="submit" class="btn btn-default" value="Cr&eacute;er foire"/>
            </form>
        <?php
    }
} else {
    accessForbidden();
}
include_once(TEMPLATES_PATH . '/footer.php');