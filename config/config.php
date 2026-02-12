<?php

define('DSN', 'mysql:host=localhost;dbname=mylink_in_bio;port=3308');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');

// En local
define('BASE_URL', 'http://localhost/linkme/'); //Constante chemin URL

// En production (sur le serveur)
// define('BASE_URL', 'https://www.mon-site.com');

define('ROOT', dirname(__DIR__)); //Constante chemin physique