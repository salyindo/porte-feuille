<?php

// Vérifier si le téléphone existe
function telephoneExiste($telephone) {
    $index = trouverWalletParTelephone($telephone);
    return $index !== -1;
}

// Vérifier si le téléphone est unique
function telephoneUnique($telephone) {
    return !telephoneExiste($telephone);
}

// Vérifier si le code est unique
function codeUnique($code) {
    global $wallets;
    for ($i = 0; $i < count($wallets); $i++) {
        if ($wallets[$i]['code'] === $code) {
            return false;
        }
    }
    return true;
}

// Vérifier si le solde initial est valide
function soldeInitialValide($solde) {
    return is_numeric($solde) && $solde >= 0;
}

// Vérifier si le montant est positif
function montantPositif($montant) {
    return is_numeric($montant) && $montant > 0;
}