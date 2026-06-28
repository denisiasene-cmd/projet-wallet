<?php
require_once "repository.php";
require_once "validator.php";

function executerCreerWallet() {
    echo "\n--- FORMULAIRE DE CRÉATION DE WALLET ---\n";

  
    do {
        $nom = readline("Entrez le nom du client : ");
        if (!verifierFormatNom($nom)) {
            echo "Erreur: Le nom est obligatoire .\n";
        }
    } while (!verifierFormatNom($nom));

    do {
        $telephone = readline("Entrez le numéro de téléphone : ");
        $telephone = trim($telephone);
        $telephoneValide = true;

        if (!verifierChampObligatoire($telephone)) {
            echo "Erreur: Le numéro de téléphone est obligatoire .\n";
            $telephoneValide = false;
        } elseif (!verifierEstNumerique($telephone) || !verifierTailleExacte($telephone, 9)) {
            echo "Erreur: Le numéro doit comporter exactement 9 chiffres.\n";
            $telephoneValide = false;
        } elseif (!verifierPrefixeSenegal($telephone)) {
            echo "Erreur: Préfixe invalide .\n";
            $telephoneValide = false;
        } elseif (!verifierUniciteTelephone($telephone)) {
            echo "Erreur: Ce numéro de téléphone est déjà utilisé .\n";
            $telephoneValide = false;
        }
    } while (!$telephoneValide);

   
    do {
        $solde = readline("Entrez le solde initial (positif ou nul >= 0) : ");
        $solde = trim($solde);
        if (!verifierSoldeInitial($solde)) {
            echo "Erreur: Le solde est obligatoire et doit être positif ou nul (RG1).\n";
        }
    } while (!verifierSoldeInitial($solde));

    do {
        $code = readline("Définissez un code secret (4 chiffres) : ");
        $code = trim($code);
        $codeValide = true;

        if (!verifierChampObligatoire($code)) {
            echo "Erreur: Le code secret est obligatoire .\n";
            $codeValide = false;
        } elseif (!verifierEstNumerique($code) || !verifierTailleExacte($code, 4)) {
            echo "Erreur: Le code invalide.\n";
            $codeValide = false;
        } elseif (!verifierUniciteCode($code)) {
            echo "Erreur: Ce code secret est déjà attribué à un autre compte (\n";
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

function routerAction($choix) {
    switch (trim($choix)) {
        case '1':
            executerCreerWallet();
            break;
        case '2':
            echo "\n  faire depots.\n";
            break;
        case '3':
            echo "\n faire un retrait \n";
            break;
        case '4':
            echo "\n listes des trasactions\n";
            break;
        case '0':
            echo "\n quitter \n";
            break;
        default:
            echo "\nChoix invalide, veuillez réessayer.\n";
            break;
    }
}
