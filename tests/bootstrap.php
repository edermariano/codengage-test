<?php
// tests/bootstrap.php
if (isset($_ENV['BOOTSTRAP_CLEAR_CACHE_ENV'])) {
    // executes the "php bin/console cache:clear" command
    passthru(sprintf(
        'php "%s/../bin/console" cache:clear --env=%s --no-warmup',
        __DIR__,
        $_ENV['BOOTSTRAP_CLEAR_CACHE_ENV']
    ));
}

if (isset($_ENV['BOOTSTRAP_CLEAR_DB'])) {

    if(file_exists(__DIR__ . '/../var/db_codengage_test.db')) {
        $teste = unlink(__DIR__ . '/../var/db_codengage_test.db');
    }

    passthru(sprintf(
        'php "%s/../bin/console" doctrine:database:create --env=%s',
        __DIR__,
        $_ENV['APP_ENV']
    ));

    passthru(sprintf(
        'php "%s/../bin/console" doctrine:schema:create --env=%s',
        __DIR__,
        $_ENV['APP_ENV']
    ));
}

require __DIR__.'/../vendor/autoload.php';
