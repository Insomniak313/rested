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


Lors de l'installation, 3 conteneurs (à ce jour) sont créés :

### MySQL

Il contient la base de données.<br/>
Le port 3306 (port par défaut de MySQL) est ouvert.

### Composer

Ce conteneur permet d'installer les différentes dépendances du projet back, sans avoir a installer Composer. <br/>
Il est découplé du conteneur PHP afin de ne pas avoir à reconstruire le conteneur pour que les modifications du PHP soit prises en compte. <br/>
Une fois que les vendors ont été récupérés et que les scripts post-installation ont été lancés, ce conteneur est terminé (il n'est alors plus utile).

### PHP7.2+Apache2.4 "Dev" & "Prod"

Ce conteneur à accès au code source de la partie back et aux vendors récupérés par le conteneur Composer. <br/>
2 VirtualHost sont installés :
<ul>
<li>Un environnement "PROD", accessible à l'URL http://localhost:81</li>
<li>Un environnement "DEV", accessible à l'URL http://localhost:82</li>
</ul>