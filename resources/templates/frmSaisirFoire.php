<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 13/04/2017
 * Time: 04:07
 */

?>

<form action="/saisir/foire/" method="POST" class="col-xs-6">
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
