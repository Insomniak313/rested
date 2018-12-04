#!/bin/bash

# On récupère le chemin vers le script
SCRIPT=$(readlink -f "$0")
SCRIPTPATH=$(dirname "$SCRIPT")

# On vérifie si Composer est installé
command -v docker >/dev/null 2>&1 || {
	echo "Veuillez installer Composer : https://getcomposer.org/download/"
	exit 1;
}

# On se place dans le dossier back et on commence l'installation
cd "$SCRIPTPATH/back"

# On installe les vendors composer
composer install

# Définition des droits
chown -R www-data:www-data var/cache
chown -R www-data:www-data var/logs

# Suppression ds logs
rm -rf var/logs/* var/cache/*

# Création de la base de donnnées
php bin/console doctrine:database:create

# Mise à jour du schéma
php bin/console doctrine:schema:update --force

# Suppression du cache
php bin/console cache:clear --env=prod

# Mise à jour des droits pour le cache et les logs
chmod 777 -R var/cache var/logs