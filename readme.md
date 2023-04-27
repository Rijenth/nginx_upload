# Serveur Nginx

Projet pour Hetic

```
src -> contenu de l'app
|
|
|---> functions -> php
|
|---> routes -> routes fichiers html
```

# Règles de nominations

## Nominations branches
`(type)/(what)`

type : 
- feat -> features
- fix -> correction de bug
- ui -> frontend
- docs -> documentations/commentaires

what : qu'est ce que vous faites. e.g : account-page

## Nominations fichier

Nominations des fichiers en camelCase

## Nominations des commits :

Comme vous voulez mais de preférences utiliser [gitmoji](https://marketplace.visualstudio.com/items?itemName=seatonjiang.gitmoji-vscode)

## Mise à jour du site

cd /var/www/html/serveur-nginx

git pull

sudo service nginx restart
