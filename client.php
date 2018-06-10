<?php
define('__ROOT__', dirname(__FILE__));
require_once(__ROOT__.'/mongodb.php');

$mongodb = new mongodb('log');
$result = $mongodb->query('test');
var_dump($result);