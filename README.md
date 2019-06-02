# REST RATP API (v4)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/0e42a9e2-ecb8-4412-8c88-b8f417f5ae2c/mini.png)](https://insight.sensiolabs.com/projects/0e42a9e2-ecb8-4412-8c88-b8f417f5ae2c)

This project turnkey is distributed as a middleware to expose RATP data as REST resources.
You can retrieve real time schedules for any given RER (train), Metro, Tramway, Bus or Noctilien stop in real time.

This project uses the package [horaires-ratp-sdk](https://github.com/pgrimaud/horaires-ratp-sdk) which consume the official RATP API.
 
Old version 3 README is available [here](https://github.com/pgrimaud/ratp-api-rest/blob/v3/README.md).

## Requirements

 - Access to the official RATP API (see [here](https://data.ratp.fr/explore/dataset/horaires-temps-reel/))
 - PHP >= 7.2
 - Redis server (for cache)

## Installation

First :

```
git clone git@github.com:pgrimaud/ratp-api-rest
cd ratp-api-rest
composer install
```

- Then configure your favorite webserver (Apache or Nginx) : 
[more informations here](http://symfony.com/doc/current/setup/web_server_configuration.html).
- Manage your `.env` file : [more informations here](https://symfony.com/doc/current/configuration.html#the-env-file-environment-variables).

## TODO

- Add Opendata SNCF data (WIP)

## Known bugs (on the 2nd, June 2019)

- None

## Feedback or questions

You can [create an issue](https://github.com/pgrimaud/ratp-api-rest/issues) if needed or contact me on [Twitter](https://twitter.com/pgrimaud_).

## Demo

Demo is available here : [https://api-ratp.pierre-grimaud.fr/v4/](https://api-ratp.pierre-grimaud.fr/v4/)

## Contributing

Pull requests are appreciated. Everyone is welcome and even encouraged to contribute with their own improvements.

## Copyrights

This project is not affiliated with or endorsed by the [RATP](http://www.ratp.fr).

## License

Licensed under the terms of the MIT License.
