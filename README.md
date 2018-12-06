# rested

## Introduction

Ceci est un projet d'application web fonctionnant avec un backend sous Symfony3.4 et un frontend sous React.

## Pré-requis

Afin de faciliter son installation, le projet utilise docker.<br/>
https://docs.docker.com/install/

## Installation

```
git clone https://github.com/Insomniak313/rested.git
cd rested
sudo ./install.bash
```

## Qu'est-ce qui vient de se passer ?

Lors de l'installation, les conteneurs sont créés (cf ci-dessous pour leur description).</br>
 
La base de donnée MySQL est crée, son schéma est mis à jour et les données issues de l'API https://api.gouv.fr/api/api-geo.html sont stockées (grâce à la commande `php bin/console app:load-data`). Dans le cadre de ce projet, on se limite simplement à récupérer les régions et les départements français.

Le back propose alors différentes routes pour retourner les données, que le front consume.
<ul>     
<li>L'environnement back "PROD" est accessible à l'URL http://localhost:10081</li>
<li>L'environnement back "DEV" est accessible à l'URL http://localhost:10082</li>
<li>L'environnement front est accessible à l'URL http://localhost:10083</li>
<li>PhpMyAdmin est accessible à l'URL http://localhost:10085</li>
</ul>

## Descriptif des conteneurs

Lors de l'installation, 5 conteneurs sont créés.<br/>
Ils écoutent tous sur des ports "non-standard" afin que ceux-ci ne soient pas déjà utilisés par un service quelconque.

### 1 - MySQL

Il contient la base de données.<br/>
##### Ports ouverts en écoute :
<ul>
<li>13306</li>
</ul>

### 2 - PhpMyAdmin

Une interface graphique pour la base de données.<br/>
##### Ports ouverts en écoute :
<ul>
<li>10085</li>
</ul>

### 3 - Composer

Ce conteneur permet d'installer les différentes dépendances du projet back, sans avoir a installer Composer sur le conteneur "PHP7.2+Apache2.4". <br/>
Une fois que les vendors ont été récupérés et que les scripts post-installation ont été lancés, ce conteneur est terminé (il n'est alors plus utile).

### 4 - PHP7.2+Apache2.4 "Dev" & "Prod"

Ce conteneur à accès au code source de la partie back et aux vendors récupérés par le conteneur Composer. <br/>
2 VirtualHost sont installés :
<ul>
<li>Un environnement "PROD", accessible à l'URL http://localhost:10081</li>
<li>Un environnement "DEV", accessible à l'URL http://localhost:10082</li>
</ul>

##### Ports ouverts en écoute :
<ul>
<li>10081</li>
<li>10082</li>
</ul>

### 5 - Nginx

Ce conteneur à accès au code source de la partie front.<br/>
Il lance le serveur de développement, accessible à l'URL http://localhost:10083

##### Ports ouverts en écoute :
<ul>
<li>10083</li>
</ul>

## Environnement de développement

Les dossiers /front/ et /back/ sont synchronisés sur les conteneurs. Toute modification des sources est directement prise en compte. <br/>
Les executables (php, yarn) doivent par contre être lancés sur les conteneurs car ils ne sont pas présent sur l'hôte.<br/>

Afin de faciliter le développement, des scripts portant le nom de l'executable à lancer sur l'hôte ont été rajoutés dans le dossier /script : <br/>
-> `php bin/console ...` devient `./scripts/php bin/console ...`<br/>
-> `yarn test` devient `./scripts/yarn test`

## Axes d'amélioration

<ul>
<li>Back<ul>
    <li>Intégration de PHPUnit</li>
    <li>Amélioration des performances du Crawler</li>
    <li>Amélioration de la sécurité</li>
    </ul></li>
<li>Front<ul>
    <li>Intégration de Jest</li>
    <li>Intégration de Enzyme</li>
    </ul></li>
</ul>

## Problème éventuel lié à MySQL

Si lors de l'installation, des messages "The server requested authentication method unknown to the client" remontent c'est que l'installation de MySQL s'est mal passée. Depuis la version 8, MySQL propose de nouvelles façon de se connecter qui n'ont pas encore été implémentées dans PHP.</br>
Pour résoudre ce problème, lancer les commandes :
```
sudo docker exec -it $(sudo docker container ls --filter name=rested_mysql --quiet) /bin/bash
```

Vous vous retrouvez alors connecté au conteneur MySQL :
```
root@a50856802429:/# 
```
Rentrez alors la commande suivante pour vous connecter à MySQL :
```
mysql -uroot -proot
```

Une fois connecté à MySQL, exécuter la requête SQL suivante :
```
ALTER USER 'root' IDENTIFIED WITH mysql_native_password BY 'root';
```