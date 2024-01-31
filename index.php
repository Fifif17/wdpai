<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::get('', 'SetController');
Routing::get('mainPage', 'SetController');
Routing::get('myAccount', 'SetController');
Routing::get('register', 'SecurityController');
Routing::get('login', 'SecurityController');
Routing::get('logout', 'SecurityController');
Routing::get('insertUserHistory', 'SetController');
Routing::get('searchSets', 'SetController');
Routing::get('setPage', 'SetController');
Routing::get('addSet', 'SetController');
Routing::get('addWord', 'SetController');
Routing::get('removeSet', 'SetController');
Routing::get('removeWord', 'SetController');
Routing::get('learnPanel', 'SetController');
Routing::get('unloadLearnPanel', 'SetController');

Routing::run($path);
