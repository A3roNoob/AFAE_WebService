<?php
require_once(dirname(__FILE__)."/../config.php");
require_once(dirname(__FILE__)."/../functions.php");
session_start();
?>
<html>
<head>
    <title>Bordereau de ch&egrave;ques</title>
    <style>
        @page {
            size: auto;
            margin: 3mm 15mm 4mm 15mm;
        }

        body {
            height: 297mm;
            width: 210mm;
        }

        div.bordereau {
            width: 100%;
        }

        div.head {
            width: 45%;
            float: left;
        }

        div.header {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            text-align: left;
        }

        th {
            border-top: 5px solid black;
            border-bottom: 5px solid black;
        }

        th.big {
            width: 40%;
        }

        th.small {
            width: 20%;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
            page-break-before: auto;
        }

        td {
            border: 1px solid black;
        }

        div.footer {
            margin-top: 5%;
            width: 100%;
        }

        div.subfooter {
            width: 45%;
            float: left;
        }

        div.subfooter h5 {
            display: inline;
        }

        div.subfooter h5.data {
            margin-left: 20%;
        }

        div.signature {
            display: block;
            margin-top: 10%;
            margin-left: 40%;
        }

        div.signature h6 {
            margin: 0;
            margin-bottom: 1%;
        }
    </style>
</head>

<body>
<?php


if (isset($_SESSION['foire']) && is_a($_SESSION['foire'], "Foire")) {
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['date']) && !empty($_POST['date'])) {
        ?>
        <div class="borderau">
            <div id="header">
                <div class="head">
                    <h3>Bordereau de ch&egrave;ques</h3>
                    <h4 class="date">Ch&egrave;ques du <?php
                        $today = DateTime::createFromFormat("d-m-Y", today());
                        $today = $today->format("d/m/Y");
                        echo $today; ?></h4>
                </div>
                <div class="head">
                    <h3>Compte AFAE: Soci&eacute;t&eacute; G&eacute;n&eacute;rale</h3>
                    <h3>30003 00842 000 50 089146 41</h3>
                </div>
            </div>
            <table>
                <tr class="head">
                    <th class="big">Banque</th>
                    <th class="big">Client</th>
                    <th class="small">Montant</th>
                </tr>
                <?php
                $nbCheque = $totalPrix = 0;
                foreach (Transaction::getChequesDateFoire(1, test_input($_POST['date'])) as $cheque) {
                    ?>
                    <tr>
                        <td><?php echo Banque::loadFromBd($cheque->getIdBanque())->codeBanque(); ?></td>
                        <td><?php echo $cheque->getNomClient();?></td>
                        <td><?php echo $cheque->getMontant()." €"; ?></td>
                    </tr>
                    <?php
                    $nbCheque++;
                    $totalPrix += $cheque->getMontant();
                }
                ?>
            </table>
            <div class="footer">
                <div class="subfooter">
                    <h5>Nombre de ch&egrave;ques</h5>
                    <h5 class="data"><?php echo $nbCheque; ?></h5>
                </div>
                <div class="subfooter">
                    <h5>Total des ch&egrave;ques:</h5>
                    <h5 class="data"><?php echo $totalPrix." €" ;?></h5>
                </div>
            </Div>

            <div class="signature">
                <h6>Date et signature</h6>
                <h6><em>Jeudi 22 juin 2017</em></h6>
            </div>
        </div>

        <?php

    } else {
        ?>
        <form action="/impression/bordereau/" method="post">
            <label for="date">Date&nbsp;:&nbsp;</label>
            <select name="date">
                <?php
                foreach (Transaction::getDatesFromFoire(1) as $date) {
                    $dateAff = DateTime::createFromFormat("Y-m-d", $date);
                    $dateAff = $dateAff->format("d/m/Y");
                    echo "<option value=" . $date . ">" . $dateAff . "</option>";
                }
                ?>

            </select>
            <input type="submit" value="Envoyer"/>
        </form>

        <?php


    }
}
?>



<?php

?>
</body>
</html>
