<?php

// Les wallets (comme dans ton exemple)
$wallets = [
    0 => ['client' => 'Baila Wane', 'telephone' => '771001010', 'code' => 1234, 'solde' => 0],
    1 => ['client' => 'Hawa Baila Wane', 'telephone' => '782345678', 'code' => 0000, 'solde' => 100000]
];

// Les transactions
$transactions = [];

// Trouver un wallet par téléphone
function trouverWalletParTelephone($telephone) {
    global $wallets;
    for ($i = 0; $i < count($wallets); $i++) {
        if ($wallets[$i]['telephone'] === $telephone) {
            return $i;
        }
    }
    return -1;
}

// Ajouter un wallet
function ajouterWallet($newWallet) {
    global $wallets;
    $wallets[] = $newWallet;
}

// Mettre à jour le solde
function mettreAJourSolde($index, $nouveauSolde) {
    global $wallets;
    $wallets[$index]['solde'] = $nouveauSolde;
}

// Ajouter une transaction
function ajouterTransaction($telephone, $type, $montant, $frais) {
    global $transactions;
    $transactions[] = [
        'telephone' => $telephone,
        'type'      => $type,
        'montant'   => $montant,
        'frais'     => $frais,
        'date'      => date('d/m/Y H:i')
    ];
}