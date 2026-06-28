<?php

/**
 * FEATURE 3 - RG 3.2 : Calcul algorithmique des frais par paliers
 */
function calculerFraisRetrait($montant) {
    $montant = (float)$montant;

    // Palier 1 : Entre 0 et 10 000 CFA -> 200 CFA fixes
    if ($montant >= 0 && $montant <= 10000) {
        return 200.0;
    }
    
    // Palier 2 : Entre 10 001 et 100 000 CFA -> 500 CFA fixes
    if ($montant > 10000 && $montant <= 100000) {
        return 500.0;
    }
    
    // Palier 3 : Strictement supérieur à 100 000 CFA -> 1%
    if ($montant > 100000) {
        $frais = $montant * 0.01;
        
        // Plafond strict exigé de 5000 CFA max
        if ($frais > 5000) {
            return 5000.0;
        }
        return $frais;
    }

    return 0.0;
}
