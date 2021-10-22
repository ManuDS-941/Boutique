<?php
require_once("include/init.inc.php");

// Si l'internaute n'est pas (!) connecter, il n'a rien à faire sur la page profil, on le redirige vers la page connexion !
if(!connect())
{
    header('location: connexion.php');
}

// extract($_SESSION['user']);
// echo $email . '<hr>';

// echo '<pre>'; print_r($_SESSION); echo '</pre>';

require_once("include/header.inc.php");
require_once("include/nav.inc.php");
?>


<!-- EXERCICE : Afficher la phrase 'bonjour pseudo' en passant par le fichier de session -->

<h1 class="display-4 text-center my-4">Bonjour <span class="text-info"><?=$_SESSION['user']['pseudo']; ?></span></h1>

<div class="card col-md-4 mx-auto mb-4 shadow-lg">
    <div class="card-body">
    <h5 class="card-title">Vos informations de profil</h5>

    <!-- On passe en revue le tableau ARRAY stocké à l'indice 'user' dans la session accessible par la superglobal $_SESSION -->
    <?php foreach($_SESSION['user'] as $key => $value): // les 2 points ':' remplace l'accolade ouvrante?>

        <!-- On exclu à l'affichage l'id_membre, le pseudo et le statut -->
        <?php if($key != 'id_membre' && $key != 'pseudo' && $key != 'statut'): ?>

            <?php if($key == 'civilite' && $value == 'm'): ?>

            <p class="card-text"><strong><?= ucfirst($key) ?></strong> : Homme</p>

            <?php elseif($key == 'civilite' && $value == 'f'): ?>

            <p class="card-text"><strong><?= ucfirst($key) ?></strong> : Femme</p>

            <?php else: ?>

            <p class="card-text"><strong><?= ucfirst($key) ?></strong> : <?= $value ?></p>
            <!-- ucfirst() : Permet de mettre à chaque indice la premiere lettre en majuscule

        <?php endif; ?>

        <?php endif; ?>

        <?php endforeach; // remplace l'accolade fermante ?>

        <a href="#" class="card-link">Modifier</a>
    </div>
</div>

<?php
require_once("include/footer.inc.php");