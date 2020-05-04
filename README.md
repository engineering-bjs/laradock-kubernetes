# Laradock Kubernetes Helm Chart - 
Laradock kubernetes Helm Chart inspired by https://laradock.io/
solved @https://github.com/laradock/laradock/issues/1469
Laravel kubernetes Installation + cron jobs + REDIS Queue Scheduler

## Installing helm chart
```
$ helm install --name laradock-kubernetes . --namespace development 
```
## pass db + redis config in helm chart during installation as env
````
$ helm install --name laradock-kubernetes . --namespace development --set DB_HOST="",DB_DATABASE="",DB_PASSWORD="",REDIS_HOST="",REDIS_PASSWORD=""

````

## Uninstalling the Chart

To uninstall/delete the laradock-kubernetes deployment:

```console
$ helm delete --purge laradock-kubernetes
```

## Configuration

| Parameter                                 | Description                                   | Default                                                 |
|-------------------------------------------|-----------------------------------------------|---------------------------------------------------------|
| `APP_KEY`                             | APP_KEY                          |                                                     |
| `DB_HOST`                             | DB_HOST                          |                                                   |
| `DB_DATABASE`                             | DB_DATABASE                          |                                                     |
| `DB_USERNAME`                             | DB_USERNAME                          |                                                 |
| `DB_PASSWORD`                             | DB_PASSWORD                          |                                                     |
| `REDIS_HOST`                             | REDIS_HOST                          |                                                |
| `REDIS_PASSWORD`                             | REDIS_PASSWORD                          |                                                     |
| `PUSHER_APP_ID`                             | PUSHER_APP_ID                          |                                                     |
| `PUSHER_APP_KEY`                             | PUSHER_APP_KEY                          |                                                     |
| `PUSHER_APP_SECRET`                             | PUSHER_APP_SECRET                          |                                                     |
| `AWS_ACCESS_KEY_ID`                             | AWS_ACCESS_KEY_ID                          |                                                     |
| `AWS_SECRET_ACCESS_KEY`                             | AWS_SECRET_ACCESS_KEY                          |                                                     |
| `AWS_DEFAULT_REGION`                             | AWS_DEFAULT_REGION                          |                                                     |
| `AWS_BUCKET`                             | AWS_BUCKET                          |                                                     |
| `MAIL_USERNAME`                             | MAIL_USERNAME                          |                                                     |
| `MAIL_PASSWORD`                             | MAIL_PASSWORD                          |                                                     |
| `MIX_PUSHER_APP_KEY`                             | MIX_PUSHER_APP_KEY                          |                                                     |
| `MIX_PUSHER_APP_CLUSTER`                             | MIX_PUSHER_APP_CLUSTER                          |                                                     |
| `APP_NAME`                             | APP_NAME                          |                                                     |
| `APP_ENV`                             | APP_ENV                          |                                                     |
| `APP_DEBUG`                             | APP_DEBUG                          |                        |                             |
| `APP_URL`                             | APP_URL                          |                         |                            |
| `LOG_CHANNEL`                             | LOG_CHANNEL                          |                         |                            |
| `DB_CONNECTION`                             | DB_CONNECTION                          |                         |                            |
| `DB_PORT`                             | DB_PORT                          |                         |                            |
| `BROADCAST_DRIVER`                             | BROADCAST_DRIVER                          |                         |                            |
| `CACHE_DRIVER`                             | CACHE_DRIVER                          |                         |                            |
| `QUEUE_CONNECTION`                             | QUEUE_CONNECTION                          |                         |                            |
| `SESSION_DRIVER`                             | SESSION_DRIVER                          |                         |                            |
| `SESSION_LIFETIME`                             | SESSION_LIFETIME                          |                         |                            |
| `CACHE_DRIVER`                             | CACHE_DRIVER                          |                         |                            |
| `REDIS_PORT`                             | REDIS_PORT                          |                         |                            |
| `MAIL_MAILER`                             | MAIL_MAILER                          |                         |                            |
| `MAIL_HOST`                             | MAIL_HOST                          |                         |                            |
| `MAIL_PORT`                             | MAIL_PORT                          |                         |                            |
| `MAIL_ENCRYPTION`                             | MAIL_ENCRYPTION                          |                         |                            |
| `MAIL_FROM_ADDRESS`                             | MAIL_FROM_ADDRESS                          |                         |                            |
| `MAIL_FROM_NAME`                             | MAIL_FROM_NAME                          |                         |                            |
| `PUSHER_APP_CLUSTER`                             | PUSHER_APP_CLUSTER                          |                         |                            |


# Docker build for all required containers
```
$ cd laravel_app/

# build php-fpm
$ docker build -t anishdhanka/php-fpm -f docker/php-fpm/Dockerfile .

# build docker nginx
$ docker build -t anishdhanka/nginx -f docker/nginx/Dockerfile .

# build docker workspace
$ docker build -t anishdhanka/workspace -f docker/workspace/Dockerfile .

# build docker php-worker
$ docker build -t anishdhanka/php-worker -f docker/php-worker/Dockerfile .
```
## Note
I have installed redis and mysql from statefulsets manually , used redis for session + queue drivers and mysql as db  

## Set kube environment to laravel .env
Check create_env.php in laravel_app directory
```
<?php
function createEnv()
{
    $environment_variables = getenv();
    $env = fopen('.env', 'w+');
    foreach ($environment_variables as $key => $environment_variable) {
        fwrite($env, $key . "='" . getenv($key) . "'\n");
    }

    fclose($env);
}
```

