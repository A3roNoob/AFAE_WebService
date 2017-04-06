<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 02/04/2017
 * Time: 23:57
 */
$pagetitle = 'Connexion';
require_once("resources/config.php");
require_once("resources/functions.php");
session_start();
include_once(TEMPLATES_PATH . '/header.php');

$login = $password = "";
$loginErr = $passwordErr = "";
if (isset($_SESSION['userobject'])) {
    $user = $_SESSION['userobject'];

    ?>
    <div class="messagebox">
        <h1>Vous &ecirc;tes connect&eacute;, <?php echo $user->name() . " " . $user->fname(); ?></h1>
    </div>

    <?php
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_SESSION['userobject'])) {

        if (empty($_POST['login'])) {
            $loginErr = "Un identifiant doit &ecirc;tre entr&eacute;";
        } else {
            $login = test_input($_POST['login']);
        }
        if (empty($_POST['password'])) {
            $passwordErr = "Un mot de passe doit &ecirc;tre entr&eacute;";
        } else {
            $password = test_input($_POST['password']);
        }
        try {
            $user = User::loadFromBd($login, $password);
            if (!(is_null($user->id())))
                $_SESSION['userobject'] = $user;
        } catch (Exception $e) {
            echo "<div class='messagebox'><h1>" . $e->getMessage() . "</h1></div>";
        }
        if (!isset($_SESSION['userobject']))
            include(TEMPLATES_PATH . '/frmLogin.php');
        else ?>
            <div class="messagebox">
            <h1>Vous &ecirc;tes connect&eacute;, <?php echo $user->name() . " " . $user->fname(); ?></h1>
            <script>
                setTimeout(function () {
                    window.location.replace("index.php");
                }, 200);
            </script>
        </div>
        <?php
    } else {
        include(TEMPLATES_PATH . '/frmLogin.php');
    }
}
include_once(TEMPLATES_PATH . '/footer.php');
?>