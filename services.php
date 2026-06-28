<?php


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
