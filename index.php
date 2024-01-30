<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::get('', 'DefaultController');
Routing::get('mainPage', 'SetController');
Routing::get('myAccount', 'SetController');
Routing::get('register', 'SecurityController');
Routing::get('login', 'SecurityController');
Routing::get('logout', 'SecurityController');
Routing::get('addProject', 'ProjectController');
Routing::get('insertUserHistory', 'SetController');
Routing::get('searchSets', 'SetController');

Routing::run($path);
