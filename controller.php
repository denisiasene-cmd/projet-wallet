<?php
require_once "repository.php";
require_once "validator.php";

function executerCreerWallet() {
    
}

function routerAction($choix) {
    switch (trim($choix)) {
        case '1':
            executerCreerWallet();
            break;
        case '2':
            executerFaireDepot(); 
            break;
        case '3':
            echo "\n[Retrait] Module bientôt disponible.\n";
            break;
        case '4':
            echo "\n[Transactions] Module bientôt disponible.\n";
            break;
        case '0':
            echo "\nMerci d'avoir utilisé E-Wallet. Au revoir !\n";
            break;
        default:
            echo "\nChoix invalide, veuillez réessayer.\n";
            break;
    }
}

function executerFaireDepot() {
    echo "\n--- FORMULAIRE DE DÉPÔT DE FONDS ---\n";

    do {
        $telephone = readline("Entrez le numéro du bénéficiaire : ");
        $telephone = trim($telephone);
        if (!verifierChampObligatoire($telephone) || !verifierExistenceTelephone($telephone)) {
            echo "Erreur: \n";
        }
    } while (!verifierChampObligatoire($telephone) || !verifierExistenceTelephone($telephone));

    do {
        $montant = readline("Entrez le montant à déposer (CFA) : ");
        $montant = trim($montant);
        if (!verifierMontantStrictementPositif($montant)) {
            echo "Erreur: Le montant doit être strictement supérieur à 0 CFA (RG2).\n";
        }
    } while (!verifierMontantStrictementPositif($montant));

    modifierSoldeDepot($telephone, $montant);

    echo "\nSuccès: Dépôt de " . $montant . " CFA effectué sur le numéro " . $telephone . " !\n";
}
