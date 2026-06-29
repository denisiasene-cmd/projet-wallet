<?php
require_once "repository.php";

function verifierChampObligatoire($valeur) {
    $tailleUtile = 0;
    for ($i = 0; isset($valeur[$i]); $i++) {
        if ($valeur[$i] !== ' ' && $valeur[$i] !== "\n" && $valeur[$i] !== "\r" && $valeur[$i] !== "\t") {
            $tailleUtile = $tailleUtile + 1;
        }
    }
    return $tailleUtile > 0;
}


function verifierEstNumerique($chaine) {
    $taille = 0;
    for ($i = 0; isset($chaine[$i]); $i++) {
        if ($chaine[$i] < '0' || $chaine[$i] > '9') {
            return false;
        }
        $taille = $taille + 1;
    }
    return $taille > 0;
}


function verifierTailleExacte($chaine, $tailleAttendue) {
    $taille = 0;
    for ($i = 0; isset($chaine[$i]); $i++) {
        $taille = $taille + 1;
    }
    return $taille === $tailleAttendue;
}


function verifierPrefixeSenegal($telephone) {
    $telephone = trim($telephone);
    if (str_starts_with($telephone, "77") || 
        str_starts_with($telephone, "78") || 
        str_starts_with($telephone, "76") || 
        str_starts_with($telephone, "70") || 
        str_starts_with($telephone, "75")) {
        return true;
    }
    return false;
}

function verifierSoldeInitial($solde) {
    if (!verifierChampObligatoire($solde)) {
        return false;
    }
    $nombreDePoints = 0;
    $taille = 0;
    for ($i = 0; isset($solde[$i]); $i++) {
        $char = $solde[$i];
        if ($char === '.') {
            $nombreDePoints = $nombreDePoints + 1;
            if ($nombreDePoints > 1) return false;
            continue;
        }
        if ($char < '0' || $char > '9') return false;
        $taille = $taille + 1;
    }
    return $taille > 0;
}


function verifierUniciteTelephone($telephone) {
    global $wallets;
    foreach ($wallets as $wallet) {
        if ($wallet['telephone'] === $telephone) {
            return false; 
        }
    }
    return true; 
}

function verifierUniciteCode($code) {
    global $wallets;
    foreach ($wallets as $wallet) {
        if ($wallet['code'] === $code) {
            return false; 
        }
    }
    return true; 
}

function verifierFormatNom($nom) {
    if (!verifierChampObligatoire($nom)) return false;
    for ($i = 0; isset($nom[$i]); $i++) {
        $char = $nom[$i];
        $isMaj = ($char >= 'A' && $char <= 'Z');
        $isMin = ($char >= 'a' && $char <= 'z');
        $isEsp = ($char === ' ');
        if (!$isMaj && !$isMin && !$isEsp) return false;
    }
    return true;
}
