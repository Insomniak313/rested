# rested

## Introduction

Ceci est un projet d'application web fonctionnant avec un backend sous Symfony3.4 et un frontend sous React.

### Docker

Afin de faciliter son installation, le projet utilise docker.<br/>
Lors de l'installation, 3 conteneurs (à ce jour) sont créés :

####Un conteneur MySQL
Il contient la base de données
####Un conteneur PHP7.2+Apache2.4 "Dev"
Ce conteneur fait un **lien vers le code source** du backend (/back) et a son VirtualHost Apache qui pointe sur **app_dev.php**. L'avantage de ce lien est que l'on n'a pas a reconstruire le conteneur pour que les modifications du PHP soit prises en compte. L'inconvénient de ce conteneur est que la récupération des vendors composer doit se faire sur l'hôte (et il faut donc avoir composer d'installé sur sa machine).<br/>
Ce conteneur est accessible à l'URL http://localhost:82 .

####Un conteneur PHP7.2+Apache2.4 "Prod"
Ce conteneur à accès a **une copie du code source** du backend (/back) et a son VirtualHost Apache qui pointe sur **app.php**. Comme il travaille sur une copie du code source, ce conteneur est capable d'installer tout seul les vendors composer. Par contre, tant que l'on n'a pas reconstruit le conteneur celui-ci ne percoit pas les modifications du code PHP.<br/>
Ce conteneur est accessible à l'URL http://localhost:81 .
