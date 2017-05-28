<form action="/perso/infos/" method="POST" class="col-xs-6 ">
    <div class="<?php hasError($nameErr); ?> has-feedback form-group">
        <label class="col-sm-12 control-label" for="name">Nom&nbsp;:</label>
        <div class="col-sm-12">
            <input class="form-control" type="text"
                   value="<?php echo $user->name(); ?>"
                   id="name" name="name" placeholder="nom"/>
            <?php spanError($nameErr); ?>
        </div>
    </div>

    <div class="<?php hasError($fNameErr); ?> has-feedback form-group">
        <label class="col-sm-12 control-label" for="firstname">Pr&eacute;nom&nbsp;:</label>
        <div class="col-sm-12">
            <input class="form-control" type="text"
                   value="<?php echo $user->fname(); ?>"
                   id="firstname" name="firstname" placeholder="prénom"/>
            <?php spanError($fNameErr); ?>
        </div>
    </div>

    <div class="<?php hasError($emailErr); ?>  has-feedback form-group">
        <label class="col-sm-12 control-label" for="email">E-mail&nbsp;:</label>
        <div class="col-sm-12">
            <input class="form-control" type="text"
                   value="<?php echo $user->email(); ?>"
                   id="email" name="email" placeholder="email"/>
            <?php spanError($emailErr); ?>
        </div>
    </div>

    <div class="<?php hasError($addressErr); ?> has-feedback form-group">
        <label class="col-sm-12 control-label" for="address">Num&eacute;ro et voirie&nbsp;</label>
        <div class="col-sm-12">
            <input class="form-control" type="text"
                   value="<?php echo $user->address(); ?>"
                   id="address" name="address" placeholder="numéro et voirie"/>
            <?php spanError($addressErr); ?>
        </div>
    </div>

    <div class="<?php hasError($phoneErr); ?> has-feedback form-group">
        <label class="col-sm-12 control-label" for="phone">T&eacute;l&eacute;phone&nbsp;:</label>
        <div class="col-sm-12">
            <input class="form-control" type="text"
                   value="<?php echo $user->phone(); ?>"
                   id="phone" name="phone" placeholder="téléphone"/>
            <?php spanError($phoneErr); ?>
        </div>
    </div>

    <div class="<?php hasError($cpErr); ?> has-feedback form-group">
        <label class="col-sm-12 control-label" for="cp">Code postal&nbsp;:</label>
        <div class="col-sm-12">
            <input class="form-control" type="text"
                   value="<?php echo $user->codePostal();?>" id="cp"
                   name="cp" placeholder="code postal"/>
            <?php spanError($cpErr); ?>
        </div>
    </div>

    <div class="<?php hasError($cityErr); ?> has-feedback form-group">
        <label class="col-sm-12 control-label" for="city">Ville&nbsp;:</label>
        <div class="col-sm-12">
            <input class="form-control" type="text"
                   value="<?php echo $user->city(); ?>"
                   id="city" name="city" placeholder="ville"/>
            <?php spanError($cityErr); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-12 control-label" for="baisse">Baisse du prix autoris&eacute;e&nbsp;:</label>
        <div class="col-sm-12">
            <input class="form-control" type="checkbox"
                    <?php if ($user->drop()) echo "checked"; ?>
                   id="baisse" name="baisse" placeholder="ville"/>
        </div>
    </div>
    <div class="col-sm-12">
        <br />

        <button type="submit" class="btn btn-primary">Modifier</button>
    </div>
</form>