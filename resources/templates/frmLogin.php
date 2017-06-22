<form action="/connexion/" method="post">
    <div class="form-group">
        <label for="login">Identifiant&nbsp;: </label>
        <?php
        $class = '';
        if (!empty($loginErr)) $class = 'alert alert-danger';
        echo '<p class="' . $class . '" style="color: red;" role="alert">* ' . $loginErr . '</p>';
        ?>
        <input type="text" class="form-control" name="login" placeholder="Identifiant"/>
    </div>

    <div class="form-group">
        <label for="password">Mot de passe&nbsp;: </label>
        <?php
        if (!empty($passwordErr)) $class = 'alert alert-danger';
        echo '<p class="' . $class . '" style="color:red;" role="alert">* ' . $passwordErr . '</p>';
        ?>
        <input type="password" class="form-control" name="password" placeholder="Mot de passe"/>
    </div>

    <button type="submit" class="btn btn-default">Connexion</button>
</form>
