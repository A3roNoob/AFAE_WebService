<?php
require_once(dirname(__FILE__)."/../config.php");
require_once(dirname(__FILE__)."/../functions.php");
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            size: auto;
            margin: 0;
            margin-top: 5mm;
        }

        body {
            height: 297mm;
            width: 210mm;
            margin: 10mm 15mm 10mm 15mm;
        }

        .vendeur {
            width: 45%;
            top: 10mm;
            page-break-inside: avoid;
            page-break-after: auto;
            page-break-before: auto;
            float: left;
            overflow: visible
        }

        h1, h2, h4 {
            text-align: center;
        }

        h1 {
            -webkit-margin-after: 0.4em;
        }
    </style>
</head>
<body>
<?php

function monthToFrench($m){
    switch($m){
        case 1:
            return "janvier";
        case 2:
            return "f&eacute;vrier";
        case 3:
            return "mars";
        case 4:
            return "avril";
        case 5:
            return "mai";
        case 6:
            return "juin";
        case 7:
            return "juillet";
        case 8:
            return "aout";
        case 9:
            return "septembre";
        case 10:
            return "octobre";
        case 11:
            return "novembre";
        case 12:
            return "d&eacute;cembre";
        default:
            return "n'existe pas";
    }
}

function printUser($userArr){
    $cpt = 0;
    foreach ($userArr as $user) {
?>
        <div class="vendeur">
            <h4><?php echo $_SESSION['foire']->nomFoire(). " - " . monthToFrench(date("m", strtotime($_SESSION['foire']->dateDebutFoire()))). " " . date("Y", strtotime($_SESSION['foire']->dateDebutFoire())); ?></h4>
            <h1><?php echo $user->id(); ?></h1>
            <h1><?php echo $user->name(); ?></h1>
            <h2><?php echo $user->fname(); ?></h2>
        </div>


        <?php

        $cpt++;
    }
}

if (isset($_SESSION['foire'])) {
    if(empty($_POST)){
        $userMan = new UserManager();
        $userMan->loadUsersFromFoire($_SESSION['foire']->idFoire());
        printUser($userMan->users());
    }
    else{
        $userArr = array();
        foreach($_POST as $user){
            array_push($userArr, User::loadUserWithId(test_input($user)));
        }
        printUser($userArr);
    }
}
?>
</body>
</html>