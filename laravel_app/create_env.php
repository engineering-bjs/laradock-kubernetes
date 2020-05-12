<?php
function createEnv()
{
    $environment_variables = getenv();
    //  if you want to add more environment then added to array so it will only allow to set to .env instead to all env from kubernetes
    $laravel_env_keys = array(
        "APP_NAME",
        "APP_ENV",
        "APP_KEY",
        "APP_DEBUG",
        "APP_URL",
        "LOG_CHANNEL",
        "DB_CONNECTION",
        "DB_HOST",
        "DB_PORT",
        "DB_DATABASE",
        "DB_USERNAME",
        "DB_PASSWORD",
        "BROADCAST_DRIVER",
        "CACHE_DRIVER",
        "QUEUE_CONNECTION",
        "SESSION_DRIVER",
        "SESSION_LIFETIME",
        "REDIS_HOST",
        "REDIS_PASSWORD",
        "REDIS_PORT",
        "MAIL_MAILER",
        "MAIL_HOST",
        "MAIL_PORT",
        "MAIL_USERNAME",
        "MAIL_PASSWORD",
        "MAIL_ENCRYPTION",
        "MAIL_FROM_ADDRESS",
        "MAIL_FROM_NAME",
        "AWS_ACCESS_KEY_ID",
        "AWS_SECRET_ACCESS_KEY",
        "AWS_DEFAULT_REGION",
        "AWS_BUCKET",
        "PUSHER_APP_ID",
        "PUSHER_APP_KEY",
        "PUSHER_APP_SECRET",
        "PUSHER_APP_CLUSTER",
        "MIX_PUSHER_APP_KEY",
        "MIX_PUSHER_APP_CLUSTER"
    );
    $env = fopen('.env', 'w+');
    foreach ($environment_variables as $key => $environment_variable) {
        if (in_array($key, $laravel_env_keys)) {
            if (count(explode(' ', getenv($key))) > 1) {
                fwrite($env, $key . "='" . getenv($key) . "'\n");
            } else {
                fwrite($env, $key . "=" . getenv($key) . "\n");
            }

        }
    }

    fclose($env);
}

createEnv();
