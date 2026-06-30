<?php

$wallets = [];
$transactions = [];

function ajouterWallet($nouveauWallet) {
    global $wallets;
    
  
    $prochainIndex = 0;
    foreach ($wallets as $w) {
        $prochainIndex = $prochainIndex + 1;
    }
    
    $wallets[$prochainIndex] = $nouveauWallet;
}
function calculerFraisRetrait($montant) {
    $montant = (float)$montant;

   
    if ($montant >= 0 && $montant <= 10000) {
        return 200.0;
    }
    
  
    if ($montant > 10000 && $montant <= 100000) {
        return 500.0;
    }
    
    
    if ($montant > 100000) {
        $frais = $montant * 0.01;
        
        
        if ($frais > 5000) {
            return 5000.0;
        }
        return $frais;
    }

    return 0.0;
}

function modifierSoldeDepot($telephone, $montant) {
    global $wallets;
    $telephone = trim($telephone);
    $montant = (float)$montant;

    foreach ($wallets as &$wallet) {
        if ($wallet['telephone'] === $telephone) {
            $wallet['solde'] = $wallet['solde'] + $montant;
            break;
        }
    }
}

function recupererTransactions() {
    global $transactions;
    return $transactions;
}

?>


