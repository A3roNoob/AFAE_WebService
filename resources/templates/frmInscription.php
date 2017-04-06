<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 06/04/2017
 * Time: 21:08
 */
?>
<form action="inscription.php" method="POST" class="col-xs-6">
    <div class="<?php hasError($nameErr); ?> has-feedback form-group">
        <label class="col-sm-12 control-label" for="name">Nom&nbsp;:</label>
        <div class="col-sm-12">
            <input class="form-control" type="text"
                   value="<?php echo $name; ?>"
                   id="name" name="name" placeholder="nom"/>
            <?php spanError($nameErr); ?>
        </div>
    </div>

    <div class="<?php hasError($fNameErr); ?> has-feedback form-group">
        <label class="col-sm-12 control-label" for="firstname">Pr&eacute;nom&nbsp;:</label>
        <div class="col-sm-12">
            <input class="form-control" type="text"
                   value="<?php echo $fName; ?>"
                   id="firstname" name="firstname" placeholder="prénom"/>
            <?php spanError($fNameErr); ?>
        </div>
    </div>

    <div class="<?php hasError($loginErr); ?> has-feedback form-group">
        <label class="col-sm-12 control-label" for="login">Identifiant&nbsp;:</label>
        <div class="col-sm-12">
            <input class="form-control" type="text"
                   value="<?php echo $login; ?>"
                   id="login" name="login" placeholder="identifiant"/>
            <?php spanError($loginErr); ?>
        </div>
    </div>

    <div class="<?php hasError($passwordErr); ?> has-feedback form-group">
        <label class="col-sm-12 control-label" for="password">Mot de passe&nbsp;:</label>
        <div class="col-sm-12">
            <input class="form-control" type="password"
                   value="<?php echo $password; ?>"
                   id="password" name="password" placeholder="mot de passe"/>
            <?php spanError($passwordErr); ?>
        </div>
    </div>

    <div class="<?php hasError($emailErr); ?>  has-feedback form-group">
        <label class="col-sm-12 control-label" for="email">E-mail&nbsp;:</label>
        <div class="col-sm-12">
            <input class="form-control" type="text"
                   value="<?php echo $email; ?>"
                   id="email" name="email" placeholder="email"/>
            <?php spanError($emailErr); ?>
        </div>
    </div>

    <div class="<?php hasError($addressErr); ?> has-feedback form-group">
        <label class="col-sm-12 control-label" for="address">Num&eacute;ro et voirie&nbsp;</label>
        <div class="col-sm-12">
            <input class="form-control" type="text"
                   value="<?php echo $address; ?>"
                   id="address" name="address" placeholder="numéro et voirie"/>
            <?php spanError($addressErr); ?>
        </div>
    </div>

    <div class="<?php hasError($phoneErr); ?> has-feedback form-group">
        <label class="col-sm-12 control-label" for="phone">T&eacute;l&eacute;phone&nbsp;:</label>
        <div class="col-sm-12">
            <input class="form-control" type="text"
                   value="<?php echo $phone; ?>"
                   id="phone" name="phone" placeholder="téléphone"/>
            <?php spanError($phoneErr); ?>
        </div>
    </div>

    <div class="<?php hasError($cpErr); ?> has-feedback form-group">
        <label class="col-sm-12 control-label" for="cp">Code postal&nbsp;:</label>
        <div class="col-sm-12">
            <input class="form-control" type="text"
                   value="<?php echo $cp;?>" id="cp"
                   name="cp" placeholder="code postal"/>
            <?php spanError($cpErr); ?>
        </div>
    </div>

    <div class="<?php hasError($cityErr); ?> has-feedback form-group">
        <label class="col-sm-12 control-label" for="city">Ville&nbsp;:</label>
        <div class="col-sm-12">
            <input class="form-control" type="text"
                   value="<?php echo $city; ?>"
                   id="city" name="city" placeholder="ville"/>
            <?php spanError($cityErr); ?>
        </div>
    </div>
    <div class="col-sm-12">
        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </div>
</form>