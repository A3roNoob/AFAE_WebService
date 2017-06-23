<?php
require_once("resources/config.php");
require_once("resources/functions.php");
session_start();
$pagetitle = 'Modifier utilisateur';

include_once(TEMPLATES_PATH . "/header.php");

if (isset($_GET['user']) && !empty($_GET['user'])) {
    $user = User::loadUserWithId(test_input($_GET['user']));

    if (!isset($_GET['infos'])) {
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
                if ($user->updateUser($name, $fName, $address, $cp, $city, $phone, $email, $baisse)) {
                    $user->setRank(test_input($_POST['rank']));
                    $user->updateRank();
                    echo "<div class='alert alert-success'>L'utilisateur a &eacute;t&eacute; modifi&eacute;.</div>";
                    ?>
                    <div>
                        <p><b>Pr&eacute;nom:&nbsp;</b> <?php echo $user->fname(); ?></p>
                        <p><b>Nom:&nbsp;</b> <?php echo $user->name(); ?></p>
                        <p><b>Adresse:&nbsp;</b> <?php echo $user->address(); ?></p>
                        <p><b>Code postal:&nbsp;</b> <?php echo $user->codePostal(); ?></p>
                        <p><b>Ville:&nbsp;</b><?php echo $user->city(); ?></p>
                        <p><b>T&eacute;l&eacute;phone:&nbsp;</b> <?php echo $user->phone(); ?></p>
                        <p><b>Rang:&nbsp;</b> <?php echo $user->rank()->name(); ?></p>
                        <p><b>Baisse:&nbsp;</b> <?php echo ($user->drop()) ? "oui" : "non"; ?></p>
                        <p><b>E-mail:&nbsp;</b><?php echo $user->email(); ?></p>
                    </div>
                    <?php
                } else {
                    echo "<div class='alert alert-danger'>L'utilisateur n'a pas pu &ecirc;tre mis &agrave; jour;</div>";
                }
            } else {
                include(TEMPLATES_PATH . '/frmModifUser.php');
            }
        } else {
            include(TEMPLATES_PATH . '/frmModifUser.php');
        }

    }else {


        ?>



        <?php
    }
}
include_once(TEMPLATES_PATH . "/footer.php");
