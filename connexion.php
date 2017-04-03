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
require(CLASS_PATH . "/User.php");
session_start();

require_once(TEMPLATES_PATH . '/header.php');

if (isset($_SESSION['userobject'])) {
    $user = $_SESSION['userobject'];

    ?>
    <div class="messagebox">
        <h1>Vous &ecirc;tes connect&eacute;, <?php echo $user->name() . " " . $user->fname(); ?></h1>
    </div>

    <?php
} else {

    if (isset($_POST['login']) && isset($_POST['password']) && !isset($_SESSION['userobject'])) {
        $redirect = true;
        try {
            $user = User::loadFromBd($_POST['login'], $_POST['password']);
            if (!(is_null($user->id())) && $user->name() != "wrong")
                $_SESSION['userobject'] = $user;
        } catch (Exception $e) {
            echo "<div class='messagebox'><h1>" . $e->getMessage() . "</h1></div>";
            $redirect = false;
        }

        if ($redirect) {
            ?>
            <script>
                setTimeout(function () {
                    window.location.replace("index.php");
                }, 200);
            </script>
            <?php
        } else {
            include(TEMPLATES_PATH . '/frmLogin.html');
        }
    } else {
        include(TEMPLATES_PATH . '/frmLogin.html');
    }
}
require_once(TEMPLATES_PATH . '/footer.php');
?>