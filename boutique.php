<?php 
require_once('include/init.inc.php');

// Si l'indice 'categorie' est bien définit dans l'URL, cela veut dire que l'internaute a cliqué sur le lien d'une catégorie, alors on selectionne en BDD tout les produits liés à cette catégorie
if(isset($_GET['categorie']))
{   //                                                              pull
    $data2 = $bdd->prepare("SELECT * FROM produit WHERE categorie = :categorie");
    $data2->bindValue(':categorie', $_GET['categorie'], PDO::PARAM_STR);
    $data2->execute();
}
else // Sinon, l'internaute n'a pas cliqué sur un lien d'une catégorie donc on seelctionne l'ensemble de la table 'produit'
{
    $data2 = $bdd->query("SELECT * FROM produit");
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

                    <a href="?categorie=<?= $cat['categorie'] ?>" class="list-group-item text-dark text-center"><?= $cat['categorie'] ?></a>

                <?php endwhile; ?>

            </div>

        </div>
        <!-- /.col-lg-3 -->

        <div class="col-lg-9">

            <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner" role="listbox">
                <div class="carousel-item active">
                    <img class="d-block img-fluid" src="<?= URL ?>assets/slider1.jpg" alt="First slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block img-fluid" src="<?= URL ?>assets/slider2.jpg" alt="Second slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block img-fluid" src="<?= URL ?>assets/slider3.jpg" alt="Third slide">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
            </div>

            <div class="row">

                <!-- fecth() retourne un ARRAY par tour de boucle contenant les données d'1 produit -->
                <?php while($product = $data2->fetch(PDO::FETCH_ASSOC)): 
                    // echo '<pre>'; print_r($product); echo '</pre>';
                    ?>    

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100">

                            <a href="fiche_produit.php?id_produit=<?= $product['id_produit'] ?>"><img class="card-img-top" src="<?= $product['photo'] ?>" alt="<?= $product['titre'] ?>"></a>
                            <div class="card-body">

                                <h4 class="card-title">
                                    <a href="fiche_produit.php?id_produit=<?= $product['id_produit'] ?>" class="text-dark"><?= $product['titre'] ?></a>
                                </h4>

                                <h5><?= $product['prix'] ?>€</h5>

                                <p class="card-text"><?= substr($product['description'], 0, 80); ?>...</p><!-- on coupe une partie de la description -->
                            </div>

                            <div class="card-footer text-center">
                                <a href="fiche_produit.php?id_produit=<?= $product['id_produit'] ?>" class="btn btn-dark">En savoir plus &raquo;</a>
                            </div>

                        </div>
                    </div>

                <?php endwhile; ?>

            </div>
            <!-- /.row -->

        </div>
        <!-- /.col-lg-9 -->

    </div>
    <!-- /.row -->

</div>
<!-- /.container -->

<?php 
require_once('include/footer.inc.php');