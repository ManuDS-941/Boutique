
<?php
require_once("../include/init.inc.php");

// Si l'internaute n'est pas ni connecté ni administrateur, il n'a rien a faire sur cette page, on le redirige vers la page connexion.php
if(!adminConnect())
{
    header('location: ' . URL . 'connexion.php');
}

// ----------------------- SUPPRESSION DU PRODUIT


// Si l'indice 'action' est bien définit dans l'URL et qu'il a pour valeur 'suppression', cela veut dire que l'internaute à cliqué sur le bouton 'SUPPRESSION' et par conséquent que les paramètres 'action=suppression' ont été envoyé dans l'URL
if(isset($_GET['action']) && $_GET['action'] == 'suppression')
{
    // requete SQL DELETE
    // echo "suppression produit | C'est Supprimé !!";

    // EXO : réaliser le script PHP + SQL permettant de supprimer un produit dans la BDD
    
    $delete = $bdd->prepare("DELETE FROM produit WHERE id_produit = :id_produit");
    $delete->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
    $delete->execute();

    // On redéfinit la valeur de l'indice 'action' dans l'URL afin d'être redirigé vers l'affichage des produits après la suppression
    $_GET['action'] = 'affichage';

    $validDelete = "<p class='col-md-4 mx-auto bg-success rounded text-white text-center p-3'>Le produit <strong>ID $_GET[id_produit]</strong> a été supprimé avec succès !! </p>";

}

// echo '<pre>'; print_r($_POST); echo '</pre>';
// Les informations d'un fichier uploader sont directement receptionnés et stockés dans la superglobales $_FILES
// Ne pas oublier dans le formulaire l'attribut enctype="multipart/form-data"
// echo '<pre>'; print_r($_FILES); echo '</pre>';

// ----------------------- ENREGISTREMENT PRODUIT

if($_POST)
{
    $photoBdd = '';

    // Un champ de type 'file' n'accepte pas l'attribut 'value'
    // Du coup en cas de modification, le champ photo reste vide et le champ photo dans la BDD est également vide
    //SI on souhaite conservé l'image du produit, on recupére l'URL de l'image via le champ type 'hidden' dans le formulaireafin de la renvoyer dans la BDD
    if(isset($_GET['action']) && $_GET['action'] == 'modification')
    {
        $photoBdd = $_POST['photo_actuelle'];
    }

    if(!empty($_FILES['photo']['name']))
    {
        // On renomme la photo, on concatene la référence saisi dans le formulaire avec le nom de l'image récupérer dans la superglobale $_FILES
        $nom_photo = $_POST['reference'] . '-' . $_FILES['photo']['name'];

        // echo $nom_photo . '<hr>';

        // On définit l'URL de l'image qui sera stocké en BDD
        $photoBdd = URL . "assets/$nom_photo";
        // echo $photoBdd . '<hr>';

        // On définit le chemin physique de la photo sur le serveur, nous en aurons besoin afin de copier et enregistrer l'image dans le bon dossier sur le serveur
        $photoDossier = RACINE_SITE . "assets/$nom_photo";

        // echo $photoDossier . '<hr>';

        // copy() : fonction prédéfinie permettant ici de copier l'image télécharger via le formulaire directement dans le dossier 'assets' sur le serveur
        //Arguments :
        // 1. Le nom temporaire de l'image (accessible dans $_FILES (tmp))
        // 2. Le chemin physique complet de la photo sur le serveur
        copy($_FILES['photo']['tmp_name'], $photoDossier);

    }
    

    // Si l'indice 'action' dans l'URL a pour valeur 'ajout', cela veut dire que l'internaute a cliqué sur le bouton 'AJOUT D'UN PRODUIT, donc les paramètres 'action=ajout' ont été transmise dans l'URL, alors on execute une requete INSERT à la validation du formulaire
    if(isset($_GET['action']) && $_GET['action'] == 'ajout')
    {
        // EXERCICE : Réaliser le script PHP + SQL permettant d'insérer un produit dans la table 'produit" avec une requete préparée (prepare + INSERT)
        $produitInsert = $bdd->prepare("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, public, photo, prix, stock) VALUES (:reference, :categorie, :titre, :description, :couleur, :taille, :public, :photo, :prix, :stock)");

        $_GET['action'] = 'affichage';

        // On redéfinit la valeur de l'indice 'action' dans l'URL afin d'être redirigé vers l'affichage des produits après l'insertion des produits
        $validInsert = "<p class='col-md-4 mx-auto bg-success rounded text-white text-center p-3'>Le produit référence <strong>$_POST[reference] $_POST[titre]</strong> a été enregistré avec succès !! </p>";

    }
    else // Sinon, dans tout les autres cas, ce n'est pas une insertion, c'est une modification à la validation du formulaire
    {
        // Requete SQL de modification (UPDATE)
        $produitInsert = $bdd->prepare("UPDATE produit SET reference = :reference, categorie = :categorie, titre = :titre, description = :description, couleur = :couleur, taille = :taille, public = :public, photo = :photo, prix = :prix, stock = :stock WHERE id_produit = :id_produit");

        $produitInsert->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);

        $_GET['action'] = 'affichage';

        // On redéfinit la valeur de l'indice 'action' dans l'URL afin d'être redirigé vers l'affichage des produits après l'insertion des produits
        $validUpdate = "<p class='col-md-4 mx-auto bg-success rounded text-white text-center p-3'>Le produit référence <strong>$_POST[reference] $_POST[titre]</strong> a été modifié avec succès !! </p>";

    }

        $produitInsert->bindValue(':reference', $_POST['reference'], PDO::PARAM_STR);
        $produitInsert->bindValue(':categorie', $_POST['categorie'], PDO::PARAM_STR);
        $produitInsert->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
        $produitInsert->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
        $produitInsert->bindValue(':couleur', $_POST['couleur'], PDO::PARAM_STR);
        $produitInsert->bindValue(':taille', $_POST['taille'], PDO::PARAM_STR);
        $produitInsert->bindValue(':public', $_POST['public'], PDO::PARAM_STR);
        $produitInsert->bindValue(':photo', $photoBdd, PDO::PARAM_STR);
        $produitInsert->bindValue(':prix', $_POST['prix'], PDO::PARAM_INT);
        $produitInsert->bindValue(':stock', $_POST['stock'], PDO::PARAM_INT);
        $produitInsert->execute(); // execution de la requete insert into..

        
    
}

