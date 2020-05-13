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

```

## REDIS Queue + cron jobs tables
```
mysql> show tables;
+--------------------+
| Tables_in_laradock |
+--------------------+
| cron_logs          |
| failed_jobs        |
| migrations         |
| redis_logs         |
| users              |
+--------------------+
5 rows in set (0.01 sec)

mysql> desc redis_logs;
+------------+-----------------+------+-----+---------+----------------+
| Field      | Type            | Null | Key | Default | Extra          |
+------------+-----------------+------+-----+---------+----------------+
| id         | bigint unsigned | NO   | PRI | NULL    | auto_increment |
| data       | text            | NO   |     | NULL    |                |
| created_at | timestamp       | YES  |     | NULL    |                |
| updated_at | timestamp       | YES  |     | NULL    |                |
+------------+-----------------+------+-----+---------+----------------+
4 rows in set (0.00 sec)

mysql> desc cron_logs;
+--------------+-----------------+------+-----+---------+----------------+
| Field        | Type            | Null | Key | Default | Extra          |
+--------------+-----------------+------+-----+---------+----------------+
| id           | bigint unsigned | NO   | PRI | NULL    | auto_increment |
| trigger_time | datetime        | NO   |     | NULL    |                |
| created_at   | timestamp       | YES  |     | NULL    |                |
| updated_at   | timestamp       | YES  |     | NULL    |                |
+--------------+-----------------+------+-----+---------+----------------+
4 rows in set (0.00 sec)

# cron job working example in scheduler pod also marked as critical pod.
# check laravel_app/app/Console/Kernel.php

    protected function schedule(Schedule $schedule)
    {
               $schedule->call(function () {
                    DB::table('cron_logs')->insert(['trigger_time' => date("Y-m-d H:i:s")]);
               })->everyMinute();
    }


mysql> select * from cron_logs \G;
*************************** 1. row ***************************
          id: 1
trigger_time: 2020-05-05 11:39:47
  created_at: NULL
  updated_at: NULL
*************************** 2. row ***************************
          id: 2
trigger_time: 2020-05-05 11:39:53
  created_at: NULL
  updated_at: NULL
*************************** 3. row ***************************
          id: 3
trigger_time: 2020-05-05 11:42:01
  created_at: NULL
  updated_at: NULL
*************************** 4. row ***************************
          id: 4
trigger_time: 2020-05-05 11:42:32
  created_at: NULL
  updated_at: NULL
*************************** 5. row ***************************
          id: 5
trigger_time: 2020-05-05 11:43:00
  created_at: NULL
  updated_at: NULL
5 rows in set (0.01 sec)


## db migrate job helm chart pre-install hook 
## check laravel_app/database/migrations

mysql> desc users;
+-------------------+-----------------+------+-----+---------+----------------+
| Field             | Type            | Null | Key | Default | Extra          |
+-------------------+-----------------+------+-----+---------+----------------+
| id                | bigint unsigned | NO   | PRI | NULL    | auto_increment |
| name              | varchar(255)    | NO   |     | NULL    |                |
| email             | varchar(255)    | NO   | UNI | NULL    |                |
| email_verified_at | timestamp       | YES  |     | NULL    |                |
| password          | varchar(255)    | NO   |     | NULL    |                |
| address           | text            | NO   |     | NULL    |                |
| remember_token    | varchar(100)    | YES  |     | NULL    |                |
| created_at        | timestamp       | YES  |     | NULL    |                |
| updated_at        | timestamp       | YES  |     | NULL    |                |
+-------------------+-----------------+------+-----+---------+----------------+
9 rows in set (0.01 sec)

## queue working on php-worker
curl --location --request POST 'http://127.0.0.1:8000/redis-queue-test' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'name=anish'

## check laravel_app/app/Http/Controllers/RedisTestQueueController.php

## Php Controller for REDIS Job Controller

class RedisTestQueueController extends Controller
{
        public function store(Request $request)
        {
            $data = $request->all();
            $json_encode = json_encode($data);
            RedisTestJob::dispatch($json_encode);
            return response()->json(['message'=>'data successfully submitted']);
        }
}

## Laravel Job 

## check laravel_app/app/Http/Jobs/RedisTestJob.php

class RedisTestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::table('redis_logs')->insert(['data' => $this->data]);
    }
}

## Queue job output

mysql> select * from redis_logs \G;
*************************** 1. row ***************************
        id: 1
      data: {"name":"anish"}
created_at: NULL
updated_at: NULL
1 row in set (0.00 sec)
```
