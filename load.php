<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('memory_limit', '1024M');
ini_set('max_execution_time', 300);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

$bdd = new PDO(
    "mysql:host=localhost;
    dbname=".$GLOBALS['bdd']['base'].";
    charset=utf8",
    $GLOBALS['bdd']['login'],
    $GLOBALS['bdd']['mdp']
);

require_once  __DIR__.'/function.php';