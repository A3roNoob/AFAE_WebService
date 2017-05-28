<?php if (!empty($dateErr)) echo "<span class=\"alert alert-danger\">" . $dateErr . "</span>" ?>

<form action="/saisir/foire/" method="POST" class="col-xs-6">
    <div class="form-group">
        <label for="name">Nom de la foire&nbsp;:</label>
        <?php if(!empty($foireNameErr)) echo "<span class=\"alert alert-danger\">" . $foireNameErr . "</span>"?>
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
        <label class="control-label" for="prixbaisse">Prix minimum de baisse&nbsp;: </label>
        <div class="input-group">
            <input type="number" class="form-control" placeholder="0.00" min="0.00" step="0.01" id="prixbaisse"
                   name="prixbaisse"/>
            <span class="input-group-addon">€</span>
        </div>
    </div>
    <div>
        <label class="control-label" for="maxobj">Nombre d'objets max par vendeur&nbsp;: </label>
        <div class="input-group">
            <input type="number" class="form-control" placeholder="0" min="0" step="1" id="maxobj"
                   name="maxobj"/>
        </div>
    </div>
    <div>
        <label class="control-label" for="maxobjassoc">Nombre d'objets max par association&nbsp;: </label>
        <div class="input-group">
            <input type="number" class="form-control" placeholder="0" min="0" step="1" id="maxobjassoc"
                   name="maxobjassoc"/>
        </div>
    </div>
    <div>
        <label for="datedebut">Date d&eacute;but des saisies&nbsp;:</label>
        <div class="input-group date" data-provide="datepicker" data-date-format="dd/mm/yyyy">
            <input type="text" class="form-control" id="datedebutsaisie" name="datedebutsaisie">
            <div class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </div>
        </div>
    </div>
    <div>
        <label for="datefin">Date fin des saisies&nbsp;:</label>
        <div class="input-group date" data-provide="datepicker" data-date-format="dd/mm/yyyy">
            <input type="text" class="form-control" id="datefinsaisie" name="datefinsaisie">
            <div class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </div>
        </div>
    </div>
    <div>
        <label for="datedebutfoire">Date début de la foire&nbsp;:</label>
        <div class="input-group date" data-provide="datepicker" data-date-format="dd/mm/yyyy">
            <input type="text" class="form-control" id="datedebutfoire" name="datedebutfoire">
            <div class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </div>
        </div>
    </div>
    <div>
        <label for="datefinfoire">Date fin de la foire&nbsp;:</label>
        <div class="input-group date" data-provide="datepicker" data-date-format="dd/mm/yyyy">
            <input type="text" class="form-control" id="datefinfoire" name="datefinfoire">
            <div class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </div>
        </div>
    </div>

    <br />
    <input type="submit" class="btn btn-default" value="Cr&eacute;er foire"/>
</form>
