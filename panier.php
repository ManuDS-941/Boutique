<?php 
require_once('include/init.inc.php');

if(isset($_POST['ajout_panier']))
{
    echo '<pre>'; print_r($_POST); echo '</pre>';
    $data = $bdd->query("SELECT * FROM produit WHERE id_produit =  $_POST[id_produit]");
    $product = $data->fetch(PDO::FETCH_ASSOC);

    // echo '<pre>'; print_r($product); echo '</pre>';

    ajoutPanier($product['id_produit'], $product['photo'], $product['reference'], $product['titre'], $_POST['quantite'], $product['prix']);
}

// CONTROLE DES STOCKS 
// On entre dans le IF seulement dans la cas où l'internaute a cliqué sur le bouton 'FINALISER LA COMMANDE' et que l'attribut name 'payer' du bouton est détecté 
if(isset($_POST['payer']))
{   //       2  2  <  4
    for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
    {                                                                               // 2
        $data = $bdd->query("SELECT * FROM produit WHERE id_produit = " . $_SESSION['panier']['id_produit'][$i]);
        $product = $data->fetch(PDO::FETCH_ASSOC);
        // echo '<pre>'; print_r($product); echo '</pre>';

        // Si la quantite de stock en BDD est inférieur à la quantité commandé par l'internaute dans la panier, alors on entre dans le IF
        $error = '';
        if($product['stock'] < $_SESSION['panier']['quantite'][$i])
        {
            // Affichage du stock restant en BDD 
            $error .= '<p class="col-md-6 mx-auto bg-primary rounded text-center text-white p-2 my-4">Stock restant en BDD du produit <strong>' . $_SESSION['panier']['titre'][$i] . '</strong> : <strong>' . $product['stock'] . '</strong></p>';

            // Affichage de la quantité commandé
            $error .= '<p class="col-md-6 mx-auto bg-success rounded text-center text-white p-2 my-4">Quantité commandé du produit <strong>' . $_SESSION['panier']['titre'][$i] . '</strong> : <strong>' . $_SESSION['panier']['quantite'][$i] . '</strong></p>';

            // On entre dans le IF si la quantité en BDD est supérieur à 0 mais inférieur à la quantité commandé dans le panier
            if($product['stock'] > 0)
            {
                $error .= '<p class="col-md-6 mx-auto bg-info rounded text-center text-white p-2 my-4">La quantité du produit <strong>' . $_SESSION['panier']['titre'][$i] . '</strong> a été réduite car notre stock est insuffisant, vérifiez vos achats !!</p>';

                // On affecte la quantité restante en stock au produit directement dans la session
                $_SESSION['panier']['quantite'][$i] = $product['stock'];
            }
            else // Sinon le stock en BDD est à 0, alors on supprime le produit dans le panier, donc dans la session
            {
                $error .= '<p class="col-md-6 mx-auto bg-success rounded text-center text-white p-2 my-4">Le produit <strong>' . $_SESSION['panier']['titre'][$i] . '</strong> a été supprimé car nous sommes en rutpure de stock, vérifiez vos achats !!</p>';

                // On supprime le produit dans la session qui est en rupture de stock dans la BDD
                suppProduitPanier($_SESSION['panier']['id_produit'][$i]);
                $i--; // on faire un tour de boucle à l'envers, on décrément, parce que la fonction array_splice() supprime le produit mais remont tout les inférieur aux indices supérieurs, cela nous permet de ne pas oublier de contrôler un produit qui est remonté d'un indice dans le tableau ARRAY dans la session
            }
        }
    }

    // Si la variable $error est vide,cela veut dire que nous sommes entré dans aucune des condition c-dessus, donc que les stocks en BDD sont suffisant, on entre dans le IF et on insère en BDD les informations de la commande
    if(empty($error))
    {
        $insertCMD = $bdd->exec("INSERT INTO commande (id_membre, montant, date_enregistrement) VALUES (" . $_SESSION['user']['id_membre'] . "," . montantTotal() . ", NOW())");

        // On récupère le dernier id généré en BDD, donc l'id de la commande insérer en BDD pour l'insérer dans la table details_commande, pour que chaque produit soit bien relié à la bonne commande
        $id_commande = $bdd->lastInsertId(); 

        for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
        {
            $insertDCMD = $bdd->exec("INSERT INTO details_commande (id_commande, id_produit, quantite, prix) VALUES ($id_commande, " . $_SESSION['panier']['id_produit'][$i] . "," . $_SESSION['panier']['quantite'][$i] . "," . $_SESSION['panier']['prix'][$i] . ")");

            // On déprécie les stocks en BDD, on soustrait la quantité commandé à la quantité en stock en BDD
            $updateQTE = $bdd->exec("UPDATE produit SET stock = stock - " . $_SESSION['panier']['quantite'][$i] . " WHERE id_produit = " . $_SESSION['panier']['id_produit'][$i]);
        }
        // On supprime l'indice 'panier' dans la session après validation de la coommande
        unset($_SESSION['panier']);

        $validCMD = "<p class='col-md-6 mx-auto bg-success rounded text-center text-white p-2 my-4'>La commande a bien été enregistrée ! Votre numéro de commande est le <strong>CMD$id_commande</strong></p>";
    }
}

