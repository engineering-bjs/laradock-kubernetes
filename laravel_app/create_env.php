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

createEnv();
