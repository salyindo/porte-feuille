# Présentation Technique — Partie B
## Projet E-Wallet PHP

---

# Sommaire

1. Fonctions Anonymes / Fléchées / Closures
2. Fonctions natives de tableaux (array_*)
3. Composer — Gestionnaire de dépendances
4. Packagist.org — L'écosystème PHP

---

# 1. Fonctions Anonymes, Fléchées et Closures

---

## 1.1 — C'est quoi une fonction anonyme ?

Une fonction **sans nom**, qu'on peut stocker dans une variable ou passer en paramètre.

### Fonction classique (avec nom)
```php
function doubler($n) {
    return $n * 2;
}
echo doubler(5); // 10
```

### Fonction anonyme (sans nom)
```php
$doubler = function($n) {
    return $n * 2;
};
echo $doubler(5); // 10
```

> La différence : la fonction anonyme est stockée dans `$doubler` comme une variable.

---

## 1.2 — Closure

Une **Closure** est une fonction anonyme qui peut **capturer des variables** de son environnement extérieur avec le mot-clé `use`.

```php
$taux = 0.18; // Variable extérieure

$calculerTaxe = function($montant) use ($taux) {
    return $montant * $taux;
};

echo $calculerTaxe(10000); // 1800
```

> Sans `use`, la fonction ne peut pas accéder à `$taux`.

### Utilisation dans notre projet E-Wallet
```php
// On capture $telephone pour filtrer les transactions
$telephone = "771234567";

$filtrer = function($transaction) use ($telephone) {
    return $transaction["telephone"] === $telephone;
};
```

---

## 1.3 — Fonction fléchée (Arrow function)

Introduite en **PHP 7.4**. Version courte d'une fonction anonyme.

- Pas besoin de `use` — capture automatique des variables extérieures
- Syntaxe : `fn($param) => expression`

### Comparaison

```php
$multiplicateur = 3;

// Fonction anonyme — besoin de use
$triple = function($n) use ($multiplicateur) {
    return $n * $multiplicateur;
};

// Fonction fléchée — capture automatique
$triple = fn($n) => $n * $multiplicateur;

echo $triple(5); // 15
```

### Utilisation dans notre projet E-Wallet
```php
// Partie B — recherche avec fonction fléchée
$index = array_search(
    $telephone,
    array_column($wallets, "telephone")
);

// Filtrage avec fonction fléchée
$resultat = array_filter(
    $wallets,
    fn($w) => $w["code"] === $code
);
```

---

## 1.4 — Résumé comparatif

| Type | Syntaxe | Capture externe | Cas d'usage |
|---|---|---|---|
| Classique | `function nom() {}` | Non | Fonctions réutilisables |
| Anonyme | `function() {}` | Avec `use` | Callbacks, stockage |
| Fléchée | `fn() =>` | Automatique | Expressions courtes |

---

# 2. Fonctions natives de tableaux (array_*)

---

## 2.1 — Pourquoi les utiliser ?

### Partie A — Boucle manuelle
```php
// Chercher un wallet — 6 lignes
for ($i = 0; $i < count($wallets); $i++) {
    if ($wallets[$i]["telephone"] === $telephone) {
        return $i;
    }
}
return -1;
```

### Partie B — Fonction native
```php
// Même résultat — 2 lignes
$index = array_search($telephone, array_column($wallets, "telephone"));
return $index !== false ? $index : -1;
```

> Plus court, plus lisible, plus rapide !

---

## 2.2 — array_map

Applique une fonction à **chaque élément** d'un tableau et retourne un nouveau tableau.

```php
$nombres = [1, 2, 3, 4, 5];

// Doubler chaque nombre
$doubles = array_map(fn($n) => $n * 2, $nombres);
// Résultat : [2, 4, 6, 8, 10]

// Appliquer des frais à chaque montant
$montants = [5000, 50000, 200000];
$avecFrais = array_map(fn($m) => $m + calculerFrais($m), $montants);
```

---

## 2.3 — array_filter

**Filtre** les éléments d'un tableau selon une condition.

