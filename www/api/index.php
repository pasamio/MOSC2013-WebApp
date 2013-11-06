<?php

require(dirname(dirname(__DIR__)) . '/bootstrap.php');

use MOSC\App\API;

$app = new API();

$app->execute();