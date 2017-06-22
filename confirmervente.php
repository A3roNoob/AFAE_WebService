<?php
require_once("resources/config.php");
require_once("resources/functions.php");
session_start();

$pagetitle = 'Confirmer vente';
include_once(TEMPLATES_PATH . '/header.php');

if (isset($_SESSION['userobject']) && $_SESSION['userobject']->checkRank(Rank::loadFromName('Operateur'))) {

    if (empty($_POST))
        echo "<div class='alert alert-info'>Vous n'avez s&eacute;lectionn&eacute; aucun objet &agrave; vendre !</div>";
    else {
        ?>
        <div class="table-responsive">
            <form method="post" action="/vente/objet/">

                <table class="table table-striped">
                    <tr>
                        <th>Num Objet</th>
                        <th>Vendeur</th>
                        <th>Description</th>
                        <th>Taille</th>
                        <th>Nb Items</th>
                        <th>Prix</th>
                    </tr>

                    <?php
                    $total = 0;
                    foreach ($_POST as $objet) {
                        $objet = Object::loadObjectFromId($objet);
                        $total += $objet->prix();
                        echo "<input type='hidden' name='objet_" . $objet->idObjet() . "'value='" . $objet->idObjet() . "' />";
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
                        echo "</tr>";
                    }

                    ?>
                </table>
                <div class="form-group">
                    <label for="name">Nom client&nbsp;:&nbsp;</label>
                    <input type="text" name="nomclient" class="form-control" value=""/>
                </div>
                <div class="form-group" style="width: 50%;">
                    <label for="paiement">Type: </label>
                    <select style="width: 30%;" name="paiement">
                        <option value="1">Liquide</option>
                        <option value="2">Ch&egrave;que</option>
                    </select>
                </div>
                <div class="form-group" style="width: 50%;">
                    <label for="banque">Banque: </label>
                    <select style="width: 30%;" name="banque">
                        <option value="0">Liquide</option>
                        <?php
                        $banqueList = Banque::getAllBanque();
                        foreach($banqueList as $banque){
                            echo "<option value='".$banque->idBanque()."'>".$banque->nomBanque()."</option>";
                        }

                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="total">Total: <?php echo $total; ?> €</label>
                    <input type="hidden" name="total" class="form-control" value="<?php echo $total ?>"/>
                </div>
                <input type="submit" value="Confirmer"/>
                <input type="button" value="Annuler" onclick="history.back()"/>
            </form>
        </div>
        <?php
    }
} else {
    accessForbidden();
}

include_once(TEMPLATES_PATH . '/footer.php');