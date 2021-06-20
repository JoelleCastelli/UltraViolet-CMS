#UltraViolet

## About

UltraViolet is a CMS based on a home-made MVC framework developed with PHP 7.4.
It is meant to let users create their own website to write articles and reviews about movies and TV shows thanks to
a database relying on the TMDB API.

## To launch the projet :
1. Run `docker-compose up -d` in the root directory
2. Run `npm install` in `www/` to get node dependencies
3. Run `npm run build` in `www/` to build the assets in the `www/dist` directory
4. In your database, execute the content of the SQL file in resources/uvtr_database.sql
5. Get [your own TMDB API key](https://developers.themoviedb.org/3/getting-started/introduction) and paste it in the .env file


## Authors

This project is developed by five people:

|                                                      |                                                             |
|-----------------------------------------------------|:-----------------------------------------------------------:|
| [Joëlle CASTELLI](https://github.com/JoelleCastelli) | ![](https://img.shields.io/github/followers/JoelleCastelli) |
| [Coraline ESEDJI](https://github.com/coco-as-co)     |    ![](https://img.shields.io/github/followers/coco-as-co)    |
| [Sami ZERRAI](https://github.com/SamiZerrai)      |     ![](https://img.shields.io/github/followers/SamiZerrai)      |
| [Romain PIERUCCI](https://github.com/Norudah)     |    ![](https://img.shields.io/github/followers/Norudah)    |
| [Sylvain BOUDACHER](https://github.com/sulycate)     |    ![](https://img.shields.io/github/followers/sulycate)    |


