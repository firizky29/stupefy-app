<?php
    define('DB_HOST', getenv('DB_HOST'));
    define('DB_PORT', getenv('DB_PORT'));
    define('DB_NAME', getenv('MYSQL_DATABASE'));
    define('DB_USER', getenv('MYSQL_USER'));
    define('COOKIE_AUTH_SECRET', getenv('COOKIE_AUTH_SECRET'));
    define('DB_PASSWORD', getenv('MYSQL_PASSWORD'));
    define('CALLBACK_SECRET', getenv('CALLBACK_SECRET'));
    define('DB_ALBUM_TABLE', 'album');
    define('DB_USER_TABLE', 'user');
    define('DB_SONG_TABLE', 'song');
    define('DB_SUBSCRIPTION_TABLE', 'subscription');
    define('COOKIE_AUTH_EXPIRE', 86400);
    define('SOAP_API_KEY', getenv('SOAP_API_KEY'));
    define('SOAP_IP_ADDRESS', getenv('SOAP_IP_ADDRESS'));
    define('SOAP_PORT', getenv('SOAP_PORT'));
    define('APP_IP_ADDRESS', getenv('APP_IP_ADDRESS'));
    define('APP_PORT', getenv('APP_PORT'));
?>