<nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <a class="navbar-brand" href="#">Ma boutique de OUF !!</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarsExample04">

    <ul class="navbar-nav mr-auto">

    <?php if(connect()): // on définit les liens accessibles pour un membre connecté ?>

      <li class="nav-item active">
        <a class="nav-link" href="<?= URL ?>profil.php">Votre compte</a>
      </li>


      <li class="nav-item active">
        <a class="nav-link" href="<?= URL ?>boutique.php">Accès à la boutique</a>
      </li>
      
      <li class="nav-item active">
        <a class="nav-link" href="<?= URL ?>panier.php">Mon panier</a>
      </li>

      <li class="nav-item active">
        <a class="nav-link" href="<?= URL ?>connexion.php?action=deconnexion">Deconnexion</a>
      </li>

    <?php else: // on définit les liens accessibles pour un visiteur lambda non connecté?>

      <li class="nav-item active">
        <a class="nav-link" href="<?= URL ?>inscription.php">Créer votre compte</a>
      </li>

      <li class="nav-item active">
        <a class="nav-link" href="<?= URL ?>connexion.php">Identifiez-vous</a>
      </li>

      <li class="nav-item active">
        <a class="nav-link" href="<?= URL ?>boutique.php">Accès à la boutique</a>
      </li>

      <li class="nav-item active">
        <a class="nav-link" href="<?= URL ?>panier.php">Mon panier</a>
      </li>

    <?php endif; ?>

    <?php if(adminConnect()): // On entre dans le IF si l'internaute est connecté et est administrateur du site, c'est à dire que son statut à pour valeur '1' dans la session donc dans la BDD ?>

      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">BACK OFFICE</a>
        <div class="dropdown-menu" aria-labelledby="dropdown04">
          <a class="dropdown-item" href="<?= URL ?>admin/gestion_boutique.php">Gestion des produits</a>
          <a class="dropdown-item" href="<?= URL ?>admin/gestion_commande.php">Gestion des commandes</a>
          <a class="dropdown-item" href="<?= URL ?>admin/gestion_membre.php">Gestion des membres</a>
        </div>
      </li>

    <?php endif; ?>

    </ul>

    <form class="form-inline my-2 my-md-0">
      <input class="form-control" type="text" placeholder="Search">
    </form>
  </div>
</nav>

<main class="container mon-conteneur">