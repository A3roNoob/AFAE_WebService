<div class="messagebox">
    <form action="#" method="post">
        <label for="login">Identifiant : </label>
        <span class="error">* <?php echo $loginErr ;?></span><br/>
        <input type="text" name="login"/><br/>
        <label for="password">Mot de passe : </label>
        <span class="error">* <?php echo $passwordErr;?></span><br/>
        <input type="password" name="password"/><br/>
        <input type="submit" value="Connexion"><br/>
    </form>
</div>