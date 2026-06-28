<?php
require_once "repository.php";
require_once "validator.php";

function routerAction($choix) {
    $choix = trim($choix);

    switch ($choix) {
        case '1':
            echo "\n  Creer un wallet\n";
           
            break;
            
        case '2':
            echo "\n faire dépôt...\n";
            break;
            
        case '3':
            echo "\n faire retrait...\n";
            break;
            
        case '4':
            echo "\n l'historique des transactions...\n";
            break;
            
        case '0':
            echo "\n Quitter\n";
            break;
            
        default:
            echo "\nVeuillez entrer un chiffre entre 0 et 4.\n";
            break;
    }
}