// echo '<pre>'; print_r($_SESSION); echo '</pre>';

require_once('include/header.inc.php');
require_once('include/nav.inc.php');
?>

<h1 class="display-4 text-center my-4">Votre panier</h1>

<?php 
if(isset($error)) echo $error; 
if(isset($validCMD)) echo $validCMD; 
?>

<table class="col-md-8 mx-auto table table-bordered text-center">
    <tr>
        <th>Photo</th>
        <th>Référence</th>
        <th>Titre</th>
        <th>Quantité</th>
        <th>Prix Unitaire</th>
        <th>Prix Total / Produit</th>
        <th>Supprimer</th>
    </tr>

    <?php if(empty($_SESSION['panier']['id_produit'])): ?>

        <tr>
            <td colspan="7"><p class="font-italic text-danger">Votre panier est vide !!</p></td>
        </tr>

    <?php else: ?>

        <?php for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++): ?>

            <tr>

                <td><img src="<?= $_SESSION['panier']['photo'][$i] ?>" alt="<?= $_SESSION['panier']['titre'][$i] ?>" class="img-product"></td>

                <td><?= $_SESSION['panier']['reference'][$i] ?></td>
                <td><?= $_SESSION['panier']['titre'][$i] ?></td>
                <td><?= $_SESSION['panier']['quantite'][$i] ?></td>
                <td><?= $_SESSION['panier']['prix'][$i] ?>€</td>

                <td><?= $_SESSION['panier']['prix'][$i]*$_SESSION['panier']['quantite'][$i] ?>€</td>

                <td><a href="" class="text-dark"><i class='fas fa-trash-alt'></i></a></td>

            </tr>

        <?php endfor; ?>

            <tr>
                <th colspan="5">MONTANT TOTAL</th>
                <th><?= montantTotal() ?>€</th>
            </tr>

<!-- endif -->
<!-- </table> -->

        <?php if(connect()): ?>

            <tr>
                <td colspan="6">
                    <form method="post" action="" class="col-md-8 mx-auto p-0 mb-1">
                        <input type="submit" name="payer" class="btn btn-success" value="FINALISER LA COMMANDE">
                    </form>
                </td>
            </tr>

        <?php else: ?>

            <tr>
                <td colspan="6">
                    <div class="col-md-8 mx-auto p-0 mb-1">
                    <a href="connexion.php" class="btn btn-success">JE ME CONNECTE POUR PASSER MA COMMANDE</a>
                    </div>
                </td>
            </tr>

        <?php endif; ?>
        
    <?php endif; ?>
</table>
<?php 
require_once('include/footer.inc.php');