```php
$transactions = [
    ["telephone" => "771234567", "type" => "depot",   "montant" => 5000],
    ["telephone" => "782345678", "type" => "retrait",  "montant" => 2000],
    ["telephone" => "771234567", "type" => "retrait",  "montant" => 1000],
];

// Garder seulement les transactions de 771234567
$resultat = array_filter(
    $transactions,
    fn($t) => $t["telephone"] === "771234567"
);
// Résultat : transactions 1 et 3
```

---

## 2.4 — array_search

Cherche une **valeur** dans un tableau et retourne son index.

```php
$fruits = ["pomme", "banane", "cerise"];

$index = array_search("banane", $fruits);
// Résultat : 1

// Si non trouvé → false
$index = array_search("mangue", $fruits);
// Résultat : false
```

---

## 2.5 — array_column

Extrait **une colonne** d'un tableau multidimensionnel.

```php
$wallets = [
    ["telephone" => "771234567", "nom" => "Alice", "solde" => 5000],
    ["telephone" => "782345678", "nom" => "Bob",   "solde" => 3000],
];

// Extraire tous les numéros de téléphone
$telephones = array_column($wallets, "telephone");
// Résultat : ["771234567", "782345678"]

// Utilisation dans notre projet
$index = array_search($telephone, array_column($wallets, "telephone"));
```

---

## 2.6 — array_reduce

**Réduit** un tableau à une seule valeur.

```php
$montants = [1000, 2000, 3000, 4000];

// Calculer la somme totale
$total = array_reduce(
    $montants,
    fn($carry, $item) => $carry + $item,
    0  // Valeur initiale
);
// Résultat : 10000
```

---

## 2.7 — Résumé des fonctions array_*

| Fonction | Rôle | Retourne |
|---|---|---|
| `array_map()` | Transformer chaque élément | Nouveau tableau |
| `array_filter()` | Garder certains éléments | Tableau filtré |
| `array_search()` | Chercher une valeur | Index ou false |
| `array_column()` | Extraire une colonne | Tableau de valeurs |
| `array_reduce()` | Calculer une valeur globale | Une valeur |
| `array_push()` | Ajouter un élément | void |
| `array_pop()` | Supprimer le dernier | L'élément supprimé |
| `array_merge()` | Fusionner deux tableaux | Nouveau tableau |
| `in_array()` | Vérifier si valeur existe | true / false |

---

# 3. Composer — Gestionnaire de dépendances

---

## 3.1 — C'est quoi Composer ?

Composer est l'outil officiel de **gestion de dépendances** en PHP.

Il permet de :
- Installer des bibliothèques externes en une commande
- Gérer les versions des bibliothèques
- Partager facilement un projet avec ses dépendances

> C'est l'équivalent de `npm` pour JavaScript ou `pip` pour Python.

---

## 3.2 — Sans Composer vs Avec Composer

### Sans Composer
```
1. Aller sur le site de la bibliothèque
2. Télécharger le fichier ZIP
3. Extraire dans ton projet
4. Inclure manuellement avec require
5. Recommencer pour chaque mise à jour
```

### Avec Composer
```bash
composer require nom/bibliotheque
```
C'est tout ! Composer gère le reste automatiquement.

---

## 3.3 — Installation de Composer

```bash
# Télécharger Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php

# Vérifier l'installation
composer --version
```

---

## 3.4 — Le fichier composer.json

C'est le fichier de configuration de ton projet. Il liste toutes les dépendances.

```json
{
    "name": "mon/ewallet",
    "description": "Système de gestion de portefeuille électronique",
    "require": {
        "php": ">=8.0",
        "monolog/monolog": "^3.0"
    },
    "autoload": {
        "psr-4": {
            "EWallet\\": "src/"
        }
    }
}
```

---

## 3.5 — Commandes essentielles

```bash
# Initialiser un projet
composer init

# Installer une bibliothèque
composer require monolog/monolog

# Installer toutes les dépendances du projet
composer install

# Mettre à jour les dépendances
composer update

# Afficher les bibliothèques installées
composer show
```

---

## 3.6 — Le dossier vendor

Quand tu installes une bibliothèque, Composer crée un dossier `vendor/` :

```
mon-projet/
├── vendor/              ← Bibliothèques installées
│   ├── monolog/
│   └── autoload.php    ← Fichier d'autoload
├── composer.json        ← Configuration
├── composer.lock        ← Versions exactes installées
└── index.php
```

