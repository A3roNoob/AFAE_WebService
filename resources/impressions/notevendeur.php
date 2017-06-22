<?php
require_once("..\\config.php");
require_once("..\\functions.php");
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

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            text-align: right;
        }

        td.prix {
            width: 25%;
        }

        td.x {
            width: 5%;
        }

        .note {
            width: 30%;
            border: 2px solid black;
            float: left;
            overflow: hidden;
            margin-bottom: 5%;
            page-break-inside: avoid;
            page-break-after: auto;
            page-break-before: auto;
            margin-left: 2%;
        }

        .note h2 {
            text-align: center;
        }

        .note .total,
        .note .retenue,
        .note .totalrendu {
            width: 60%;
            float: left;
            margin-left: 2%;
        }

        .note .totalData,
        .note .retenueData,
        .note .totalrenduData {
            width: 35%;
            text-align: right;
            float: left;
        }
    </style>
</head>
<body>
<?php
function countItemByValue($iduser)
{
    $objMan = new ObjectManager();
    $objArr = $objMan->getAllItemSoldByUserFoire($iduser, $_SESSION['foire']->idFoire());
    $sortedArr = array();

    foreach ($objArr as $objet) {
        $sortedArr["prix_" . $objet->prix()] = 0;
        foreach ($objArr as $objet2) {
            if ($objet->prix() == $objet2->prix())
                $sortedArr["prix_" . $objet->prix()] = $sortedArr["prix_" . $objet->prix()] + 1;
        }
    }

    return $sortedArr;
}

function getPriceFromKey(array $array)
{
    $keys = array_keys($array);
    $prices = array();
    foreach ($keys as $price) {
        $priceArr = explode("_", $price);
        array_push($prices, $priceArr[1]);
    }
    return $prices;
}

function printUser($userArr)
{
    $cpt = 0;
    foreach ($userArr as $user) {
        $pricesByNb = countItemByValue($user->id());
        $prices = getPriceFromKey($pricesByNb);
        ?>
        <div class="note">
            <h2><?php echo $user->id() . " " . $user->name(); ?></h2>
            <table>
                <?php
                $total = 0;
                $cpt = 0;
                foreach ($pricesByNb as $nbItem) {
                    ?>
                    <tr>
                        <td class="prix"> <?php echo $prices[$cpt] . " €"; ?></td>
                        <td class="x"> x</td>
                        <td class="nbobjetsvendus"> <?php echo $nbItem; ?></td>
                        <td class="equal">=</td>
                        <td class="totalObj"><?php
                            $total += $prices[$cpt] * $nbItem;
                            echo $prices[$cpt] * $nbItem . " €"; ?></Td>
                    </tr>
                    <?php
                    $cpt++;
                } ?>
            </table>
            <div class="total">Total&nbsp;:&nbsp;</div>
            <div class="totalData"><?php echo $total . " €"; ?></div>
            <div class="retenue">Retenue (<?php echo $_SESSION['foire']->retenue(); ?>%):</div>
            <div class="retenueData"><?php echo $total * ($_SESSION['foire']->retenue() / 100) . " €"; ?></div>
            <div class="totalrendu">Total &agrave; rendre:</div>
            <div class="totalrenduData"> <?php echo $total * (1 - ($_SESSION['foire']->retenue() / 100)) . " €"; ?> </div>
        </div>


        <?php

        $cpt++;
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