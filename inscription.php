<?php
require_once("include/init.inc.php");


// Exercice 2 :

// echo '<pre>'; print_r($_POST); echo '</pre>';


// Si l'internaute est connecté, il n'a rien à faire sur la page connexion.php, on le redirige vers sa page profil !
if(connect())
{
  header('location: profil.php');
}


if($_POST)
{
    // EXERCICE 3 :
    // On selectionne le pseudo en BDD A CONDITION que la colonne 'pseudo' de la table 'membre' SOIT EGAL au pseudo saisie dans le formulaire par l'internaute
    $verifPseudo = $bdd->prepare("SELECT * FROM membre WHERE pseudo = :pseudo"); // retourne un objet PDOStatement // :pseudo = c'est une boite vide permettant d'eviter les injection SQL en les enfermants
    $verifPseudo->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
    $verifPseudo->execute();


    // echo rowCount() : retourne le nombre de lignes selectionnées en BDD via la requete SQL

    // On compte le nombre de ligne retournépar la requette de selection, si rowCount() retourne au moin un résultat, cela veut dire que le pseudo est bien connu en BDD, alors on entre dans  le IF et on affiche un message d'erreur
    if($verifPseudo->rowCount() > 0)
    {
        $errorPseudo = "<span class='text-danger font-italic'>Ce pseudo est déjà existant. Merci d'en saisir un nouveau</span>";

        $error = true;
    }

    // EXERCICE 4
    // Si la requete de selection retourne des données de la BDD, cela veut dire que l'adresse est connu en BDD, alors on affiche un message d'erreur
    $verifMail = $bdd->prepare("SELECT * FROM membre WHERE email = :email");
    $verifMail->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $verifMail->execute();

    // Si rowCount() retourne 1 resultat, cela veut dire que la requete de selection a retourné des données par rapport à l'email saisi dans le formulaire
    if($verifMail->rowCount() > 0)
        {
            $errorMail = "<span class='text-danger font-italic'>Ce mail est déjà existant. Merci d'en saisir un nouveau</span>";

            $error = true;
        }


    // EXERCICE 5
        // Si la valeur du champ 'mdp' est differente du champs 'confirm_mdp' alors on entre dans le IF
        if($_POST['mdp'] != $_POST['confirm_mdp'])
        {
            $errorMdp = "<span class='text-danger font-italic'>Ce mail est déjà existant. Merci d'en saisir un nouveau</span>";

            $error = true;
        }

    // EXERCICE 6
    // Si la variable $error n'est pas définit, cela veut dire que l'internaute a correctement rempt le formulaire
    // $error est définit seulement dans le cas ou l'internaute a fait une erreur dans le formulaire et est donc entré dans au moins une des conditions IF déclarées ci-dessus
    if(!isset($error))
    {
        
        //extract($_POST);

        // Les mots de passes ne sont jamais conserver en clair dans la BDD
        // password_hash() est une fonction prédéfinie permettant de créer une clé de hachage (Attention obligatoire legalement)
        // arguments :
        // 1. La chaine de caractères a haché
        // 2. Le type de hachage
        $_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_BCRYPT); // Le raccourcis avec extract ne marche pas sur la variable définit 

        
        // requete SQL d'insertion (prepare)
        $validInsert = $bdd->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse)");
        $validInsert->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
        $validInsert->bindValue(':mdp', $_POST['mdp'], PDO::PARAM_STR);
        $validInsert->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
        $validInsert->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
        $validInsert->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $validInsert->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
        $validInsert->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
        $validInsert->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_STR);
        $validInsert->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR); // Grace a extract($_post); on peut ecrire juste $adress au lieu de $_post['adresse']
        $validInsert->execute();

        // Après insertion de l'utilisateur, on redirige vers la page connexion.php
        header('location: connexion.php?inscription=valid');


    }

}

require_once("include/header.inc.php");
require_once("include/nav.inc.php");
?>

<!--
1. Réaliser un formulaire HTML correspondant à la table 'membre' (sauf id_membre | confirmer mot de passe)
2. Controler en PHP que l'on receptionne bien toute les données saisie dans le formulaire
3. Controler la validité du pseudo (SELECT + ROWCOUNT)
4. Controler la validité de l'email (SELECT + ROWCOUNT)
5. Faites en sorte d'informer l'internaute si les mdp ne correspondent pas
6. Si l'internaute a correctement rempli le formulaire, executer la requete d'insertion en BDD (requete préparé : PREPARE + BINDVALUE)
-->

<h1 class="display-4 text-center my-3">Créer votre compte</h1>

<form method="post" class="col-md-8 mx-auto">

    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="inputState">Civilité :</label>
            <select id="civilite" class="form-control" name="civilite">
                <option value="m">Monsieur</option>
                <option value="f">Madame</option>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="pseudo">Pseudo :</label>
            <input type="text" class="form-control" id="pseudo" name="pseudo">
            <?php if(isset($errorPseudo)) echo $errorPseudo; ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="mdp">Mot de Passe :</label>
            <input type="text" class="form-control" id="mdp" name="mdp">
            <?php if(isset($errorMdp)) echo $errorMdp; ?>
        </div>
    
        <div class="form-group col-md-6">
            <label for="confirm_mdp">Confirmer votre mot de passe</label>
            <input type="text" class="form-control" id="confirm_mdp" name="confirm_mdp">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="prenom">Prenom :</label>
            <input type="text" class="form-control" id="prenom" name="prenom">
        </div>
        <div class="form-group col-md-6">
            <label for="nom">Nom :</label>
            <input type="text" class="form-control" id="nom" name="nom">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="email">E-mail : </label>
            <input type="text" class="form-control" id="email" name="email">
            <?php if(isset($errorMail)) echo $errorMail; ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="adresse">Adresse :</label>
            <input type="text" class="form-control" id="adresse" name="adresse">
        </div>
    </div>


    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="ville">Ville :</label>
            <input type="text" class="form-control" id="ville" name="ville">
        </div>
        <div class="form-group col-md-3">
            <label for="code_postal">Code Postal :</label>
            <input type="text" class="form-control" id="code_postal" name="code_postal">
        </div>
    </div>






    <button type="submit" class="btn btn-dark">Inscription</button>

</form>










<?php
require_once("include/footer.inc.php");
?>