Dans ton code, tu n'as qu'une seule ligne à ajouter :
```php
<?php
require "vendor/autoload.php"; // Charge tout automatiquement
```

---

## 3.7 — Exemple concret

```bash
# Installer la bibliothèque de logs
composer require monolog/monolog
```

```php
<?php
require "vendor/autoload.php";

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Créer un logger
$log = new Logger("ewallet");
$log->pushHandler(new StreamHandler("transactions.log"));

// Enregistrer une transaction
$log->info("Dépôt effectué", ["telephone" => "771234567", "montant" => 5000]);
```

---

# 4. Packagist.org — L'écosystème PHP

---

## 4.1 — C'est quoi Packagist ?

**Packagist.org** est le dépôt central des bibliothèques PHP.

C'est le site où :
- Les développeurs publient leurs bibliothèques
- Composer cherche les packages à installer
- Tu peux trouver des solutions à tes problèmes

> Adresse : **https://packagist.org**

---

## 4.2 — Comment ça fonctionne ?

```
Développeur publie     →  Packagist.org  →  Composer installe
une bibliothèque           (dépôt central)    dans ton projet
```

Quand tu tapes `composer require monolog/monolog` :
1. Composer va sur Packagist.org
2. Cherche le package `monolog/monolog`
3. Télécharge la version compatible
4. L'installe dans `vendor/`

---

## 4.3 — Structure d'un package

Sur Packagist, chaque package a un nom en deux parties :
```
vendor/package
  │       │
  │       └── Nom de la bibliothèque
  └── Nom de l'auteur ou organisation
```

Exemples :
- `monolog/monolog` → logs
- `guzzlehttp/guzzle` → requêtes HTTP
- `phpunit/phpunit` → tests unitaires
- `vlucas/phpdotenv` → variables d'environnement

---

## 4.4 — Bibliothèques populaires

| Package | Utilité | Commande |
|---|---|---|
| `monolog/monolog` | Système de logs | `composer require monolog/monolog` |
| `guzzlehttp/guzzle` | Requêtes HTTP | `composer require guzzlehttp/guzzle` |
| `phpunit/phpunit` | Tests unitaires | `composer require phpunit/phpunit` |
| `vlucas/phpdotenv` | Fichiers .env | `composer require vlucas/phpdotenv` |
| `symfony/console` | Applications console | `composer require symfony/console` |
| `laravel/framework` | Framework web | `composer require laravel/framework` |

---

## 4.5 — Rechercher sur Packagist

Sur **packagist.org**, pour chaque bibliothèque tu vois :
- Le nombre de téléchargements
- La version stable
- La documentation
- La commande Composer à utiliser

```bash
# Exemple : chercher une bibliothèque de validation
composer require respect/validation
```

---

## 4.6 — Packagist dans notre projet E-Wallet

On pourrait utiliser ces packages :
```bash
# Pour valider les numéros de téléphone
composer require giggsey/libphonenumber-for-php

# Pour les logs de transactions
composer require monolog/monolog

# Pour les tests
composer require phpunit/phpunit
```

---

# Résumé général

| Concept | Rôle dans le projet |
|---|---|
| Fonctions anonymes | Callbacks dans array_filter, array_map |
| Fonctions fléchées | Version courte des closures (fn() =>) |
| Closures | Capturer des variables avec `use` |
| array_filter | Filtrer les transactions par téléphone |
| array_search | Trouver l'index d'un wallet |
| array_column | Extraire les téléphones de tous les wallets |
| Composer | Gérer les bibliothèques externes du projet |
| Packagist | Trouver et publier des bibliothèques PHP |

---

# Conclusion

La Partie B apporte trois améliorations majeures :

1. **Code plus court** grâce aux fonctions natives `array_*`
2. **Code mieux organisé** grâce aux Namespaces
3. **Code extensible** grâce à Composer et Packagist

Ces outils sont utilisés dans **tous les projets PHP professionnels** (Laravel, Symfony, WordPress...).

---

*Présentation réalisée dans le cadre du projet E-Wallet PHP*
*Partie B — Professionnalisation et Outils Modernes*
