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

        .objet {
            text-align: center;
            position: relative;
            border: 0.7px solid;
            width: 45%;
            height: 15%;
            page-break-inside: avoid;
            page-break-after: auto;
            page-break-before: auto;
            margin-bottom: 5%;
            float: left;
        }

        .objet.two {
            float: left;
            margin-left: 5%;
        }

        .carnet,
        .ordre {
            border-bottom: 0.5px solid;
            border-right: 0.5px solid;
        }

        .carnet {
            float: left;
            width: 20%;
        }

        .carnet .text {
            border-bottom: 0.5px solid;
        }

        .ordre {
            top: 0%;
            float: left;
            width: 15%;
        }

        .ordre .text {
            border-bottom: 0.5px solid;
        }

        .desc_title {
            width: 47.1%;
            float: left;
        }

        .desc_title .title {
            text-decoration: underline;
        }

        .desc {
            transform: translateY(20%);
        }

        .piece {
            border-bottom: 0.5px solid;
            border-left: 0.5px solid;
            float: left;
            width: 17%;
        }

        .piece .text {
            border-bottom: 0.5px solid;
        }

        .bottom {
            border-top: 0.5px solid;
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 35%;
        }

        .foire,
        .taille {
            border-right: 0.5px solid;
        }

        .foire,
        .taille,
        .prix {
            position: absolute;
            bottom: 0;
            height: 100%;
        }

        .foire {
            width: 50%;
        }

        .foire .text {
            height: 72%;
        }

        .foire .num {
            height: 19%;
        }

        .taille {
            width: 15%;
            margin-left: 50%;
        }

        .taille .text {
            border-bottom: 0.5px solid;
        }

        .taille .content {
            transform: translateY(50%);
        }

        .prix {
            width: 34%;
            right: 0%;
        }

        .prix .text {
            border-bottom: 0.5px solid;
            width: 100%;
            height: 29%;
        }

        .prix .num {
            width: 100%;
            height: 70%;
            font-size: 1.5em;
            transform: translateY(25%);
        }

        .prix .symb {
            position: absolute;
            width: 15%;
            right: 0;
            height: 60%;
            bottom: 0;
            border-top: 0.5px solid;
            border-left: 0.5px solid;
        }

        .prix .symb .rp {
            width: 100%;
            border-bottom: 0.5px solid;
        }
    </style>
</head>
<body>
<?php

function monthToFrench($m)
{
    switch ($m) {
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

function printUser($userArr)
{
    $cpt = 0;
    foreach ($userArr as $user) {
        $objMan = new ObjectManager();
        $objMan->loadObjectsFromUserFoire($user, $_SESSION['foire']->idFoire());
        foreach ($objMan->objets() as $objet) {
            for ($i = 0; $i < $objet->nbItems(); $i++) {
                ?>
                <div class="objet <?php if ($cpt % 2 == 1) echo "two"; ?>">
                    <div class="carnet">
                        <!-- id vendeur -->
                        <div class="text">N°carnet</div>
                        <div class="num"><?php echo $user->id(); ?></div>
                    </div>
                    <div class="ordre">
                        <!-- num objet (dupliquer si en plusieurs pieces) -->
                        <div class="text">N°ordre</div>
                        <div class="num"><?php echo $objet->numItem(); ?></div>
                    </div>
                    <div class="desc_title">
                        <span class="title"> Description</span>

                    </div>
                    <div class="piece">
                        <!-- num de la piece -->
                        <div class="text">Pi&egrave;ce</div>
                        <div class="num"><?php echo ($i + 1) . "/" . $objet->nbItems(); ?></div>
                    </div>
                    <div class="desc"><?php echo $objet->desc(); ?></div>

                    <div class="bottom">
                        <div class="foire">
                            <!-- nom foire, mois annee foire, num objet -->
                            <div class="text"><?php echo $_SESSION['foire']->nomFoire() . " - " . monthToFrench(date("m", strtotime($_SESSION['foire']->dateDebutFoire()))) . " " . date("Y", strtotime($_SESSION['foire']->dateDebutFoire())); ?></div>
                            <div class="num"><?php echo $objet->idObjet(); ?></div>
                        </div>
                        <div class="taille">
                            <!-- taille de l'objet -->
                            <div class="text">Taille</div>
                            <div class="content"><?php echo $objet->taille(); ?></div>
                        </div>
                        <div class="prix">
                            <!-- prix de l'objet -->
                            <div class="text">Prix</div>
                            <div class="num"><?php if ($i == 0) echo $objet->prix() . " €"; ?></div>
                            <div class="symb">
                                <div class="rp">RP</div>
                                <div>N</div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $cpt++;
            }
        }
    }
}

if (isset($_SESSION['foire'])) {
    if (empty($_POST)) {
        $userMan = new UserManager();
        $userMan->loadUsersFromFoire($_SESSION['foire']->idFoire());
        printUser($userMan->users());
    } else {
        $userArr = array();
        foreach ($_POST as $user) {
            array_push($userArr, User::loadUserWithId(test_input($user)));
        }
        printUser($userArr);
    }
}
?>
</body>
</html>