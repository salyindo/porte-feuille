<?php

// Router vers la bonne fonction
function traiterChoix($choix) {
    switch ($choix) {
        case 1: controllerCreerWallet(); break;
        case 2: controllerDepot();       break;
        case 3: controllerRetrait();     break;
        case 4: controllerTransactions(); break;
    }
}

// Créer un wallet
function controllerCreerWallet() {
    echo "--- Créer un Wallet ---\n";
    $newWallet = [];
    $newWallet['client']    = readline("Nom du client : ");
    $newWallet['telephone'] = readline("Téléphone : ");
    $newWallet['code']      = (int)readline("Code secret : ");
    $newWallet['solde']     = (int)readline("Solde initial : ");

    $resultat = creerWallet($newWallet);
    echo $resultat['message'] . "\n";
}

// Faire un dépôt
function controllerDepot() {
    echo "--- Faire un Dépôt ---\n";
    $telephone = readline("Téléphone : ");
    $montant   = (int)readline("Montant : ");

    $resultat = faireDepot($telephone, $montant);
    echo $resultat['message'] . "\n";
}

// Faire un retrait
function controllerRetrait() {
    echo "--- Faire un Retrait ---\n";
    $telephone = readline("Téléphone : ");
    $montant   = (int)readline("Montant : ");

    $resultat = faireRetrait($telephone, $montant);
    echo $resultat['message'] . "\n";
}

// Lister les transactions
function controllerTransactions() {
    echo "--- Lister les Transactions ---\n";
    $telephone = readline("Téléphone (vide pour tout voir) : ");

    if (empty($telephone)) {
        $transactions = listerTransactions();
    } else {
        $transactions = listerTransactions($telephone);
    }

    if (count($transactions) === 0) {
        echo "Aucune transaction trouvée\n";
        return;
    }

    for ($i = 0; $i < count($transactions); $i++) {
        $t = $transactions[$i];
        echo "[{$t['date']}] {$t['telephone']} | {$t['type']} | {$t['montant']} CFA | Frais: {$t['frais']} CFA\n";
    }
}