#!/bin/bash

# On récupère le chemin vers le script
SCRIPT=$(readlink -f "$0")
SCRIPTPATH=$(dirname "$SCRIPT")

# On se place dans le dossier back et on commence l'installation
cd "$SCRIPTPATH/back"

# On vérifie si Composer est installé
command -v composer >/dev/null 2>&1 || {
	echo "Veuillez installer Composer : https://getcomposer.org/download/"
	exit 1;
}

# On vérifie si MySQL est installé
if (( $(ps -ef | grep -v grep | grep mysql | wc -l) == 0 ))
then
	echo "Veuillez installer MySQL"
	exit 1;
fi

# On récupère les informations nécessaires pour la configuration de MySQL
read -e -p "Veuillez saisir l'adresse IP de la base de données MySQL : `echo $'\n> '`" -i "127.0.0.1" DATABASE_HOST
read -e -p "Veuillez saisir l'utilisateur MySQL : `echo $'\n> '`" -i "root" DATABASE_USER
read -e -p "Veuillez saisir le mot de passe MySQL : `echo $'\n> '`" -i "null" DATABASE_PASSWORD

# On copie le fichier parameters.yml.dist en parameters.yml
cp app/config/parameters.yml.dist cp app/config/parameters.yml

# On renseigne les informations nécessaires dans le fichier parameters.yml
sed -i "s#.*database_host:.*#    database_host: $DATABASE_HOST#g" app/config/parameters.yml
sed -i "s#.*database_user:.*#    database_user: $DATABASE_USER#g" app/config/parameters.yml
sed -i "s#.*database_password:.*#    database_password: $DATABASE_PASSWORD#g" app/config/parameters.yml

# On installe les vendors composer
composer install

# On crée la base de données MySQL
php bin/console doctrine:database:create

# On met a jour le schema
php bin/console doctrine:schema:update --force