require_once("../include/header.inc.php");
require_once("../include/nav.inc.php");

// EXERCICE :
// 1. Réaliser le traitement PHP + SQL permettant d'afficher l'ensemble de la table 'produit' (entête colonne + contenu) sur la page Web (SELECT + FETCH) sous forme de tableau HTML
// 2. Prévoir un lien modification et suppression pour chaque produit
// 3. Afficher le nombre de produit stockés dans la table produit

?>

<div class="col-md-6 mx-auto text-center">
<h2 class="display-4 text-center">BACK OFFICE</h2>
<a href="?action=affichage" class="btn btn-dark p-3">AFFICHAGE DES PRODUITS</a>
<a href="?action=ajout" class="btn btn-dark p-3">AJOUT D'UN PRODUITS</a>
</div>

<?php 


// Si l'indice 'action' est bien définit dans l'URL et qu'il a pour valeur 'affichage', cela veut dire que l'internaute à cliqué sur le bouton 'AFFICHAGE DES PRODUITS' et par conséquent que les paramètres 'action=affichage' ont été envoyé dans l'URL
if(isset($_GET['action']) && $_GET['action'] == 'affichage')
{


    // EXERCICE 1 :

    // ------------------------------ AFFICHAGE DES PRODUITS
    $data = $bdd->query("SELECT * FROM produit");

    echo "<h1 class='display-4 text-center my-3'>Affichage des produits</h1>";

    //Affichage du message de validation de suppression
    if(isset($validDelete)) echo $validDelete;

    //Affichage du message de validation de insertion
    if(isset($validInsert)) echo $validInsert;
    
    
    //Affichage du message de validation de modification
    if(isset($validUpdate)) echo $validUpdate;

    echo "<p class='text-center'>Nombre de produit(s) dans la boutique : <span class='badge badge-success'>" . $data->rowCount() . "</span></p>";

    // echo '<pre>'; print_r($data); echo '</pre>';

    $products = $data->fetchAll(PDO::FETCH_ASSOC);

    // echo '<pre>'; print_r($products); echo '</pre>';

    

    echo '<table class="table table-bordered text-center"><tr>';
    //                     id_produit
    foreach($products[0] as $key => $value)
    {
        echo "<th>$key</th>";
    }
        echo "<th>Editer</th>";
        echo "<th>Supprimer</th>";

    echo '</tr>';
    //                     0    ARRAY
    foreach($products as $key => $tab)
    {
        echo '<tr>';

        foreach($tab as $key2 => $value)
        {
            
            // Si l'infice $key2 a pour valeur 'photo', alors on envoi l'URL de l'image dans une balise <img>
            if($key2 == 'photo')
            {
                echo "<td><img src='$value' alt='$tab[titre]' class='img-product'></td>";
            }
            else // Sinon, le reste des valeurs sont affichées dans des cellules <td></td>
            {
                echo "<td class='align-middle'>$value</td>"; 
            }
        }
        echo "<td class='align-middle'><a href='?action=modification&id_produit=$tab[id_produit]' class='text-primary'><i class='far fa-edit'></i></a></td>"; // CDN fontawesome
        echo "<td class='align-middle'><a href='?action=suppression&id_produit=$tab[id_produit]' class='text-danger'><i class='fas fa-trash-alt'></i></a></td>";
        echo '</tr>';
    }

    echo '</table>';

}

?>


<?php 

