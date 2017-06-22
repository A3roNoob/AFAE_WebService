<?php
require_once("resources/config.php");
require_once("resources/functions.php");
session_start();

$pagetitle = 'Gestion des foires';
include_once(TEMPLATES_PATH . '/header.php');

if (isset($_SESSION['userobject']) && $_SESSION['userobject']->checkRank(Rank::loadFromName('Operateur'))) {
    if (isset($_GET['foire'])) {
        ?>
        <form method="POST" action="/gestion/foire/<?php echo $_GET['foire']; ?>/" class="form-inline">
            <div class="form-group">
                <label for="objet">Nom de l'objet: </label>
                <input type="text" class="form-control" onKeyUp="searchItem()"
                       value="<?php if (isset($_POST['objet'])) echo $_POST['objet']; ?>" id="objet" name="objet"/>
            </div>
            <input type="submit" class="btn btn-default" value="Chercher"/>
        </form>
        <br/>

        <?php
        $objMan = new ObjectManager();
        $objMan->loadObjectsFromFoire(test_input($_GET['foire']));

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['objet'])) {
           $desc = test_input($_POST['objet']);
        }
        ?>
        <div class="table-responsive">
            <form id="formvendre" action="/vente/confirmer/" method="POST">
                <input type="submit" id="submitobjet" value="Vendre" />
                <input type="button" value="D&eacute;s&eacute;lectionner tout" id="clear"/>
                <div class="clear">&nbsp;</div>
            <table class="table table-striped">
                <tr>
                    <th>Num Objet</th>
                    <th>Vendeur</th>
                    <th>Description</th>
                    <th>Taille</th>
                    <th>Nb Items</th>
                    <th>Prix</th>
                    <th>Baisse autorisée</th>
                    <th>Vendu</th>
                    <th>Prix Vendu</th>
                    <th>Vendre</th>
                </tr>
                <?php

                foreach ($objMan->objets() as $objet) {
                    if(isset($desc) && !empty($desc)){
                        if(!(strpos(strtoupper('#'.$objet->desc()), strtoupper($desc)) || strtoupper($objet->desc()) == strtoupper($desc))) {
                            echo "<tr style='display: none;'>";
                        }
                    }else
                        echo "<tr>";

                    echo "<td>";
                    echo $objet->numItem();
                    echo "</td>";

                    echo "<td>";
                    echo $objet->user()->name() . " " . $objet->user()->fName();
                    echo "</td>";

                    echo "<td class='desc'>";
                    echo $objet->desc();
                    echo "</td>";

                    echo "<td>";
                    echo $objet->taille();
                    echo "</td>";

                    echo "<td>";
                    echo $objet->nbItems();
                    echo "</td>";

                    echo "<td>";
                    echo $objet->prix();
                    echo "</td>";

                    echo "<td>";
                    echo $objet->baisse() ? "oui" : "non";
                    echo "</td>";

                    echo "<td>";
                    echo $objet->vendu() ? "oui" : "non";
                    echo "</td>";

                    echo "<td>";///Prix Vendu
                    echo ($objet->vendu()) ? "Pas implémenté" : "Non Vendu";
                    echo "</td>";

                    echo "<td>";
                    ///TODO à faire vente d'objet
                    echo ($objet->vendu()) ? "Vendu" : "<input type='checkbox' class='objet' name='objet_".$objet->idObjet()."' value='".$objet->idObjet()."'/>";
                    echo "</td>";

                    echo "</tr>";
                }
                $_SESSION['foire'] = test_input($_GET['foire']);
                ?>
            </table>
        </form>
        </div>
        <script>
            document.getElementById("clear").addEventListener("click", function(){
                var check = document.getElementsByClassName("objet");
                for(var i = 0; i < check.length; i++){
                    console.log(i);
                    check[i].checked = false;
                }
            });

            document.getElementById("submitobjet").addEventListener("click", function (e){
                var check, nbCheck = 0;
                check = document.getElementsByClassName("objet");
                for(var i = 0; i < check.length; i++){
                    if( check[i].checked == true){
                        nbCheck++;
                    }
                }
                if (nbCheck == 0){
                    alert("Attention aucun objet coché !");
                    e.preventDefault();
                    return false;
                }
                return true;
            });
        </script>
        <?php

    } else {
        $foireMan = new FoireManager();
        $foireMan->loadFoiresFromDb();

        ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <tr>
                    <th>Nom</th>
                    <th>Administrateur</th>
                    <th>Date de début saisies</th>
                    <th>Date de fin saisies</th>
                    <th>Date de début foire</th>
                    <th>Date de fin foire</th>
                    <th>G&eacute;rer</th>
                </tr>
                <?php
                foreach ($foireMan->foires() as $foire) {
                    echo "<tr>";
                    echo "<td>";
                    echo $foire->nomFoire();
                    echo "</td>";

                    echo "<td>";
                    $user = User::loadUserWithId($foire->idAdmin());
                    echo $user->name() . " " . $user->fname();
                    echo "</td>";

                    echo "<td>";
                    echo $foire->dateDebutSaisie();
                    echo "</td>";

                    echo "<td>";
                    echo $foire->dateFinSaisie();
                    echo "</td>";

                    echo "<td>";
                    echo $foire->dateDebutFoire();
                    echo "</td>";

                    echo "<td>";
                    echo $foire->dateFinFoire();
                    echo "</td>";

                    echo "<td>";
                    echo "<button type='button' onclick=\"window.location.href = '/gestion/foire/" . $foire->idFoire() . "/'\" class='btn btn-primary'>Voir</button>";
                    echo "</td>";
                    echo "</tr>";
                }

                ?>
            </table>
        </div>
        <?php
    } ?>
    <script>
        function searchItem() {
            var input, filter, descs;

            input = document.getElementById('objet');
            filter = input.value.toUpperCase();
            descs = document.getElementsByClassName('desc');

            for (var i = 0; i < descs.length; i++) {
                if (descs[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
                    descs[i].parentElement.style.display = "";
                } else {
                    descs[i].parentElement.style.display = "none";
                }
            }
        }
    </script>
    <?php
} else {
    accessForbidden();
}

include_once(TEMPLATES_PATH . '/footer.php');

?>