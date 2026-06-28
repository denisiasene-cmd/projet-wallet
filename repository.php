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

function modifierSoldeDepot($telephone, $montant) {
    global $wallets;
    $telephone = trim($telephone);
    $montant = (float)$montant;

    $wallets = array_map(function($w) use ($telephone, $montant) {
        if (trim($w['telephone']) === $telephone) {
            $w['solde'] = $w['solde'] + $montant;
        }
        return $w;
    }, $wallets);
    
    enregistrerTransaction($telephone, 'depot', $montant, 0);
}

function modifierSoldeRetrait($telephone, $montantTotal) {
    global $wallets;
    $telephone = trim($telephone);
    $montantTotal = (float)$montantTotal;

    $wallets = array_map(function($w) use ($telephone, $montantTotal) {
        if (trim($w['telephone']) === $telephone) {
            $w['solde'] = $w['solde'] - $montantTotal;
        }
        return $w;
    }, $wallets);
}


function enregistrerTransaction($telephone, $type, $montant, $frais) {
    global $transactions;
    
    $index = 0;
    foreach ($transactions as $t) {
        $index++;
    }

    $transactions[$index] = [
        'telephone' => trim($telephone),
        'type' => $type,
        'montant' => (float)$montant,
        'frais' => (float)$frais
    ];
}


function recupererTransactions() {
    global $transactions;
    return $transactions;
}
