<?php
require_once("resources/config.php");

session_start();
$pagetitle = 'Infos persos';

include_once(TEMPLATES_PATH . "/header.php");

$user = new User();

if (is_a($_SESSION['userobject'], "User")) {
    $user = $_SESSION['userobject'];
    if (isset($_GET['infos'])) {
        $name = $fName = $email = $address = $cp = $city = $phone = "";
        $nameErr = $fNameErr = $emailErr = $addressErr = $cpErr = $cityErr = $phoneErr = false;
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

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['name']) && !empty($_POST['name']))
                $name = test_input($_POST['name']);
            else
                $nameErr = true;
            if (isset($_POST['firstname']) && !empty($_POST['firstname']))
                $fName = test_input($_POST['firstname']);
            else
                $fNameErr = true;
            if (isset($_POST['email']) && !empty($_POST['email']))
                $email = test_input($_POST['email']);
            else
                $emailErr = true;
            if (isset($_POST['address']) && !empty($_POST['address']))
                $address = test_input($_POST['address']);
            else
                $addressErr = true;
            if (isset($_POST['phone']) && !empty($_POST['phone']))
                $phone = test_input($_POST['phone']);
            else
                $phoneErr = true;
            if (isset($_POST['cp']) && !empty($_POST['cp']))
                $cp = test_input($_POST['cp']);
            else
                $cpErr = true;
            if (isset($_POST['city']) && !empty($_POST['city']))
                $city = test_input($_POST['city']);
            else
                $cityErr = true;

            $baisse = isset($_POST['baisse']);

            if (!$nameErr && !$fNameErr && !$addressErr && !$cpErr && !$cityErr && !$phoneErr && !$emailErr) {
                if ($_SESSION['userobject']->updateUser($name, $fName, $address, $cp, $city, $phone, $email, $baisse)) {
                    echo "<div class='alert alert-success'>Vos infos personnelles ont &eacute;t&eacute; modifi&eacute;es.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Vos infos personnelles n'ont pas pu &ecirc;tre modifi&eacute;es.</div>";
                }
            } else {
                include(TEMPLATES_PATH . '/frmModifPerso.php');
            }
        } else {
            include(TEMPLATES_PATH . '/frmModifPerso.php');
        }

    } else if (isset($_GET['mdp'])) {
        if (isset($_POST['mdp']) && isset($_POST['mdp2'])) {
            $mdp = test_input($_POST['mdp']);
            $mdp2 = test_input($_POST['mdp2']);
            if ($mdp == $mdp2) {
                if ($user->updatePassword($mdp)) {
                    echo "<div class='alert alert-success'>Votre mot de passe a &eacute;t&eacute; modifi&eacute;. <a href='/connexion/' class='alert-link'>Reconnectez-vous !</a></div>";
                    session_destroy();
                } else {
                    echo "<div class='alert alert-danger'>Votre mot de passe n'a pas pu &ecirc;tre modifi&eacute;.</div>";
                }

            } else {
                echo "<div class='alert alert-warning'>Les 2 mots de passes ne correspondent pas.</div><br />";
            }
        } else {
            include(TEMPLATES_PATH . '/frmMdp.php');
        }
    } else {


        ?>
        <div>
            <p><b>Nom d'utilisateur:&nbsp;</b> <?php echo $user->login(); ?></p>
            <p><b>Pr&eacute;nom:&nbsp;</b> <?php echo $user->fname(); ?></p>
            <p><b>Nom:&nbsp;</b> <?php echo $user->name(); ?></p>
            <p><b>Adresse:&nbsp;</b> <?php echo $user->address(); ?></p>
            <p><b>Code postal:&nbsp;</b> <?php echo $user->codePostal(); ?></p>
            <p><b>Ville:&nbsp;</b><?php echo $user->city(); ?></p>
            <p><b>T&eacute;l&eacute;phone:&nbsp;</b> <?php echo $user->phone(); ?></p>
            <p><b>Baisse:&nbsp;</b> <?php echo ($user->drop()) ? "oui" : "non"; ?></p>
            <p><b>E-mail:&nbsp;</b><?php echo $user->email(); ?></p>
            <button onclick="window.location.href= '/perso/infos/'">Modifier infos</button>
            <button onclick="window.location.href= '/perso/mdp/'">Modifier mot de passe</button>
        </div>


        <?php
    }
}
include_once(TEMPLATES_PATH . "/footer.php");
