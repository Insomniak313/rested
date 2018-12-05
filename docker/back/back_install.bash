#!/usr/bin/env bash

composer install --no-interaction --prefer-source

# Définition des droits
chown -R www-data:www-data var/cache
chown -R www-data:www-data var/logs
chown -R www-data:www-data var/sessions

# Suppression des logs, du cache et des sessions
rm -rf var/logs/* var/cache/* var/sessions/*

# Création de la base de donnnées pré-existante
php bin/console doctrine:database:drop --force

# Création de la base de donnnées
php bin/console doctrine:database:create

# Mise à jour du schéma
php bin/console doctrine:schema:update --force

# Suppression du cache
php bin/console cache:clear --env=prod

# Mise à jour des droits pour le cache et les logs
chmod 777 -R var/cache var/logs var/sessions