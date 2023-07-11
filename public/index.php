<?php

require_once '../helpers.php';
require_once '../vendor/autoload.php';
require_once '../routes/web.php';

$dotenv = Dotenv\Dotenv::createImmutable(base_path());
$dotenv->safeLoad();
