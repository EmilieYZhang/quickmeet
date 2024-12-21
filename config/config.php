<?php
// @author: Japmann Sarin
// database settings
// Database settings

if (!defined('DB_HOST')) {
    define('DB_HOST', 'mysql.cs.mcgill.ca');
}

if (!defined('DB_USER')) {
    define('DB_USER', 'confidential');
}

if (!defined('DB_PASS')) {
    define('DB_PASS', 'confidential');
}

if (!defined('DB_NAME')) {
    define('DB_NAME', 'confidential');
}

if (!defined('MAIL_HOST')) {
    define('MAIL_HOST', 'smtp.gmail.com');
}

if (!defined('MAIL_USERNAME')) {
    define('MAIL_USERNAME', 'confidential');
}

if (!defined('MAIL_PASSWORD')) {
    define('MAIL_PASSWORD', 'confidential'); 
}

if (!defined('MAIL_PORT')) {
    define('MAIL_PORT', 587);
}

// Set the default timezone for the app
date_default_timezone_set('America/Toronto');
?>