<?php
require_once("resources/config.php");
require_once("resources/functions.php");
session_start();

$pagetitle = 'Tableau de bord';
include_once(TEMPLATES_PATH . '/header.php');
if (isset($_SESSION['foireobj']) && is_a($_SESSION['foireobj'], "Foire")) {

    $nbVendeur = $nbObjetsDeposes = $nbObjetsVendus = $pourcent = $nbTransaction = $montantTotal = $nbCheques = $totalCheque = $nbLiquide = $totalLiquide = $totalRendre = 0;
    $nbVendeur = $_SESSION['foireobj']->getNbVendeurs();
    $nbObjetsDeposes = $_SESSION['foireobj']->getNbObjets();
    $nbObjetsVendus = $_SESSION['foireobj']->getNbObjetsVendu();
    if($nbObjetsDeposes > 0)
        $pourcent = ($nbObjetsVendus / $nbObjetsDeposes) * 100;
    else
        $pourcent = 0;
    $nbTransaction = Transaction::getNbTransactionFromFoires($_SESSION['foireobj']->idFoire());
    $nbCheques = Transaction::getNbPaiementsFoire($_SESSION['foireobj']->idFoire(), 2);
    $totalCheque = Transaction::getTotalPaiment($_SESSION['foireobj']->idFoire(), 2);
    $nbLiquide = Transaction::getNbPaiementsFoire($_SESSION['foireobj']->idFoire(), 1);
    $totalLiquide = Transaction::getTotalPaiment($_SESSION['foireobj']->idFoire(), 1);
    $montantTotal = $totalLiquide + $totalCheque;
    $totalRendre = $montantTotal * (1 -($_SESSION['foireobj']->retenue()/100));

    ?>
    <style>
        .table {
            visibility: hidden;
        }

        .tableaudebord {
            visibility: visible;
        }

        div.tableaudebord {
            width: 100%;
        }

        div.container {
            width: 45%;
            float: left;
            margin-left: 5%;
        }

        .container table {
            border: 0.5px solid black;
        }

        div.container table td {
            width: 30%;
        }

        div.container table td.small {
            width: 10%;
            color: red;
        }
    </style>
    <style media="print">
        @page {
            size: auto;
            margin: 3mm 15mm 4mm 15mm;
        }

        .tableaudebord, #navbar {
            visibility: hidden;
        }

        .table {
            visibility: visible;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
            page-break-before: auto;
        }

        td {
            border: 1px solid black;
            width: 50%;
            text-align: center;
        }
    </style>
    <div class="tableaudebord">
        <div class="container">
            <table>
                <tr>
                    <td>Transactions</td>
                    <td>Total</td>
                    <td>par Ch&egrave;ques</td>
                    <td>en Liquide</td>
                </tr>
                <tr>
                    <td>Nombre</td>
                    <td><?php echo $nbTransaction; ?></td>
                    <td><?php echo $nbCheques; ?></td>
                    <td><?php echo $nbLiquide; ?></td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td style="color: red;"><?php echo $montantTotal." €";?></td>
                    <td><?php echo $totalCheque." €";?></td>
                    <td><?php echo $totalLiquide." €";?></td>
                </tr>
            </table>
        </div>
        <div class="container">
            <table>
                <tr>
                    <td>Nombre Vendeurs</td>
                    <td><?php echo $nbVendeur; ?></td>
                    <td class="small"></td>
                </tr>
                <tr>
                    <td>Nombre Objets D&eacute;pos&eacute;s</td>
                    <td><?php echo $nbObjetsDeposes; ?></td>
                    <td class="small"></td>
                </tr>
                <tr>
                    <td>Nombre Objets Vendus</td>
                    <td><?php echo $nbObjetsVendus; ?></td>
                    <td class="small"><?php echo $pourcent . " %"; ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="table">
        <table>
            <tr>
                <td>Nb vendeurs</td>
                <td><?php echo $nbVendeur; ?></td>

            </tr>
            <tr>
                <td>Nb objets D&eacute;pos&eacute;s</td>
                <td><?php echo $nbObjetsDeposes; ?></td>
            </tr>
            <tr>
                <td>Nb Objets Vendus</td>
                <td><?php echo $nbObjetsVendus; ?></td>
            </tr>
            <tr>
                <td>Pourcent</td>
                <td><?php echo $pourcent . " %"; ?></td>
            </tr>
            <tr>
                <td>Nb Transaction</td>
                <td><?php echo $nbTransaction; ?></td>
            </tr>
            <tr>
                <td>Montant total</td>
                <td><?php echo $montantTotal; ?></td>
            </tr>
            <tr>
                <td>Nb paiement ch&egrave;</td>
                <td><?php echo $nbCheques; ?></td>
            </tr>
            <tr>
                <td>Total Cheques</td>
                <td><?php echo $totalCheque." €"; ?></td>
            </tr>
            <tr>
                <td>Nb paiment en Liquide</td>
                <td><?php echo $nbLiquide;?></td>
            </tr>
            <tr>
                <td>Total liquide</td>
                <td><?php echo $totalLiquide." €"; ?></td>
            </tr>
            <tr>
                <td>Montant &agrave; rendre</td>
                <td><?php echo $totalRendre." €"; ?></td>
        </table>
    </div>
    <?php
}
include_once(TEMPLATES_PATH . '/footer.php');