// Si l'indice 'ajout' est bien définit dans l'URL et qu'il a pour valeur 'ajout', cela veut dire que l'internaute à cliqué sur le bouton 'AJOUTS DES PRODUITS' et par conséquent que les paramètres 'action=ajout' ont été envoyé dans l'URL

// --------------------------- AJOUT D'UN PRODUIT 

if(isset($_GET['action']) &&  ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification')): 

    
    if(isset($_GET['id_produit']))
    {
        $update = $bdd->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
        $update->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
        $update->execute();

        
        // echo '<pre>'; print_r($update); echo '</pre>';

        $produitActuel = $update->fetch(PDO::FETCH_ASSOC);
        
        // echo '<pre>'; print_r($produitActuel); echo '</pre>';
    

        // ? : si on n'a reference de produitActuel dans $reference
        // : alors on aura la reference de produitActuel

        // LA boucle foreach passe en revu les données du produit a modifié selectionné en BDD
        // La boucle FOREACH crée une variable par tour de boucle contenant une donnée du produit dans chaque variable qu'on pourra assigné a chaque input
        //                   id_produit =>  1
        foreach($produitActuel as $key => $value)
        {
            // 1er tour :
            // $id_produit = (isset($produitActuel["id_produit"]))

            // 2e tour :
            // $reference = (isset($produitActuel["reference"])) etc..
            $$key = (isset($produitActuel["$key"])) ? $produitActuel["$key"] : '';
            
        }
    }

?>

<form method="post" class="col-md-8 mx-auto" enctype="multipart/form-data">

<h1 class="display-4 text-center my-3"><?= ucfirst($_GET['action']); ?> d'un produit</h1>

    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="reference">Référence :</label>
            <input type="text" class="form-control" id="reference" name="reference" value="<?php if(isset($reference)) echo $reference; ?>">
            
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="categorie">Catégorie :</label>
            <input type="text" class="form-control" id="categorie" name="categorie" value="<?php if(isset($categorie)) echo $categorie; ?>">
            
        </div>
    
        <div class="form-group col-md-6">
            <label for="titre">Titre</label>
            <input type="text" class="form-control" id="titre" name="titre" value="<?php if(isset($titre)) echo $titre; ?>">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="couleur">Couleur : </label>
            <input type="text" class="form-control" id="couleur" name="couleur" value="<?php if(isset($couleur)) echo $couleur; ?>">
            
        </div>
        <div class="form-group col-md-6">
            <label for="nom">Nom :</label>
            <input type="text" class="form-control" id="nom" name="nom" value="<?php if(isset($nom)) echo $nom; ?>">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="description">Description :</label>
            <textarea type="text" class="form-control" id="description" name="description" value="<?php if(isset($description)) echo $description; ?>"></textarea>
            
            
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="taille">Taille :</label>
            <select id="taille" class="form-control" name="taille">
                <option value="s" <?php if(isset($taille) && $taille == 's') echo 'selected'; ?>>S</option>
                <option value="m" <?php if(isset($taille) && $taille == 'm') echo 'selected'; ?>>M</option>
                <option value="l" <?php if(isset($taille) && $taille == 'l') echo 'selected'; ?>>L</option>
                <option value="xl" <?php if(isset($taille) && $taille == 'xl') echo 'selected'; ?>>XL</option>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="public">Public :</label>
            <select id="public" class="form-control" name="public">
                <option value="m" <?php if(isset($taille) && $taille == 'm') echo 'selected'; ?>>Monsieur</option>
                <option value="f" <?php if(isset($taille) && $taille == 'f') echo 'selected'; ?>>Madame</option>
                <option value="mixte" <?php if(isset($taille) && $taille == 'mixte') echo 'selected'; ?>>Mixte</option>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="photo">Photo :</label>
            <input type="file" class="form-control-file" id="photo" name="photo"> 
        </div>
    </div>

    <input type="hidden" id="photo_actuelle" name="photo_actuelle" value="<?php if(isset($photo)) echo $photo;?>">

    <!-- Affichage de la photo actuelle du produit en cas de modification -->
    <?php if(isset($photo) && !empty($photo)): ?>

        <div class="col-md-5 mx-auto">
        <em>Vous pouvez uploader une nouvel photo si vous souhaiter la changer</em><br>

        <img src="<?= $photo ?>" alt="<?= $titre ?>">
        </div>

    <?php endif; ?>


    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="prix">Prix :</label>
            <input type="text" class="form-control" id="prix" name="prix"value="<?php if(isset($prix)) echo $prix; ?>">
        </div>
        <div class="form-group col-md-3">
            <label for="stock">Stock :</label>
            <input type="text" class="form-control" id="stock" name="stock" value="<?php if(isset($stock)) echo $stock; ?>">
        </div>
    </div>


    <button type="submit" class="btn btn-dark"><?= ucfirst($_GET['action']); ?></button>

</form>


<?php
endif;
require_once("../include/footer.inc.php");