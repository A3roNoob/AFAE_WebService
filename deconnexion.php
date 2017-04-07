<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 03/04/2017
 * Time: 00:45
 */
require_once("resources/config.php");
require_once(CLASS_PATH . "/Rank.php");

session_start();
$pagetitle = 'Deconnexion';



unset($_POST['userobject']);
session_destroy();

include_once(TEMPLATES_PATH . '/header.php');
if(isset($_SESSION['userobject']) && $_SESSION['userobject']->checkRank(Rank::loadFromId(1))) {


    ?>
    <div class="messagebox">
        <h1>Vous &ecirc;tes d&eacute;connect&eacute; !</h1>
    </div>

    <script>
        setTimeout(function () {
            window.location.replace("index.php");
        }, 1000);
    </script>

    <?php
}
else
{
    accessForbidden();
}
include_once(TEMPLATES_PATH . '/footer.php');
?>