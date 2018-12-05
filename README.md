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
./install.bash
```

## Descriptif des conteneurs

Lors de l'installation, 4 conteneurs sont créés :

### 1 - MySQL

Il contient la base de données.<br/>
##### Ports ouverts en écoute :
<ul>
<li>3306</li>
</ul>

### 2 - Composer

Ce conteneur permet d'installer les différentes dépendances du projet back, sans avoir a installer Composer sur le conteneur "PHP7.2+Apache2.4". <br/>
Une fois que les vendors ont été récupérés et que les scripts post-installation ont été lancés, ce conteneur est terminé (il n'est alors plus utile).

### 3 - PHP7.2+Apache2.4 "Dev" & "Prod"

Ce conteneur à accès au code source de la partie back et aux vendors récupérés par le conteneur Composer. <br/>
2 VirtualHost sont installés :
<ul>
<li>Un environnement "PROD", accessible à l'URL http://localhost:81</li>
<li>Un environnement "DEV", accessible à l'URL http://localhost:82</li>
</ul>

##### Ports ouverts en écoute :
<ul>
<li>81</li>
<li>82</li>
</ul>

### 4 - Nginx

Ce conteneur à accès au code source de la partie front.<br/>
Il lance le serveur de développement.

##### Ports ouverts en écoute :
<ul>
<li>83</li>
</ul>

## Environnement de développement

Les dossiers /front/ et /back/ sont synchronisés sur les conteneurs. Toute modification des sources est directement prise en compte. <br/>
Les executables (php, yarn) doivent par contre être lancés sur les conteneurs car ils ne sont pas présent sur l'hôte.<br/>

Afin de faciliter le développement, des scripts portant le nom de l'executable à lancer sur l'hôte ont été rajoutés dans le dossier /script : <br/>
-> `php bin/console ...` devient `./scripts/php bin/console ...`<br/>
-> `yarn test` devient `./scripts/yarn test`
