# PHP URL Shortener

Ce dépôt contient un service de raccourcissement d'URL simple et léger développé en PHP avec SQLite pour le stockage des données.

## Fonctionnalités

- Génération d'URL courtes à partir d'URL longues
- Gestion de la durée de vie des URL raccourcies
- Suppression des URL raccourcies
- Utilisation d'un token pour sécuriser l'accès à l'API
- Stockage des données dans SQLite

## Prérequis

- Serveur web supportant PHP 7.0 ou supérieur
- Extension SQLite pour PHP
- Module de réécriture d'URL (par exemple, mod_rewrite pour Apache)

## Quickstart

1. Clonez ce dépôt sur votre serveur web : `git clone https://github.com/LibreArbitre/PHP-URL-Shortener.git`

2. Configurez le token d'authentification dans les fichiers `short.php` et `delete.php` :
Remplacez `votre_token_ici` par votre token personnalisé.

3. Configurez votre serveur web pour utiliser la réécriture d'URL.
Pour Apache, ajoutez les règles suivantes dans un fichier `.htaccess` à la racine du projet :
```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.+)$ redirect.php?code=$1 [L,QSA]
```

4. Utilisez l'API pour générer, accéder et supprimer des URL raccourcies.

## Documentation

### Générer une URL courte

Effectuez une requête GET vers `short.php` avec les paramètres suivants :
- `token` : Votre token d'authentification
- `url` : L'URL à raccourcir (encodée en URL)
- `expires` : (optionnel) Durée de vie de l'URL raccourcie en secondes

Exemple de requête : https://votre-domaine.com/short.php?token=votre_token_ici&url=https%3A%2F%2Fexemple.com&expires=86400

### Accéder à une URL raccourcie

Visitez simplement l'URL raccourcie générée, par exemple : https://votre-domaine.com/abc123


### Supprimer une URL raccourcie

Effectuez une requête GET vers `delete.php` avec les paramètres suivants :
- `token` : Votre token d'authentification
- `code` : Le code de l'URL raccourcie à supprimer

Exemple de requête : https://votre-domaine.com/delete.php?token=votre_token_ici&code=abc123

