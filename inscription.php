<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 03/04/2017
 * Time: 13:12
 */
$pagetitle = 'Inscription';
require_once("resources/config.php");
require_once("resources/functions.php");
session_start();

$name = $fName = $login = $password = $email = $address = $cp = $city = $phone = "";
$nameErr = $fNameErr = $loginErr = $passwordErr = $emailErr = $addressErr = $cpErr = $cityErr = $phoneErr = false;

function spanError($b)
{
    if ($b)
        echo "<span class='glyphicon glyphicon-remove form-control-feedback'></span>";
}

function hasError($b)
{
    if ($b)
        echo "has-error";
}

include_once(TEMPLATES_PATH . '/header.php');

if (isset($_SESSION['userobject'])) {
    ?>
    <div class="alert alert-info">Vous &ecirc;tes d&eacute;j&agrave; connect&eacute; !<br/> Vous ne pouvez pas vous
        inscrire
        de nouveau !
    </div>
    <?php
} else {
    //On échappe les éventuelles balises html que l'utilisateur aurait pu entrer
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['name']) && !empty($_POST['name']))
            $name = test_input($_POST['name']);
        else
            $nameErr = true;
        if (isset($_POST['firstname']) && !empty($_POST['firstname']))
            $fName = test_input($_POST['firstname']);
        else
            $fNameErr = true;
        if (isset($_POST['login']) && !empty($_POST['login']))
            $login = test_input($_POST['login']);
        else
            $loginErr = true;
        if (isset($_POST['password']) && !empty($_POST['password']))
            $password = test_input($_POST['password']);
        else
            $passwordErr = true;
        if (isset($_POST['email']) && !empty($_POST['email']))
            $email = test_input($_POST['email']);
        else
            $emailErr = true;
        if (isset($_POST['address']) && !empty($_POST['address']))
            $address = test_input($_POST['address']);
        else
            $addressErr = true;
        if (isset($_POST['cp']) && !empty($_POST['cp']))
            $cp = test_input($_POST['cp']);
        else
            $cpErr = true;
        if (isset($_POST['city']) && !empty($_POST['city']))
            $city = test_input($_POST['city']);
        else
            $cityErr = true;
        if (isset($_POST['phone']) && !empty($_POST['phone']))
            $phone = test_input($_POST['phone']);
        else
            $phoneErr = true;

        if (!$nameErr && !$fNameErr && !$loginErr && !$passwordErr && !$addressErr && !$cpErr && !$cityErr && !$phoneErr && !$emailErr) {
            $user = User::createUser($name, $fName, $address, $cp, $city, $phone, false, false, $config['default_user'], $email);
            try {
                $user->insertIntoDb($_POST['login'], $_POST['password']);
            } catch (Exception $e) {
                //ON essaye d'entrer un login/email déjà existant
                if ($e->getCode() == 23000) {
                    $error = explode("'", $e->getMessage());
                    if ($error[3] == "login") {
                        echo "<div class='alert alert-danger'>Ce login existe d&eacute;j&agrave;</div>";
                    } else if ($error[3] == "email") {
                        echo "<div class='alert alert-danger'>Cet email existe d&eacute;j&agrave;</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>$e->getMessage();</div>";
                }
            }
            if (!is_null($user)) {
                //$_SESSION['userobject'] = $user;
                echo "<div class='alert alert-success'>Vous avez &eacute;t&eacute; enregistr&eacute;.<a href='connexion.php' class='alert-link'>Connectez-vous !</a></div>";
            } else {
                echo "<div class='alert alert-dander'>Une erreur est survenue. Veuillez r&eacute;essayer plus tard.</div>";
            }
        } else {
            include(TEMPLATES_PATH . '/frmInscription.php');

        }
    } else {

        include(TEMPLATES_PATH . '/frmInscription.php');

    }
}


include_once(TEMPLATES_PATH . '/footer.php');
