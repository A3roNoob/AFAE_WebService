<form action="/modifier/objet/" method="POST" class="col-xs-6">
    <input type="hidden" name="numitem" value="<?php echo test_input($_GET['objet'])?>" />
    <input type="hidden" name="idfoire" value="<?php echo test_input($_GET['foire']);?>" />
    <div class="form-group <?php hasError($descErr); ?>">
        <label class="control-label" for="desc">Description&nbsp;: </label>
        <input type="text" class="form-control" id="desc" name="desc"/>
        <?php spanError($descErr); ?>
    </div>
    <div class="form-group <?php hasError($tailleErr); ?>">
        <label class="control-label" for="taille">Taille&nbsp;: </label>
        <input type="text" class="form-control" id="taille" name="taille"/>
        <?php spanError($tailleErr); ?>
    </div>
    <div class="form-group <?php hasError($prixErr); ?>">
        <label class="control-label" for="prix">Prix&nbsp;: </label>
        <div class="input-group">
            <input type="number" class="form-control" placeholder="0.00" min="0.00" step="0.01" id="prix"
                   name="prix"/>
            <span class="input-group-addon">€</span>
        </div>


        <?php spanError($prixErr); ?>
    </div>
    <div class="form-group <?php hasError($nbItemErr); ?>">
        <label for="nbitem" class="control-label" title="Nombre d'objets composant votre item">Nombre
            d'objets&nbsp;:</label>
        <input type="number" class="form-control" name="nbitem" id="nbitem" min="1" placeholder="1"/>
        <?php spanError($nbItemErr); ?>
    </div>
    <div class="form-group">
        <label class="control-label" for="baisse">Baisse du prix ?<input type="checkbox" class="checkbox"
                                                                         name="baisse"
                <?php if ($_SESSION['userobject']->drop())
                    echo "checked"; ?>
                                                                         id="baisse"/></label>
    </div>
    <input type="submit" class="btn btn-default" value="Modifier"/>
</form>
