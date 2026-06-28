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
    $telephone = trim($telephone);

    if (!isset($wallets) || !is_array($wallets) || count($wallets) === 0) {
        return true;
    }

    $telephonesExistants = array_map(fn($wallet) => trim($wallet['telephone']), $wallets);
    return !in_array($telephone, $telephonesExistants);
}

function verifierUniciteCode($code) {
    global $wallets;
    $code = trim($code);

    if (!isset($wallets) || !is_array($wallets) || count($wallets) === 0) {
        return true;
    }

    $codesExistants = array_map(fn($wallet) => trim($wallet['code']), $wallets);
    return !in_array($code, $codesExistants);
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

function verifierExistenceTelephone($telephone) {
    global $wallets;
    $telephone = trim($telephone);

    if (!isset($wallets) || !is_array($wallets) || count($wallets) === 0) {
        return false;
    }

    $comptesTrouves = array_filter($wallets, fn($wallet) => trim($wallet['telephone']) === $telephone);
    return count($comptesTrouves) > 0;
}

function verifierMontantStrictementPositif($montant) {
    if (!verifierChampObligatoire($montant) || !verifierEstNumerique($montant)) {
        return false;
    }
    return (float)$montant > 0;
}

function verifierSoldeDisponible($telephone, $montantTotalRequis) {
    global $wallets;
    $telephone = trim($telephone);

    if (!isset($wallets) || !is_array($wallets) || count($wallets) === 0) {
        return false;
    }

    $comptesTrouves = array_filter($wallets, fn($wallet) => trim($wallet['telephone']) === $telephone);

    if (count($comptesTrouves) > 0) {
        $reindexed = array_values($comptesTrouves);
        $wallet = $reindexed[0];
        return (float)$wallet['solde'] >= (float)$montantTotalRequis;
    }
    return false;
}
