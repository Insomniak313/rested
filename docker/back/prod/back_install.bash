#!/usr/bin/env bash

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

# Récupération des variables d'environnement Apache2
source /etc/apache2/envvars

# Execution d'Apache2 en mode détaché
exec apache2 -D FOREGROUND