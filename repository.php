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
