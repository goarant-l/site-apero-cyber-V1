<?php
$pages_autorisees = [
   'accueil',
   'apropos',
   'contact',
   'connexion',            
   'connexion_exe',        
   'admin',                
   'deconnexion',        
   'connexion_choix',     
   'connexion_client',    
   'connexion_client_exe', 
   'inscription_client',   
   'inscription_client_exe',
   'detail_produit',      
   'ajout_panier_exe',
   'detail_produit',
   'ajout_panier',
   'details_panier',
   'historique_commande',
   'supprimer_panier_exe',
   'valider_commande_exe'       
];

$page = isset($_GET['page']) && in_array($_GET['page'], $pages_autorisees)
    ? $_GET['page']
    : 'accueil';

$fichier = $page . '.php';

include('./header.php');

if (file_exists($fichier)) {
    include($fichier);
} else {
    include('./accueil.php');
}

include('./footer.php');
?>