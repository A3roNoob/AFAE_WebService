<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 03/04/2017
 * Time: 13:12
 */
$pagetitle = 'Inscription';

require_once("resources/config.php");
require(CLASS_PATH . "/User.php");
session_start();

$nameBool = true;
$fNameBool = true;
$loginBool = true;
$passwordBool = true;
$emailBool = true;
$addressBool = true;
$cpBool = true;
$cityBool = true;
$phoneBool = true;

require_once(TEMPLATES_PATH . '/header.php');


if (isset($_SESSION['userobject']) || isset($_SESSION['inscrit']) && $_SESSION['inscrit'] == true) {
    ?>
    <div class="messagebox">Vous &ecirc;tes d&eacute;j&agrave; connect&eacute; !<br/> Vous ne pouvez pas vous inscrire
        de nouveau !
    </div>
    <?php
} else {
    //On échappe les éventuelles balises html que l'utilisateur aurait pu entrer
    if (isset($_POST['name'])) {
        $_POST['name'] = htmlspecialchars($_POST['name']);
        $_POST['firstname'] = htmlspecialchars($_POST['firstname']);
        $_POST['login'] = htmlspecialchars($_POST['login']);
        $_POST['password'] = htmlspecialchars($_POST['password']);
        $_POST['email'] = htmlspecialchars($_POST['email']);
        $_POST['address'] = htmlspecialchars($_POST['address']);
        $_POST['cp'] = htmlspecialchars($_POST['cp']);
        $_POST['city'] = htmlspecialchars($_POST['city']);
    }


    if (isset($_POST['name']))
        $nameBool = (!empty($_POST['name']));
    if (isset($_POST['firstname']))
        $fNameBool = (!empty($_POST['firstname']));
    if (isset($_POST['login']))
        $loginBool = (!empty($_POST['login']));
    if (isset($_POST['password']))
        $passwordBool = (!empty($_POST['password']));
    if (isset($_POST['email']))
        $emailBool = (!empty($_POST['email']));
    if (isset($_POST['address']))
        $addressBool = (!empty($_POST['address']));
    if (isset($_POST['cp']))
        $cpBool = (!empty($_POST['cp']));
    if (isset($_POST['city']))
        $cityBool = (!empty($_POST['city']));
    if (isset($_POST['phone']))
        $phoneBool = (isset($_POST['phone']) && !empty($_POST['phone']));

    if (isset($_POST['NAME']) && isset($_POST['firstname']) && $nameBool && $fNameBool && $loginBool && $passwordBool && $emailBool && $addressBool && $cpBool && $cityBool && $phoneBool) {
        //$name, $fname, $address, $cp, $city, $phone, $drop, $lock, $rank, $email
        $user = User::createUser($_POST['name'], $_POST['firstname'], $_POST['address'], $_POST['cp'], $_POST['city'], $_POST['phone'], false, false, Rank::loadFromId($config['default_user']), $_POST['email']);
        try {
            $user->insertIntoDb($_POST['login'], $_POST['password']);
        } catch (Exception $e) {
            //ON essaye d'entrer un login/email déjà existant
            if ($e->getCode() == 23000) {
                $error = explode("'", $e->getMessage());
                if ($error[3] == "login") {
                    echo "<div class='messagebox'>Ce login existe d&eacute;j&agrave;</div>";
                } else if ($error[3] == "email") {
                    echo "<div class='messagebox'>Cet email existe d&eacute;j&agrave;</div>";
                }
            } else {
                echo "<div class='messagebox'>$e->getMessage();</div>";
            }
        }
        $_SESSION['userobject'] = $user;

    }
    ?>
    <div id="inscription-form">
        <form action="inscription.php" method="POST">
            <div>
                <label class="<?php echo wrong("name") ?>" for="name">Nom&nbsp;:</label>
                <input class="<?php echo wrong("name") ?>" type="text"
                       value="<?php if (isset($_POST['name']) && wrong("name") != "wrong") echo $_POST['name']; ?>"
                       id="name" name="name"/>
            </div>

            <div>
                <label class="<?php echo wrong("firstname") ?>" for="firstname">Pr&eacute;nom&nbsp;:</label>
                <input class="<?php echo wrong("firstname") ?>" type="text"
                       value="<?php if (isset($_POST['firstname']) && wrong("firstname") != "wrong") echo $_POST['firstname']; ?>"
                       id="firstname" name="firstname"/>
            </div>

            <div>
                <label class="<?php echo wrong("login") ?>" for="login">Identifiant&nbsp;:</label>
                <input class="<?php echo wrong("login") ?>" type="text"
                       value="<?php if (isset($_POST['login']) && wrong("login") != "wrong") echo $_POST['login']; ?>"
                       id="login" name="login"/>
            </div>

            <div>
                <label class="<?php echo wrong("password") ?>" for="password">Mot de passe&nbsp;:</label>
                <input class="<?php echo wrong("password") ?>" type="password"
                       value="<?php if (isset($_POST['password']) && wrong("password") != "wrong") echo $_POST['password']; ?>"
                       id="password" name="password"/>
            </div>

            <div>
                <label class="<?php echo wrong("email") ?>" for="email">E-mail&nbsp;:</label>
                <input class="<?php echo wrong("email") ?>" type="text"
                       value="<?php if (isset($_POST['email']) && wrong("email") != "wrong") echo $_POST['email']; ?>"
                       id="email" name="email"/>
            </div>

            <div>
                <label class="<?php echo wrong("address") ?>" for="address">Num&eacute;ro et voirie&nbsp;</label>
                <input class="<?php echo wrong("address") ?>" type="text"
                       value="<?php if (isset($_POST['address']) && wrong("address") != "wrong") echo $_POST['address']; ?>"
                       id="address" name="address"/>
            </div>

            <div>
                <label class="<?php echo wrong("phone") ?>" for="phone">T&eacute;l&eacute;phone&nbsp;:</label>
                <input class="<?php echo wrong("phone") ?>" type="text"
                       value="<?php if (isset($_POST['phone']) && wrong("phone") != "wrong") echo $_POST['phone']; ?>"
                       id="phone" name="phone"/>
            </div>

            <div>
                <label class="<?php echo wrong("cp") ?>" for="cp">Code postal&nbsp;:</label>
                <input class="<?php echo wrong("cp") ?>" type="text"
                       value="<?php if (isset($_POST['cp']) && wrong("cp") != "wrong") echo $_POST['cp']; ?>" id="cp"
                       name="cp"/>
            </div>

            <div>
                <label class="<?php echo wrong("city") ?>" for="city">Ville&nbsp;:</label>
                <input class="<?php echo wrong("city") ?>" type="text"
                       value="<?php if (isset($_POST['city']) && wrong("city") != "wrong") echo $_POST['city']; ?>"
                       id="city" name="city"/>
            </div>
            <div>
                <input type="submit" value="S'inscrire"/>
            </div>

        </form>
    </div>
    <?php
}

function wrong($field)
{
    global $nameBool, $fNameBool, $loginBool, $passwordBool, $emailBool, $addressBool, $cpBool, $cityBool, $phoneBool;

    if ($field == "name" && !$nameBool) return "wrong";
    if ($field == "firstname" && !$fNameBool) return "wrong";
    if ($field == "login" && !$loginBool) return "wrong";
    if ($field == "password" && !$passwordBool) return "wrong";
    if ($field == "email" && !$emailBool) return "wrong";
    if ($field == "address" && !$addressBool) return "wrong";
    if ($field == "cp" && !$cpBool) return "wrong";
    if ($field == "city" && !$cityBool) return "wrong";
    if ($field == "phone" && !$phoneBool) return "wrong";

}

require_once(TEMPLATES_PATH . '/footer.php');
