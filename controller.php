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
            executerFaireRetrait();
            break;
        case '4':
            
            executerListerTransactions();
            break;
        case '0':
            echo "\n Quitter\n";
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
function executerFaireRetrait() {
    echo "\n--- FORMULAIRE DE RETRAIT DE FONDS ---\n";

    do {
        $telephone = readline("Entrez votre numéro de téléphone : ");
        $telephone = trim($telephone);
        if (!verifierChampObligatoire($telephone) || !verifierExistenceTelephone($telephone)) {
            echo "Erreur: Ce numéro de téléphone n'est associé à aucun Wallet actif.\n";
        }
    } while (!verifierChampObligatoire($telephone) || !verifierExistenceTelephone($telephone));

   
    do {
        $montant = readline("Entrez le montant à retirer (CFA) : ");
        $montant = trim($montant);
        if (!verifierMontantStrictementPositif($montant)) {
            echo "Erreur: Le montant de retrait doit être supérieur à 0 CFA.\n";
        }
    } while (!verifierMontantStrictementPositif($montant));

    
    $frais = calculerFraisRetrait($montant);
    $sommeTotaleRequise = (float)$montant + $frais;

    if (!verifierSoldeDisponible($telephone, $sommeTotaleRequise)) {
        echo "Erreur: Solde insuffisant. Votre solde actuel ne couvre pas le retrait (" . $montant . " CFA) et ses frais (" . $frais . " CFA).\n";
        return;
    }

    modifierSoldeRetrait($telephone, $sommeTotaleRequise);
    enregistrerTransaction($telephone, 'retrait', $montant, $frais);

    echo "\nSuccès: Retrait effectué ! Montant: " . $montant . " CFA | Frais: " . $frais . " CFA déduits avec succès.\n";
}

function executerListerTransactions() {
    echo "\n=== TRANSACTIONS ===\n";
    $liste = recupererTransactions();

    foreach ($liste as $t) {
        echo "Tél: " . $t['telephone'] . " | " . $t['type'] . " | " . $t['montant'] . " CFA | Frais: " . $t['frais'] . " CFA\n";
    }
}

