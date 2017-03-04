# REST RATP API

Ce projet clé-en-main est distribué comme une passerelle pour générer des données RATP au format REST.
Vous pouvez récupérer les horaires en temps réel pour tout arrêt RER (train), métro, tramway, bus ou noctilien du réseau RATP.

Ce projet utilise la librairie [horaires-ratp-sdk](https://github.com/pgrimaud/horaires-ratp-sdk) qui exploite les données de l'API RATP officielle.
 
## Traductions

Ce README est aussi disponible en [anglais](https://github.com/pgrimaud/ratp-api-rest/blob/master/README.md).

## Prérequis

 - Accès à l'API RATP (voir [ici](https://data.ratp.fr/explore/dataset/horaires-temps-reel/))
 - PHP >= 5.6
 - Librairie php-soap (ext-soap extension)
 - Serveur Redis (pour le cache)

## Installation

Tout d'abord :

```
git clone git@github.com:pgrimaud/ratp-api-rest
cd ratp-api-rest
composer install --no-dev
```

Puis configurer votre serveur web et le faire pointer votre vhost sur ```web/app.php```. [Plus d'infos ici](http://symfony.com/doc/current/setup/web_server_configuration.html)

*Conseil pour la production* : supprimer le fichier app_dev.php du dossier ```web```.

## TODO

- Gérer l'activation / désactivation du cache.

## Bugs connus

- ~~2017-02-27 : Les horaires en temps réel des tramways ne fonctionnent pas. J'ai contacté la RATP à ce sujet et je suis toujours en attente d'une réponse.~~

## Retours ou questions

Vous pouvez [créer une issue](https://github.com/pgrimaud/ratp-api-rest/issues) ou me contacter sur [Twitter](https://twitter.com/pgrimaud_).

## Demo

Une démo est disponible ici : [https://api-ratp.pierre-grimaud.fr/v3/documentation](https://api-ratp.pierre-grimaud.fr/v3/documentation)

## Contributions

Les pull requests sont appréciées. N'hésitez pas à soumettre vos propres optimisations ou correctifs.

## Copyrights

Ce projet n'est pas affilié à la [RATP](http://www.ratp.fr).

## Licence

Licence MIT.
