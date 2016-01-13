<?php /** SamsonPHP init script */

/** Set default locale to - Russian */
define('DEFAULT_LOCALE', 'ru');

// Check if composer is installed
if (!file_exists('../vendor/autoload.php')) {
    die('/vendor folder does not exists, probably you have not installed composer dependencies(composer install)');
}

/** Load SamsonPHP framework */
require('../vendor/autoload.php');

/** Set supported locales */
setlocales('ru');

/** Start SamsonPHP web-application */
s()
    ->environment(getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : null)
    ->composer()                                        // Load configuration from composer.json
    ->subscribe('core.e404', 'e404__handler')               // Set e404 error handler
    ->load('../src/main')                               // Load main module
    ->start('main');                                    // Start framework
