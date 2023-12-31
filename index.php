<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::get('', 'DefaultController');
Routing::get('mainPage', 'DefaultController');
Routing::get('login', 'SecurityController');
Routing::get('addProject', 'ProjectController');

Routing::run($path);
