<?php
require_once(dirname(__FILE__)."/../config.php");
require_once(dirname(__FILE__)."/../functions.php");
session_start();
?>
<html>
<head>
    <title>Objets restants</title>
    <style>
        @page {
            size: auto;
            margin: 3mm 15mm 4mm 15mm;
        }

        body {
            height: 297mm;
            width: 210mm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            text-align: right;
        }

        #header {
            width: 210mm;
            border-top: 7px solid black;
            border-bottom: 7px solid black;
        }

        .objetsrestant {
            width: 100%;
        }

        .objetsrestant h1 {
            text-align: center;
        }

        .date {
            left: 0px;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
        }

        tr.head {
            text-decoration: underline;
            border-bottom: 5px solid black;
        }

        th,
        tr {
            text-align: center;
            font-size: 1.25em;

        }
        td {
            page-break-inside: avoid;
            page-break-after: auto;
            page-break-before: auto;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
            page-break-before: auto;
        }

        tr.foot {
            border-top: 5px solid black;
        }
    </style>
</head>
<body>
<?php
if (isset($_SESSION['foire']) && is_a($_SESSION['foire'], "Foire")) {
    ?>
    <div class="objetsrestant">
        <div id="header">
            <h1>NB d'Objets restant par vendeur</h1>
            <h4 class="date"><?php
                $today = DateTime::createFromFormat("d-m-Y", today());
                $today = $today->format("d/m/Y");
                echo $today; ?></h4>
        </div>
        <table>
            <tr class="head">
                <th>N°vendeur</th>
                <th>Nom Vendeur</th>
                <th>Nb objets d&eacute;pos&eacute;s</th>
                <th>Nb objets vendus</th>
                <th>Nb objets restants</th>
                <th>Total</th>
            </tr>
            <?php
            $vendeurs = new UserManager();
            $vendeurs->loadUsersFromFoire($_SESSION['foire']->idFoire());
            $nbVendeurs = $totalDepose = $totalVendus = $totalRestants = $totalPrix= 0;
            foreach ($vendeurs->users() as $vend) {
                $objets = new ObjectManager();
                $objets->loadObjectsFromUserFoire($vend, $_SESSION['foire']->idFoire());
                $nbObjets = $nbVendus = $totalObjets = 0;
                foreach($objets->objets() as $objet){
                    $nbObjets++;
                    if($objet->vendu())
                        $nbVendus++;
                    $totalObjets += $objet->prix();
                }
                $nbRestants = $nbObjets - $nbVendus;
                ?>
                <tr>
                    <td><?php echo $vend->id();?></td>
                    <td><?php echo $vend->name(); ?></td>
                    <td><?php echo $nbObjets;?></td>
                    <td><?php echo $nbVendus;?></td>
                    <td><?php echo $nbRestants;?></td>
                    <td><?php echo $totalObjets;?></td>
                </tr>
                <?php
                $totalDepose += $nbObjets;
                $totalVendus += $nbVendus;
                $totalRestants += $nbRestants;
                $totalPrix += $totalObjets;
                $nbVendeurs++;
            }


            ?>
            <tr class="foot">
                <th><?php echo $nbVendeurs; ?></th>
                <!-- nbtotalvendeur -->
                <th></th>
                <th><?php echo $totalDepose; ?></th>
                <!-- nb objet deposes total -->
                <th><?php echo $totalVendus; ?></th>
                <!-- nb objets vendus total -->
                <th><?php echo $totalRestants; ?></th>
                <!-- nb objets restants total -->
                <th><?php echo $totalPrix; ?></th>
                <!-- total argent des objets vendus -->
            </tr>
        </table>
    </div>
    <?php
}

?>
</body>


</html>
