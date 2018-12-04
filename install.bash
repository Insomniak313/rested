#!/bin/bash

# On vérifie si Docker est installé
command -v docker >/dev/null 2>&1 || {
	echo "Veuillez installer Docker : https://docs.docker.com/install/"
	exit 1;
}

# On installe notre environnement Docker
sudo docker-compose up --build