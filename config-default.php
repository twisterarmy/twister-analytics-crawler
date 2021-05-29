<?php

// Debug
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Twister
define('TWISTER_PROTOCOL', 'http');
define('TWISTER_HOST', 'localhost');
define('TWISTER_PORT', 28332);
define('TWISTER_USERNAME', '');
define('TWISTER_PASSWORD', '');

// Geoplugin
define('GEOPLUGIN_PROTOCOL', 'http');
define('GEOPLUGIN_HOST', 'www.geoplugin.net');
define('GEOPLUGIN_PORT', 80);

// Torproject
define('TORPROJECT_PROTOCOL', 'https');
define('TORPROJECT_HOST', 'check.torproject.org');
define('TORPROJECT_PORT', 443);

// DB
define('DB_HOSTNAME', 'localhost');
define('DB_PORT', '3306');
define('DB_DATABASE', '');
define('DB_USERNAME', '');
define('DB_PASSWORD', '');

// Options
define('EMAIL_ONLINE_PEERS', false);  // email address|false
define('EMAIL_OFFLINE_PEERS', false); // email address|false
define('EMAIL_NEW_PEERS', false);     // email address|false
