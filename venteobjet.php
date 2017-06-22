<?php
require_once("resources/config.php");
require_once("resources/functions.php");
session_start();

$pagetitle = 'Vente';
include_once(TEMPLATES_PATH . '/header.php');

if (isset($_SESSION['userobject']) && $_SESSION['userobject']->checkRank(Rank::loadFromName('Operateur'))) {
    ?>
    <style>

        h1,
        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border: 2px solid black;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
        }

        #total,
        #lieu {
            float: right;
        }
    </style>
    <style media="print">
        #navbar {
            visibility: hidden;
        }

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
        table{
            width: 100%;
        }
    </style>
    <?php
    if (empty($_POST))
        echo "<div class='alert alert-info'>Vous n'avez s&eacute;lectionn&eacute; aucun objet &agrave; vendre !</div>";
    else {

        if (isset($_POST['nomclient']) && !empty($_POST['nomclient']))
            $nomClient = test_input($_POST['nomclient']);
        else
            $nomClient = null;
        unset($_POST['nomclient']);

        if (isset($_POST['paiement']) && !empty($_POST['paiement']))
            $paiement = test_input($_POST['paiement']);

        unset($_POST['paiement']);

        if (isset($_POST['banque']) && !empty($_POST['banque']) && $_POST['banque'] != 0)
            $banque = test_input($_POST['banque']);
        else
            $banque = null;
        unset($_POST['banque']);

        if (isset($_POST['total']) && !empty($_POST['total']))
            $total = test_input($_POST['total']);

        unset($_POST['total']);


        //    public static function createTransaction($idFoire, $idUser, $nomClient, $montant, $idPaiement, $idBanque, $dateTranscation)

        $transac = Transaction::createTransaction($_SESSION['foire'], $_SESSION['userobject']->id(), $nomClient, $total, $paiement, $banque, today());
        $transac->insertIntoDb();

        ?>


        <div id="facture">
            <h2>Association des Familles d'Arpajon et ses Environs</h2>
            <h1>Facture</h1>
            <div id="lieu">Arpajon, le <?php
                $today = DateTime::createFromFormat("d-m-Y", today());
                $today = $today->format("d/m/Y");
                echo $today; ?>
            </div>

            <table>
                <tr>
                    <th>Description</th>
                    <th>Nb objets</th>
                    <th>Prix</th>
                </tr>
                <tr><?php
                    foreach ($_POST as $objet) {
                        $objet = Object::loadObjectFromId($objet);
                        echo '<tr>';
                        echo '<td>';
                        echo $objet->desc();
                        echo '</td>'
                        ;
                        echo '<td>';
                        echo $objet->nbItems();
                        echo '</td>';

                        echo '<td>';
                        echo $objet->prix();
                        echo '</td>';
                        echo '</tr>';
                        $objet->vendre();
                    }
                    ?>
                </tr>
            </table>
            <h2 id="total">Total <?php echo $total;?> €</div>

        </div>
        <?php

    }
}

include_once(TEMPLATES_PATH . '/footer.php');