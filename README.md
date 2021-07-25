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

## Presentation :

### Before the D-Day.

Steps to verify that all the projects is setup for the presentation :

1. Go the the apache directory with `cd /var/www`
2. Ensure the branch main of **Ultraviolet_CMS** is pulled and come from the last version
3. Ensure that **.env** is present inside **www/** and fully prepared.
4. Copy the **www/** folder from the project with `cp -r Ultraviolet_CMS/www html`
5. Change the owner of the new created directory with `chown -R www-data:root html`
6. Then compress and keeping the permissions with `tar -pczvf html.tar.gz html`
7. Delete the old one html with `rm -rf html`
8. Ensure **ultraviolet** is an **_empty_** existing database on the server

### During

The project has been developed in order to deploy it just by un-zip the source code.
In order to do so :

1. Uncompress the archive **html.tar.gz** with `tar -pxzvf html.tar.gz html`
2. Go to the site and proceed the forms
3. Show how the introduction (see "votre premier article")
4. Then run the populate script on **/base-de-donnee/peupler** to fill the database
5. Continue.

## Authors

This project is developed by five people:

|                                                      |                                                             |
| ---------------------------------------------------- | :---------------------------------------------------------: |
| [Joëlle CASTELLI](https://github.com/JoelleCastelli) | ![](https://img.shields.io/github/followers/JoelleCastelli) |
| [Coraline ESEDJI](https://github.com/coco-as-co)     |   ![](https://img.shields.io/github/followers/coco-as-co)   |
| [Sami ZERRAI](https://github.com/SamiZerrai)         |   ![](https://img.shields.io/github/followers/SamiZerrai)   |
| [Romain PIERUCCI](https://github.com/Norudah)        |    ![](https://img.shields.io/github/followers/Norudah)     |
| [Sylvain BOUDACHER](https://github.com/sulycate)     |    ![](https://img.shields.io/github/followers/sulycate)    |
