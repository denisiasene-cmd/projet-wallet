<?php
require_once "repository.php";
require_once "validator.php";
require_once "services.php";

function executerCreerWallet() {
    echo "\n--- FORMULAIRE DE CRÉATION DE WALLET ---\n";

    do {
        $nom = readline("Entrez le nom du client : ");
        if (!verifierFormatNom($nom)) {
            echo "Erreur: Le nom est obligatoire \n";
        }
    } while (!verifierFormatNom($nom));

    do {
        $telephone = readline("Entrez le numéro de téléphone  : ");
        $telephone = trim($telephone);
        $telephoneValide = true;

        if (!verifierChampObligatoire($telephone)) {
            echo "Erreur: Le numéro de téléphone est obligatoire.\n";
            $telephoneValide = false;
        } elseif (!verifierEstNumerique($telephone) || !verifierTailleExacte($telephone, 9)) {
            echo "Erreur: Le numéro doit comporter exactement 9 chiffres.\n";
            $telephoneValide = false;
        } elseif (!verifierPrefixeSenegal($telephone)) {
            echo "Erreur: numero invalide. \n";
            $telephoneValide = false;
        } elseif (!verifierUniciteTelephone($telephone)) {
            echo "Erreur: Ce numéro de téléphone est déjà utilisé.\n";
            $telephoneValide = false;
        }
    } while (!$telephoneValide);

    do {
        $solde = readline("Entrez le solde initial (positif ou nul >= 0) : ");
        $solde = trim($solde);
        if (!verifierSoldeInitial($solde)) {
            echo "Erreur: Le solde est obligatoire et doit être positif ou nul.\n";
        }
    } while (!verifierSoldeInitial($solde));

    do {
        $code = readline("entrer votre code secret  : ");
        $code = trim($code);
        $codeValide = true;

        if (!verifierChampObligatoire($code)) {
            echo "Erreur: Le code secret est obligatoire.\n";
            $codeValide = false;
        } elseif (!verifierEstNumerique($code) || !verifierTailleExacte($code, 4)) {
            echo "Erreur: Le code secret invalide\n";
            $codeValide = false;
        } elseif (!verifierUniciteCode($code)) {
            echo "Erreur: Ce code secret est déjà attribué.\n";
            $codeValide = false;
        }
    } while (!$codeValide);

    $nouveauWallet = [
        'telephone' => $telephone,
        'nom' => strtoupper($nom),
        'solde' => (float)$solde,
        'code' => $code
    ];

    ajouterWallet($nouveauWallet);
    echo "\nSuccès: Le Wallet a été créé avec succès pour " . strtoupper($nom) . " !\n";
}

function executerFaireDepot() {
    echo "\n--- FORMULAIRE DE DÉPÔT DE FONDS ---\n";

    do {
        $telephone = readline("Entrez le numéro du bénéficiaire : ");
        $telephone = trim($telephone);
        if (!verifierChampObligatoire($telephone) || !verifierExistenceTelephone($telephone)) {
            echo "Erreur: Ce numéro n'est associé à aucun Wallet actif.\n";
        }
    } while (!verifierChampObligatoire($telephone) || !verifierExistenceTelephone($telephone));

    do {
        $montant = readline("Entrez le montant à déposer (CFA) : ");
        $montant = trim($montant);
        if (!verifierMontantStrictementPositif($montant)) {
            echo "Erreur: Le montant doit être strictement supérieur à 0 CFA.\n";
        }
    } while (!verifierMontantStrictementPositif($montant));

    modifierSoldeDepot($telephone, $montant);
    echo "\nSuccès: Dépôt effectué sur le numéro " . $telephone . " !\n";
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
        echo "Erreur: Solde insuffisant pour couvrir le retrait (" . $montant . " CFA) et ses frais (" . $frais . " CFA).\n";
        return;
    }

    modifierSoldeRetrait($telephone, $sommeTotaleRequise);
    enregistrerTransaction($telephone, 'retrait', $montant, $frais);

    echo "\nSuccès: Retrait effectué ! Frais de " . $frais . " CFA appliqués.\n";
}

function executerListerTransactions() {
    echo "\n=== TRANSACTIONS ===\n";
    $liste = recupererTransactions();
    
    $compteur = 0;
    foreach ($liste as $t) {
        $compteur++;
        echo "Tél: " . $t['telephone'] . " | " . $t['type'] . " | " . $t['montant'] . " CFA | Frais: " . $t['frais'] . " CFA\n";
    }
    
    if ($compteur === 0) {
        echo "Aucune transaction enregistrée.\n";
    }
}

function routerAction($choix) {
    switch (trim($choix)) {
        case '1': executerCreerWallet(); break;
        case '2': executerFaireDepot(); break;
        case '3': executerFaireRetrait(); break;
        case '4': executerListerTransactions(); break;
        case '0': echo "\nMerci d'avoir utilisé E-Wallet. Au revoir !\n"; break;
        default: echo "\nChoix invalide, veuillez réessayer.\n"; break;
    }
}
