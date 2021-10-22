<?php 
require_once('include/init.inc.php');

// SI l'indice 'id_produit' est bien définit dans l'URL, cela veut dire l'internaute a cliqué sur un lien 'en savoir plus' et par conséquent a tranmsit dans l'URL les paramètre ex: 'id_produit=3' 
if(isset($_GET['id_produit']))
{
    $data2 = $bdd->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $data2->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
    $data2->execute();

    // SI la requete SELECT retourne un résultat de la BDD, cela veut dire que l'id_produit est bien connu en BDD, on entre dans le IF
    if($data2->rowCount())
    {
        $product = $data2->fetch(PDO::FETCH_ASSOC);
        // echo '<pre>'; print_r($product); echo'</pre>';

        if($product['public'] == 'f')
            $public = 'Femme';
        elseif($product['public'] == 'm')
            $public = 'Homme';
        else 
            $public = 'Mixte';
    }
    else // Sinon l'id_produit dans l'URL n'est pas connu en BDD, on redirige l'internaute vers la page boutique
    {
        header('location: boutique.php');
    }
}
else // Sinon l'indice 'id_produit' n'est pas définit dans l'URL, on redirige vers la boutique afin d'éviter d'avoir des erreurs sur la fiche produit
{
    header('location: boutique.php');
}

require_once('include/header.inc.php');
require_once('include/nav.inc.php');
?>

<!-- Page Content -->
<div class="container">

    <div class="row">

    <div class="col-lg-3">
        
        <?php 
        $data = $bdd->query("SELECT DISTINCT categorie FROM produit");

        // $cat = $data->fetchAll(PDO::FETCH_ASSOC);

        // echo '<pre>'; print_r($cat); echo '</pre>';
        ?>

        <h3 class="my-4 text-center">Que du lourd !! Viendez voir !!</h1>
        <div class="list-group">
        
            <?php while($cat = $data->fetch(PDO::FETCH_ASSOC)): 
                // echo '<pre>'; print_r($cat); echo '</pre>';
                ?>

                <a href="boutique.php?categorie=<?= $cat['categorie'] ?>" class="list-group-item text-dark text-center"><?= $cat['categorie'] ?></a>

            <?php endwhile; ?>

        </div>

    </div>
    <!-- /.col-lg-3 -->

    <div class="col-lg-9">

        <div class="card mt-4">
            <img class="card-img-top img-fluid" src="<?= $product['photo'] ?>" alt="<?= $product['titre'] ?>">
            <div class="card-body">
                <h3 class="card-title"><?= $product['titre'] ?></h3>
                <h4><?= $product['prix'] ?>€</h4>

                <p class="card-text">Catégorie : <a href="boutique.php?categorie=<?= $product['categorie'] ?>"><?= $product['categorie'] ?></a></p>
                <p class="card-text">Référence : <?=$product['reference'] ?></p>
                <p class="card-text">Taille : <?= $product['taille'] ?></p>
                <p class="card-text">Couleur : <?= $product['couleur'] ?></p>
                <p class="card-text"><?= $public; ?></p>
                <p class="card-text"><?= $product['description'] ?></p>

                <!-- Si le stock du produit en BDD est différent de 0, on entre dans le IF -->
                <?php if($product['stock'] != 0): ?>

                    <!-- Si le stock du produit est inférieur à 10 en BDD, alors on informe l'internaute -->
                    <?php if($product['stock'] < 10): ?>

                        <p class="card-text text-success font-italic">Attention ! Il ne reste plus que <?= $product['stock'] ?> exemplaire(s) en stock !</p>

                    <?php endif; ?>

                    <form method="post" action="panier.php" class="form-inline">

                        <input type="hidden" id="id_produit" name="id_produit" value="<?= $product['id_produit'] ?>">

                        <div class="form-group">
                            <label for="quantite">Quantité</label>
                            <select class="form-control ml-2" id="quantite" name="quantite">
                            <!--                 <=  3                &&  4 <= 30        -->
                            <?php for($i = 1; $i <= $product['stock'] && $i <= 30; $i++): ?>

                                <option><?= $i ?></option>
                            
                            <?php endfor; ?>
                            </select>
                        </div>
                        <button type="submit" name="ajout_panier" class="btn btn-dark ml-2">Ajouter au panier</button>
                    </form>    
                
                <?php else: // Sinon le stock du produit est à 0 en BDD, alors on affiche un message ?>

                    <p class="card-text text-danger font-italic">Rupture de stock !</p>

                <?php endif; ?>
            </div>
        </div>
        <!-- /.card -->

        <div class="card card-outline-secondary my-4">
        <div class="card-header">
            Product Reviews
        </div>
        <div class="card-body">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
            <small class="text-muted">Posted by Anonymous on 3/1/17</small>
            <hr>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
            <small class="text-muted">Posted by Anonymous on 3/1/17</small>
            <hr>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
            <small class="text-muted">Posted by Anonymous on 3/1/17</small>
            <hr>
            <a href="#" class="btn btn-success">Leave a Review</a>
        </div>
        </div>
        <!-- /.card -->

    </div>
    <!-- /.col-lg-9 -->

    </div>

</div>
<!-- /.container -->

<?php 
require_once('include/footer.inc.php');