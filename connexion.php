<?php
require_once("include/init.inc.php");

// SI l'indice 'action' est bien définit dans l'URL et qu'il a pour valeur 'deconnexion', cela veut dire que l'internaute a cliqué sur le lien 'deconnexion' et par conséquent a transmis dans l'URL 'action=deconnexion'
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion')
{
  session_destroy(); // on detruit le fichier session
  // ou unset($_SESSION['user]); // On supprime une partie de la session, c'est à dire le tableau ARRAY stocké à l'indice 'user'
}


// Si l'indice 'inscription' est bien définit dans l'URL et que cet indice à pour valeur 'valid', alors on entre dans le IF
// On entre dans le IF seulement dans le cas ou l'internaute a validé son inscription
if(isset($_GET['inscription']) && $_GET['inscription'] == 'valid')
{
    $validInscrip = "<p class='col-md-5 mx-auto bg-success text-white text-center p-3 rounded'>Felicitation ! Vous êtes maintenant inscrit. Vous pouvez dès à présent vous connecter.</p>";
}


// Si l'internaute est connecté, il n'a rien à faire sur la page connexion.php, on le redirige vers sa page profil !
if(connect())
{
  header('location: profil.php');
}

if($_POST)
{
    //extract($_POST);

    //                                                                    email =   manuds...
    $data = $bdd->prepare("SELECT * FROM membre WHERE pseudo = :pseudo OR email = :email");
    $data->bindValue(':pseudo', $_POST['email_pseudo'], PDO::PARAM_STR); // bindvalue() : dans cette boite vide tu injecte cette valeur
    $data->bindValue(':email', $_POST['email_pseudo'], PDO::PARAM_STR);
    $data->execute();

     // echo $data->rowCount() . '<hr>';

    $error = '';
    // $valid = '';
    //   1
    if($data->rowCount())
    {
      // On entre dans le IF si l'internaute a saisie le bon 'pseudo' ou 'email'
      // echo "Email ou pseudo OK";

      $user = $data->fetch(PDO::FETCH_ASSOC);
      echo '<pre>'; print_r($user); echo '</pre>';
      // echo '<pre>'; print_r($data); echo '</pre>';
      // echo '<pre>'; print_r($_POST); echo '</pre>';
                                      
      // Controle de MDP en clair
      // $user['mdp'] == $_POST['mdp']
      // toto75 == qsjilnlgnsùlbhqdnùh632hfh23s2j3

      // Controle
      // password_verify() : fonction prédéfinie permettant de comparer seulement une clé de hachage à une chaine de caractère
      // if($_POST['mdp'] == $user['mdp']) : on doit faire ça pour que sa fonctionne du coup

      if(password_verify($_POST['mdp'], $user['mdp']))
      // if($_POST['mdp'] == $user['mdp'])
      {
      // On entre dans cette condition seulement dans le cas où l'internaute a saisi le bon pseudo/email ET le bon mot de passe
      // echo "mot de passe OK !!";

                                          

        //      ARRAY    nom => Greg
        foreach($user as $key => $value)
        {
        // nom != 'mdp'
          if($key != 'mdp')
          {
            //$_SESSION['user']['nom'] = Greg;
            $_SESSION['user'][$key] = $value;
          }
        }
        echo '<pre>'; print_r($_SESSION); echo '</pre>';
                                            
        // Une fois l'internaute connecté, on le redirige vers sa page profil.php
        header('location: profil.php');
        
      }
      else
      {   
      //echo "erreur MDP !!";
      $error .= "<p class='col-md-4 mx-auto bg-danger text-white text-center p-3 rounded'>Identifiants invalide.</p>";

      }


    }
    


    else
    {
      // echo "Email ou pseudo inexistant";
      $error .= "<p class='col-md-4 mx-auto bg-danger text-white text-center p-3 rounded'>Identifiants invalide.</p>";
    }

}




require_once("include/header.inc.php");
require_once("include/nav.inc.php");
?>

<h1 class="display-4 text-center my-3">Identifiez-vous</h1>

<?php
// Affichage message utilisateur :
if(isset($validInscrip)) echo $validInscrip; 
if(isset($error)) echo ($error);
// if(isset($valid)) echo $valid; 

?>

<form method="post" class="col-md-5 mx-auto">

  <div class="form-group">
    <label for="email_pseudo">Email ou Pseudo :</label>
    <input type="text" class="form-control" id="email_pseudo" name="email_pseudo">
  </div>

  <div class="form-group">
    <label for="mdp">Mot de passe :</label>
    <input type="text" class="form-control" id="mdp" name="mdp">
  </div>

  <button type="submit" class="btn btn-dark">Connexion</button>

</form>



<?php
require_once("include/footer.inc.php");