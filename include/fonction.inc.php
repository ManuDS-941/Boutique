<?php 
// Fontion utilisateur connecté

function connect()
{
    // Si l'indice 'user' dans le fichier session N'EST PAS définit, cela veut dire que l'utilisateur ne s'est pas connecté
    if(!isset($_SESSION['user']))
    {
        return false;
    }
    else // SINON, l'indice 'user' dans la session est bien définit, l'internaute est bien passé par la cpage connexion et est bien connecté !!
    {
        return true;
    }
}

// Fonction administrateur connecté

function adminConnect()
{
    // SI, l'indice 'user' est bien définit dans la session (le membre est connecté) et que dans la session, l'indice 'statut' a pour valeur '1', alors il est administrateur du site !
    if(connect() && $_SESSION['user']['statut'] == 1)
    {
        return true;
    }
    else // Sinon, le statut n'a pas pour valeur '1' dans la session, alors c'est un membre connecté, ou alors l'internaute n'est tout simplement pas connecté !
    {
        return false;
    }
}

// CREATION DU PANIER DANS LA SESSION
// Les informations du panier ne sont jamais conservés en BDD mais dansle fichier session de l'utilisateur

function creationPanier()
{
    if(!isset($_SESSION['panier']))
    {
        // On crée un indice 'panier' dans la session qui est un ARRAY
        $_SESSION['panier'] = array(); 
        $_SESSION['panier']['id_produit'] = array();
        $_SESSION['panier']['photo'] = array(); // dans le tableau ARRAY panier dans la session, on crée d'autre ARRAY afin de stocker tout les titre, reference, quantite etc 
        $_SESSION['panier']['reference'] = array();
        $_SESSION['panier']['titre'] = array();
        $_SESSION['panier']['quantite'] = array();
        $_SESSION['panier']['prix'] = array();
    }
}

/*
    [user] => ARRAY (

        informations du membre
    )

    [panier] => ARRAY (

        [id_produit] => ARRAY (
          
        )

        [titre] => ARRAY (
          
        )

        [quantite] => ARRAY (
           
        )

        [reference] => ARRAY (
            
        )

        [photo] => ARRAY (
            0 => http://localhost/assets/img.jpg
        )
    )
*/

// AJOUT D'UN PRODUIT DANS LA SESSION
                    //     2       URL      23T23    pull rouge     4    23
function ajoutPanier($id_produit, $photo, $reference, $titre, $quantite, $prix)
{
    creationPanier();

    // array_search() permet de trouver à quel indice se trouve un id_produit dans la sessio dans le tableau $_SESSION['panier']['id_produit'] (ARRAY)
    // [1]
    $position_produit = array_search($id_produit, $_SESSION['panier']['id_produit']);

    // var_dump($position_produit) . '<hr>';

    // Si position_produit retourne un indice, cela veut dire que le produit est déjà présent dans le session, alors on modifie la quantité à l'indice correspondant
    if($position_produit !== false)
    {
        // $_SESSION['panier']['quantite'][1] += 2
        $_SESSION['panier']['quantite'][$position_produit] += $quantite;
    }
    else // Sinon le produit n'est pas dans la session panier, on crée une nouvelle ligne dans chaque ARRAY pour stocker les informations du produit ajouté dans le panier
    {
        $_SESSION['panier']['id_produit'][] = $id_produit; // 2 
        $_SESSION['panier']['photo'][] = $photo; // URL
        $_SESSION['panier']['reference'][] = $reference; // 23T23
        $_SESSION['panier']['titre'][] = $titre; // pull rouge
        $_SESSION['panier']['quantite'][] = $quantite;
        $_SESSION['panier']['prix'][] = $prix;
    }
}

// FONCTION MONTANT TOTAL DU PANIER

function montantTotal()
{
    $total = 0;
    // La boucle tourne autant que l'on a de produit stocké dans le panier donc dans la session
    for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
    {
        // On multiplie la quantité commandé par le prix du produit et on stock le montant total dans la variable $total
        $total += $_SESSION['panier']['quantite'][$i]*$_SESSION['panier']['prix'][$i];
    }
    // On arrondi le montant a 2 chiffres après la virgule
    return round($total,2);
}

// FONCTION SUPPRESSION PRODUIT DANS LE PANIER
                            // 2
function suppProduitPanier($id_produit)
{   //   [0]                               // 5
    $position_produit = array_search($id_produit, $_SESSION['panier']['id_produit']);
    // array_search() retourne à quel indice se trouve dans le tableau ARRAY 'id_produit' le produit que l'on veut supprimé

    // array_splice() permet de supprimer une une ligne dans la session dans chaque tableau ARRAY définit 
    // On supprime dans la session tout les éléments du produit dans les différents tableaux ARRAY (id_produit, photo, reference etc..)
    // array_splice() réorganise les tableaux,  c'est à dire que le produit se trouvant à l'indice [3] sera remonté à l'indice [2] du tableau
    if($position_produit !== false)
    {
        //                                                      [1]
        array_splice($_SESSION['panier']['id_produit'], $position_produit, 1);
        array_splice($_SESSION['panier']['photo'], $position_produit, 1);
        array_splice($_SESSION['panier']['reference'], $position_produit, 1);
        array_splice($_SESSION['panier']['titre'], $position_produit, 1);
        array_splice($_SESSION['panier']['quantite'], $position_produit, 1);
        array_splice($_SESSION['panier']['prix'], $position_produit, 1);
    }
}

/*
    [panier] => Array
        (
            [id_produit] => Array
                (
                    [0] => 2
                    
                    [1] => 8
                )

            [photo] => Array
                (
                    [0] => http://localhost/PHP/10-boutique/assets/23T23-tee-shirt-6.jpg
                   
                    [1] => http://localhost/PHP/10-boutique/assets/23T23-tee-shirt-6.jpg
                )

            [reference] => Array
                (
                    [0] => 23T23
                    
                    [1] => 38J78
                )

            [titre] => Array
                (
                    [0] => pull rouge
                   
                    [1] => chapeau
                )

            [quantite] => Array
                (
                    [0] => 9
                    [1] => 6
                
                )

            [prix] => Array
                (
                    [0] => 23
                  
                    [1] => 45
                )
*/
