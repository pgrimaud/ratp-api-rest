# REST RATP API

This project turnkey is distributed as a middleware to expose RATP data as REST resources.
You can retrieve real time schedules for any given RER (train), Metro, Tramway, Bus or Noctilien stop in real time.

This project uses the package [horaires-ratp-sdk](https://github.com/pgrimaud/horaires-ratp-sdk) which consume the official RATP API.
 
## Translations

This README file is also available in [french](https://github.com/pgrimaud/ratp-api-rest/blob/master/README-FR.md).

## Requirements

 - Access to the RATP API (see [here](https://data.ratp.fr/explore/dataset/horaires-temps-reel/))
 - PHP >= 5.6
 - Package php-soap (ext-soap extension)
 - Redis server (for cache)

## Installation

First :

```
git clone git@github.com:pgrimaud/ratp-api-rest
cd ratp-api-rest
composer install --no-dev
```

Then configure your favorite webserver with ```web/app.php``` as  entrypoint. [More informations here](http://symfony.com/doc/current/setup/web_server_configuration.html)

*Recommendation for production* : remove app_dev.php file from the ```web``` folder.

## TODO

- Enable / disable cache usage

## Known bugs

- ~~2017-02-27 : Tramways schedules don't work. I contacted RATP about it and I still waiting for a reply and/or a fix.~~

## Feedback or questions

You can [create an issue](https://github.com/pgrimaud/ratp-api-rest/issues) if needed or contact me on [Twitter](https://twitter.com/pgrimaud_).

## Contributing

Pull requests are appreciated. Everyone is welcome and even encouraged to contribute with their own improvements.

## Copyrights

This project is not affiliated with or endorsed by the [RATP](http://www.ratp.fr).

## Licence

Licensed under the terms of the MIT License.
