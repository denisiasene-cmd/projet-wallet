<?php
require_once "controller.php";

do {
    echo "\n====================================\n";
    echo "         MENU PRINCIPAL E-WALLET     \n";
    echo "====================================\n";
    echo "1. Créer un Wallet\n";
    echo "2. Faire un Dépôt\n";
    echo "3. Faire un Retrait\n";
    echo "4. Lister les Transactions\n";
    echo "0. Quitter l'application\n";
    echo "====================================\n";
    
    $choix = readline("Faites votre choix : ");
    $choix = trim($choix);

  
    routerAction($choix);

} while ($choix !== '0');
