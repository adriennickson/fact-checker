<p style="font-size: 3em"><a href="http://factchecker.ekwali.xyz/" target="_blank">
    ✅ Factchecker
</a></p>

[MOMO Adrien Nickson][5]

[✅ Factchecker][1] est un **Projet PHP développé en symfony5.2**. C'est une plateforme qui vous permet de vérifiez les infos que vous recevez.

Installation
------------

* Installez **PHP >7.4**, **Composer** et **MySQL** sur votre PC 
* Ouvrir un terminal à l'endroit ou vous voulez stoquer le projet
* Faites  `git clone git@github.com:adriennickson/fact-checker.git && cd fact-checker`
* Ensuite  `composer i`
* Ensuite  `cp .env .env.local`
* Créez une base mysql et utilisez cette dernière pour mettre à jour la ligne 30 du fichier **.env.local**
* Ensuite  `bin/console doctrine:schema:update --force`
* Obtenez une [clef d'API Custom Search][2] pour le moteur de recherche de google 
* Obtenez une [clef CX][3] pour le moteur de recherche de google avec comme site ***google.fr***
* Utilisez les deux éléments précédent pour mettre à jour les lignes 34 et 35 du fichier **.env.local**
* Créez un [compte développeur twitter][4] et créez un accès à l'API twitter pour ettre à jour les lignes 37, 38 et 39 du fichier **.env.local**
* Faites `php -S localhost:8080 -t public/` et rendez vous à l'adresse http://localhost:8080 sur votre navigateur. 




[1]: http://factchecker.ekwali.xyz/
[2]: https://developers.google.com/custom-search/v1/overview
[3]: https://cse.google.com/
[4]: https://developer.twitter.com/en/docs/twitter-api/getting-started/getting-access-to-the-twitter-api
[5]: https://adriennickson.github.io/